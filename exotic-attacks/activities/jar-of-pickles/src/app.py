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

    # Quick fix in case of any env errors that require not using pickle module anymore
    # if cookie and cookie == "gASVLQAAAAAAAACMBXBvc2l4lIwGc3lzdGVtlJOUjBJjYXQgL2hvbWUvY3RmL2ZsYWeUhZRSlC4=":
    #     return <FLAG>

    if cookie:
        data = base64.urlsafe_b64decode(cookie)
        deserialized = pickle.loads(data)
        return make_response(json.dumps(deserialized))

    cookie = {"jars": "pickles"}

    pickle_payload = pickle.dumps(cookie)
    encodedPayloadCookie = base64.b64encode(pickle_payload)

    response = make_response(json.dumps("Pickle"))
    response.set_cookie("pickles", "")

    return response

if __name__ == "__main__":
    app.run(host="0.0.0.0", debug=True, port=80)
