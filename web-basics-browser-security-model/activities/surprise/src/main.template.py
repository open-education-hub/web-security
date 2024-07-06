# SPDX-License-Identifier: BSD-3-Clause

from flask import Flask, request


app = Flask(__name__)


@app.route("/surprise", methods=["PUT"])
def put_method_with_content_type():

    flag = "__TEMPLATE__"

    if not request.content_type:
        return "I don't understand you :("

    if request.content_type == "application/json":

        if "name" in request.json:
            name = request.json["name"]
            return "\n".join(
                [f"Well done my friend, {name}! Here is your surprise:", flag]
            )

        return "Better! Give me your 'name' in this format"

    else:
        return "Good! But we should start using same language"
