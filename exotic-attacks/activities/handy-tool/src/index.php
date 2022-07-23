<?php
class PHPClass {
    public $condition;
    public $prop;

    function __construct() {

    }

    function __wakeup() {
        if (isset($this->prop) && isset($this->condition) && $this->condition == true) {
            if (strpos($this->prop, "cat") === false && strpos($this->prop, "head") === false && strpos($this->prop, "grep") === false && strpos($this->prop, "tail") === false && strpos($this->prop, "tac") === false && strpos($this->prop, "rev") === false && strpos($this->prop, "awk") === false && strpos($this->prop, "sed") === false && strpos($this->prop, "more") === false && strpos($this->prop, "cut") === false && strpos($this->prop, "nl") === false && strpos($this->prop, "less") === false && strpos($this->prop, "sort") === false && strpos($this->prop, "python") === false && strpos($this->prop, "perl") === false) {
                eval($this->prop);
            }
        }
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
