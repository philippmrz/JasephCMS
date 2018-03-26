<?php
// Credentials for this server
require 'require/credentials.php';
$msg = array();

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connecting to MySQL or database failed:<b><i> " . $mysqli->connect_error . "</b></i>");
}

function random_string($length)
{
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!$.";
    $c_length = strlen($characters);
    $string = "";
    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[rand(0, $c_length - 1)];
    }
    return $string;
}

//if revisit when "stay logged in" was checked before
if (isset($_COOKIE["identifier"]) && isset($_COOKIE["token"])) {
    $identifier = $_COOKIE["identifier"];
    $token = $_COOKIE["token"];
    $result = $mysqli->query("SELECT TOKEN FROM $usertable WHERE IDENTIFIER='$identifier'");
    $row = $result->fetch_assoc();
    $db_token = $row["TOKEN"];
    $hash_token = hash("sha256", $token);

    if ($hash_token == $db_token) { //new token
        $new_token = random_string(32);
        $hash_new_token = hash("sha256", $new_token);
        $update = $mysqli->query("UPDATE $usertable SET TOKEN = '$hash_new_token' WHERE IDENTIFIER = '$identifier'");
        $get_uname = $mysqli->query("SELECT USERNAME FROM $usertable WHERE IDENTIFIER = '$identifier'");
        $row_u = $get_uname->fetch_assoc();
        $db_uname = $row_u["USERNAME"];
        ?><script>setCookie("identifier",$identifier);</script><?php
?><script>setCookie("token",$new_token);</script><?php
?><script>setCookie("logcheck","true");</script><?php
?><script>setCookie("uname",$db_uname);</script>
        <script>redirect("index");</script>
        <?php
exit;
    } else {
        die("You're a cheater");
    }
}

if (isset($_POST["logbtn"])) {
    $uname = $_POST["uname"];
    $pword = $_POST["pword"];
    if (!empty($uname) && !empty($pword)) {
        $result = $mysqli->query("SELECT PASSWORD,USERNAME FROM $usertable WHERE USERNAME='$uname'");
        $row = $result->fetch_assoc();
        $db_p = $row["PASSWORD"];
        if ($result) {
            if (password_verify($pword, $db_p)) {
                //pass
                if (isset($_POST["stay_li"])) { //when checking "RM"
                    $identifier = random_string(32);
                    $token = random_string(32);
                    $hash_token = hash("sha256", $token);
                    $result = $mysqli->query("UPDATE $usertable SET IDENTIFIER = '$identifier', TOKEN = '$hash_token' WHERE USERNAME = '$uname'");

                    ?><script>setCookie("identifier","<?php echo $identifier; ?>");</script><?php
?><script>setCookie("token","<?php echo $token; ?>");</script><?php
}
                ?><script>setCookie("logcheck","true");</script><?php
?><script>setCookie("uname","<?php echo $uname; ?>");</script>

                <script>redirect("index");</script>
                <?php
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
  <div id='sidebar'></div>
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
