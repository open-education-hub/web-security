<?php
class PHPClass {
    public $condition;
    public $prop;

    function __construct() {

    }
    function __wakeup() {
        /**
         * IMPROVED COMMAND FILTER - Issue #87
         * Blocks creative bypass techniques including:
         * - strings, od, xxd, hexdump commands
         * - Wildcard bypasses: /bin/c?t, /bin/c*t, /bin/c[^^]t
         * - Path-based execution
         * - Command substitution
         * - Escape sequences
         */
        
        // Expanded forbidden commands list
        $forbbiden_commands = [
            // Original commands
            "cat", "head", "grep", "tail", "tac", "rev", 
            "awk", "sed", "more", "cut", "nl", "less", "sort",
            "python", "perl", "m4",
            
            // NEW: Binary/hex viewers (Issue #87)
            "strings", "od", "xxd", "hexdump", "hd",
            
            // NEW: Additional file readers
            "vim", "vi", "nano", "emacs", "ed",
            
            // NEW: Encoders
            "base64", "base32", "uuencode", "iconv",
            
            // NEW: Other dangerous commands
            "find", "xargs", "dd", "file",
            "curl", "wget", "nc", "netcat",
            "bash", "sh", "zsh", "dash", "ksh",
            "php", "ruby", "node", "nodejs",
            "tar", "zip", "unzip", "gzip"
        ];

        // Validate properties exist
        if (!isset($this->prop) || !isset($this->condition) || !$this->condition) {
            return;
        }

        // Normalize for case-insensitive checking
        $prop_lower = strtolower($this->prop);
        
        // 1. CHECK BLACKLISTED COMMANDS
        foreach ($forbbiden_commands as $cmd) {
            // Word boundary check to prevent false positives
            if (preg_match('/\b' . preg_quote($cmd, '/') . '\b/i', $this->prop)) {
                error_log("Blocked command: $cmd in " . $this->prop);
                return;
            }
        }
        
        // 2. BLOCK WILDCARD CHARACTERS (Issue #87)
        // Prevents: /bin/c?t, /bin/c*t, /bin/c[^^]t
        if (preg_match('/[*?{}\[\]]/', $this->prop)) {
            error_log("Blocked wildcard in: " . $this->prop);
            return;
        }
        
        // 3. BLOCK PATH-BASED EXECUTION (Issue #87)
        // Prevents: /bin/cat, /usr/bin/strings
        if (preg_match('/\/(?:bin|usr|sbin|etc|opt|tmp)\//', $this->prop)) {
            error_log("Blocked path execution: " . $this->prop);
            return;
        }
        
        // 4. BLOCK COMMAND SUBSTITUTION
        // Prevents: $(cat flag), `cat flag`
        if (preg_match('/\$\(|`/', $this->prop)) {
            error_log("Blocked command substitution: " . $this->prop);
            return;
        }
        
        // 5. BLOCK ESCAPE SEQUENCES
        // Prevents: \x63\x61\x74 (hex encoding of "cat")
        if (preg_match('/\\\\x[0-9a-fA-F]{2}/', $this->prop)) {
            error_log("Blocked hex escape: " . $this->prop);
            return;
        }
        
        // 6. BLOCK OCTAL SEQUENCES
        // Prevents: \143\141\164 (octal encoding of "cat")
        if (preg_match('/\\\\[0-7]{3}/', $this->prop)) {
            error_log("Blocked octal escape: " . $this->prop);
            return;
        }
        
        // 7. BLOCK PIPING AND REDIRECTION
        // Prevents command chaining
        if (preg_match('/[|><&;]/', $this->prop)) {
            error_log("Blocked piping/redirection: " . $this->prop);
            return;
        }
        
        // 8. BLOCK VARIABLE TRICKS
        // Prevents: $CMD where CMD=cat
        if (preg_match('/\$[A-Z_][A-Z0-9_]*/', $this->prop)) {
            error_log("Blocked variable substitution: " . $this->prop);
            return;
        }
        
        // 9. BLOCK QUOTE CONCATENATION
        // Prevents: ca''t or c"a"t
        if (preg_match('/[\'"][\'"]/', $this->prop)) {
            error_log("Blocked quote concatenation: " . $this->prop);
            return;
        }

        // If all checks pass, execute the property
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


                        <hr class="mt-5">
                        <small class="text-muted">
                            <strong>Challenge Improvements (Issue #87):</strong><br>
                            ✅ Blocked wildcard bypasses (?, *, [], {})<br>
                            ✅ Blocked path-based execution (/bin/, /usr/)<br>
                            ✅ Blocked dangerous commands (strings, od, xxd)<br>
                            ✅ Blocked command substitution and escape sequences<br>
                            ✅ Enhanced security filters
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
