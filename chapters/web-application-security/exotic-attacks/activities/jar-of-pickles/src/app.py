import base64
import json
import pickle
from flask import Flask, request, make_response, redirect, render_template

app = Flask(__name__)

@app.route("/", methods=['GET'])
def index():
    return render_template("index.html")

@app.route("/jar", methods=['GET'])
def jar():
    cookie = request.cookies.get("pickles")

    if cookie:
        data = base64.urlsafe_b64decode(cookie)
        deserialized = pickle.loads(data)

        return make_response(json.dumps(deserialized))

    response = make_response(json.dumps("Pickle"))
    response.set_cookie("pickles", "")

    return response

if __name__ == "__main__":
    app.run(host="0.0.0.0", debug=True, port=80)
