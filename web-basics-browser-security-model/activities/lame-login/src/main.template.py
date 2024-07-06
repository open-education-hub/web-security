# SPDX-License-Identifier: BSD-3-Clause

from flask import Flask, request, render_template

app = Flask(__name__)


@app.route("/login", methods=["GET"])
def login():
    username = request.args.get("username")
    password = request.args.get("password")

    if username == "admin" and password == "Password123$":
        return "__TEMPLATE__"

    return "Neaahh"


@app.route("/lamelogin", methods=["GET"])
def lamelogin():
    return render_template("index.html")


if __name__ == "__main__":
    app.run(host="127.0.0.1")
