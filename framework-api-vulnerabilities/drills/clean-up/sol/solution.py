import requests
import sys

PORT = "7004"


def echo_usage():
    """Display script usage and exit."""

    print(f"Usage:\npython {sys.argv[0]} local/remote\nOR\npython {sys.argv[0]} IP PORT")
    sys.exit()


if __name__ == "__main__":
    if len(sys.argv) <= 1:
        echo_usage()

    if len(sys.argv) > 2:
        URL = f"http://{sys.argv[1]}:{sys.argv[2]}"
    elif sys.argv[1] == "local":
        URL = "http://127.0.0.1:" + PORT
    elif sys.argv[1] == "remote":
        URL = "http://141.85.224.115:" + PORT
    else:
        echo_usage()

    res = requests.get(URL + "/api-v1/get-user-records.php")
    print("SSS" + res.text.split("SSS")[1].split('"')[0])
