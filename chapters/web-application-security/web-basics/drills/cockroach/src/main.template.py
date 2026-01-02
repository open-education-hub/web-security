# SPDX-License-Identifier: BSD-3-Clause

from flask import Flask

app = Flask(__name__)


@app.route("/cockroach", methods=["DELETE"])
def delete_this_bastard():
    return "__TEMPLATE__"


@app.route("/_healthcheck", methods=["GET"])
def healthcheck():
    return "OK", 200


if __name__ == "__main__":
    app.run(host="127.0.0.1")
