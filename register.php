<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
</head>
<body>
<?php require 'require/header.php';?>
<script>applyStyle();</script>
<?php
// Credentials for this server
require 'require/credentials.php';

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connecting to MySQL or database failed:<b><i> " . $mysqli->connect_error . "</b></i>");
}

if (isset($_POST["regbtn"])) {
    setcookie("identifier", "", time()-86400*356);
    setcookie("token", "", time()-86400*356);

    $uname = $_POST["uname"];
    $pword = $_POST["pword"];
    $pwordval = $_POST["pwordval"];
    $msg = [];

    //invalid inputs
    if (empty($uname)) {
        array_push($msg, "Please enter a username");
    }

    if (empty($pword)) {
        array_push($msg, "Please enter a password");
    }

    if (strlen($pword) < 6) {
        array_push($msg, "Password must be at minimum length of 6 letters");
    } else {
        if (ctype_upper($pword) || ctype_lower($pword)) {
            array_push($msg, "Password must contain at least one lowercase and one uppercase character");
        }
    }

    $result = $mysqli->query("SELECT * FROM $usertable WHERE USERNAME='$uname'");
    if ($result->num_rows > 0) {
        array_push($msg, "Username already exists");
    }

    if ($pword != $pwordval) {
        array_push($msg, "Passwords must match");
    }

    if (count($msg) == 0) {
        $pword = password_hash($pword, PASSWORD_DEFAULT);
        $result = $mysqli->query("INSERT INTO $usertable (USERNAME,PASSWORD) VALUES ('$uname','$pword')");
        if ($result) {
            setcookie("logcheck","true",time()+86400*356);
            setcookie("uname", $uname, time()+86400*356);
            ?>
            <script>redirect("index");</script>
            <?php
            exit;
        } else {
            echo "Query error";
        }
    }
}


$mysqli->close();
?>
<div id="auth">
  <p id="title">Register</p>
  <form method="POST" action="">
    <?php
    if (isset($uname) && !empty($uname)) {
        echo '<input id="username" type="text" name="uname" placeholder="Username" value="' . $uname .'"/><br>';
    } else {
        echo '<input id="username" type="text" name="uname" placeholder="Username"/><br>';
    }
    ?>
    <input class="password" type="password" name="pword" placeholder="Password"/><br>
    <input class="password" type="password" name="pwordval" placeholder="Repeat Password"/><br>
    <input id="authbtn" type="submit" name="regbtn" value="Register"/><br>
</form>
<?php
if (!empty($msg)) {
        foreach ($msg as $text) {
            echo "<font size=\"2px\" color=\"red\"><i>" . $text . "</font></i><br>";
        }
    }
?>
</div>
<?php require 'require/footer.php';?>
</body>
</html>
