<?php
class PHPClass {
    public $condition;
    public $prop;

    function __construct() {

    }
    function __wakeup() {
        $forbbiden_commands = [
            "cat", "head", "grep", "tail", "tac", "rev", 
            "awk", "sed", "more", "cut", "nl", "less", "sort",
            "python", "perl", "m4",
            "strings", "od", "xxd", "hexdump", "hd",
            "vim", "vi", "nano", "emacs", "ed",
            "base64", "base32", "uuencode", "iconv",
            "find", "xargs", "dd", "file",
            "curl", "wget", "nc", "netcat",
            "bash", "sh", "zsh", "dash", "ksh",
            "php", "ruby", "node", "nodejs",
            "tar", "zip", "unzip", "gzip"
        ];

        if (!isset($this->prop) || !isset($this->condition) || !$this->condition) {
            return;
        }

        // Normalize for case-insensitive checking
        $this->prop = strtolower($this->prop);
        
        foreach ($forbbiden_commands as $cmd) {
            // Word boundary check to prevent false positives
            if (preg_match('/\b' . preg_quote($cmd, '/') . '\b/i', $this->prop)) {
                return;
            }
        }
        
        // Block wildcard characters. Prevents: /bin/c?t, /bin/c*t, /bin/c[^^]t
        if (preg_match('/[*?{}\[\]]/', $this->prop)) {
            return;
        }
        
        // Block path-based execution. Prevents: /bin/cat, /usr/bin/strings
        if (preg_match('/\/(?:bin|usr|sbin|etc|opt|tmp)\//', $this->prop)) {
            return;
        }
        
        // Block command substitution. Prevents: $(cat flag), `cat flag`
        if (preg_match('/\$\(|`/', $this->prop)) {
            return;
        }
        
        // Block escape sequences. Prevents: \x63\x61\x74 (hex encoding of "cat")
        if (preg_match('/\\\\x[0-9a-fA-F]{2}/', $this->prop)) {
            return;
        }
        
        // Block octal sequences. Prevents: \143\141\164 (octal encoding of "cat")
        if (preg_match('/\\\\[0-7]{3}/', $this->prop)) {
            return;
        }
        
        // Block piping and redirection. Prevents command chaining
        if (preg_match('/[|><&;]/', $this->prop)) {
            return;
        }
        
        // Block variable tricks. Prevents: $CMD where CMD=cat
        if (preg_match('/\$[A-Z_][A-Z0-9_]*/', $this->prop)) {
            return;
        }
        
        // Block quote concatenation. Prevents: ca''t or c"a"t
        if (preg_match('/[\'"][\'"]/', $this->prop)) {
            return;
        }

        eval($this->prop);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    </head>

    <body>
        <div>
            <div class="container">
                <div class="row">
                    <div class="bg-white p-5 mx-auto col-md-8 col-10">
                        <h3 class="display-3">Handy Tools<br></h3>
                        <form method="GET">
                            <div class="form-group">
                                <label>Select tool</label>
                                <select name="tool" class="form-control">
                                    <option value="toupper">To Upper Case</option>
                                    <option value="unserialize">Unserialize</option>
                                    <option value="trim">Trim whitespaces</option>
                                    <option value="manny">Guess my last name: Manny...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Input</label>
                                <input name="input" type="text" class="form-control">
                                <small class="form-text text-muted"></small>
                            </div>
                            <?php
                                if (isset($_GET['tool']) && $_GET['tool'] == 'toupper') {
                                    echo var_dump(strtoupper($_GET['input']));
                                    echo "<br>"; echo "<br>"; echo "<br>";
                                } elseif (isset($_GET['tool']) && $_GET['tool'] == 'unserialize') {
                                    echo var_dump(unserialize($_GET['input']));
                                    echo "<br>"; echo "<br>"; echo "<br>";
                                } elseif (isset($_GET['tool']) && $_GET['tool'] == 'trim') {
                                    echo var_dump(str_replace(' ', '', $_GET['input']));
                                    echo "<br>"; echo "<br>"; echo "<br>";
                                } elseif (isset($_GET['tool']) && $_GET['tool'] == 'manny') {
                                    if (strtolower($_GET['input']) == 'iscusitul')
                                        echo "backup.zip";
                                    else
                                        echo "Wrong!";
                                    echo "<br>"; echo "<br>"; echo "<br>";
                                }
                            ?>
                            <input type="submit" class="btn btn-primary" name="submit" value="Submit" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
