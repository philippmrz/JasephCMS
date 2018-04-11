<?php
// Credentials for this server
require 'require/credentials.php';
require_once('require/backend.php');
$msg = array();

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connecting to MySQL or database failed:<b><i> " . $mysqli->connect_error . "</b></i>");
}

if (isset($_POST["logbtn"])) {
    $uname = $_POST["uname"];
    $pword = $_POST["pword"];
    if (!empty($uname) && !empty($pword)) {
        $result = $mysqli->query("SELECT PASSWORD,USERNAME FROM $usertable WHERE USERNAME='$uname'");
        $row = $result->fetch_assoc();
        $dbP = $row["PASSWORD"];
        if ($result) {
            if (password_verify($pword, $dbP)) {
                //pass
                if (isset($_POST["stay_li"])) { //when checking "RM"
                    $identifier = randomString(32);
                    $token = randomString(32);
                    $hashToken = hash("sha256", $token);
                    $result = $mysqli->query("UPDATE $usertable SET IDENTIFIER = '$identifier', TOKEN = '$hashToken' WHERE USERNAME = '$uname'");

                    setcookie("identifier","$identifier",time() + 86400 * 365);
                    setcookie("token","$token",time() + 86400 * 365);
}
setcookie("logcheck","true");
setcookie("uname","$uname");
header("Location: index");
exit;
            } else {
                //invalid
                array_push($msg, "Invalid password or username");
            }
        } else {
            echo "query error";
        }
    } else {
        array_push($msg, "Please enter your username and your password");
    }
}

$mysqli->close();
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <link rel="stylesheet" href="style/login.css" id="pagestyle">
</head>
<body>
<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php';?>
  <div id='content'>

    <form id='auth' method="POST" action="">

      <p id="title">Login</p>

      <?php
if (isset($uname) && !empty($uname)) {
    echo '<input id="username" type="text" name="uname" placeholder="Username" value="' . $uname . '"/><br>';
} else {
    echo '<input id="username" type="text" name="uname" placeholder="Username"/><br>';
}
?>
      <input class="password" type="password" name="pword" placeholder="Password"/><br>

      <input class='primary-btn' id="authbtn" type="submit" name="logbtn" value="Sign In"/><br>

      <div id='remember-me'>
        <p>Remember me</p>
        <input type="checkbox" name="stay_li"/><br>
      </div>

      <?php
if (!empty($msg)) {
    foreach ($msg as $text) {
        echo "<font size=\"2px\" color=\"red\"><i>" . $text . "</font></i><br>";
    }
}
?>

      <p id="sub">No Account yet? Click <a href="register.php">here</a> to register</p>

    </form>
  </div>
</div>

<script>applyStyle();</script>

</body>
</html>
