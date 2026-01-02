# SPDX-License-Identifier: BSD-3-Clause

from flask import Flask, request

app = Flask(__name__)


@app.route("/gimme", methods=["POST"])
def post_method_with_content_type():
    body = request.data
    flag = "__TEMPLATE__"

    if not body:
        return "Did you miss something?"

    if len(body) == len(flag):
        return flag

    return "Not great, not terrible! You should try 35 :)"


@app.route("/_healthcheck", methods=["GET"])
def healthcheck():
    return "OK", 200


if __name__ == "__main__":
    app.run(host="127.0.0.1")
