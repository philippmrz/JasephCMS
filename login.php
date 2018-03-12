<?php
// Credentials for this server
require 'require/credentials.php';
$msg = array();

$connect = new mysqli($servername, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connecting to MySQL or database failed:<b><i> ". $connect->connect_error . "</b></i>");
}

function random_string($length) {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!$.";
    $c_length = strlen($characters);
    $string = "";
    for ($i=0;$i<$length;$i++) {
        $string .= $characters[rand(0, $c_length -1)];
    }
    return $string;
}

//if revisit when "stay logged in" was checked before
if (isset($_COOKIE["identifier"]) && isset($_COOKIE["token"])) {
    $identifier = $_COOKIE["identifier"];
    $token = $_COOKIE["token"];
    $result = $connect->query("SELECT TOKEN FROM user WHERE IDENTIFIER='$identifier'");
    $row = $result->fetch_assoc();
    $db_token = $row["TOKEN"];
    $hash_token = hash("sha256", $token);

    if ($hash_token == $db_token) {//new token
        $new_token = random_string(32);
        $hash_new_token = hash("sha256", $new_token);
        $update = $connect->query("UPDATE user SET TOKEN = '$hash_new_token' WHERE IDENTIFIER = '$identifier'");
        $get_uname = $connect->query("SELECT USERNAME WHERE IDENTIFIER = '$identifier'");
        $row_u = $get_uname->fetch_assoc();
        $db_uname = $row_u["USERNAME"];
        setcookie("identifier", $identifier, time()+86400*356);
        setcookie("token", $new_token, time()+86400*356);
        setcookie("logcheck", "true", time()+86400*356);
        setcookie("uname", $db_uname, time()+86400*356);


        header("location: //mach deine page hier rein");
        exit;
    } else {
        die("You're a cheater");
    }
}

if (isset($_POST["logbtn"])) {
    $uname = $_POST["uname"];
    $pword = $_POST["pword"];
    if (!empty($uname) && !empty($pword)) {
        $result = $connect->query("SELECT PASSWORD,USERNAME FROM user WHERE USERNAME='$uname'");
        $row = $result->fetch_assoc();
        $db_p = $row["PASSWORD"];
        if ($result) {
            if (password_verify($pword, $db_p)) {
                //pass
                if (isset($_POST["stay_li"])) {//when checking "RM"
                    $identifier = random_string(32);
                    $token = random_string(32);
                    $hash_token = hash("sha256", $token);
                    $reuslt = $connect->query("UPDATE user SET IDENTIFIER = '$identifier', TOKEN = '$hash_token' WHERE USERNAME = '$uname'");

                    setcookie("identifier", $identifier, time()+86400*356);
                    setcookie("token", $token, time()+86400*356);
                    setcookie("uname", $uname, time()+86400*356);
                }
            setcookie("logcheck", "true", time()+86400*356);
            header("location: index");
            exit;
        } else {
            //invalid
            array_push($msg, "Invalid password or username");
		 }
		}
		else{
			echo "query error";
		}
    } else {
        array_push($msg, "Please enter your username and your password");
    }
} 

$connect->close();
?>
<!doctype html>
<html>

<head>
  <?php require 'require/head.php';?>
</head>

<body>

<?php require 'require/header.php';?>
<script>applyStyle();</script>

<div id="auth">
<p id="title">Login</p>
<form method="POST" action="">
  <?php
  if (isset($uname) && !empty($uname)) {
      echo '<input id="username" type="text" name="uname" placeholder="Username" value="'. $uname .'"/><br>';
  } else {
      echo '<input id="username" type="text" name="uname" placeholder="Username"/><br>';
  }
  ?>
  <input id="password" type="password" name="pword" placeholder="Password"/><br>
  <input id="authbtn" type="submit" name="logbtn" value="Sign In"/><br>
  Remember me:<input type="checkbox" name="stay_li"/><br>
<?php
if (isset($_COOKIE["registered"])) {
      echo "<font size=\"2px\" color=\"green\"><i>" .$_COOKIE["registered"] . "</font></i><br>";
      setcookie("registered", "", time()-60*3);
  }

if (!empty($msg)) {
    foreach ($msg as $text) {
        echo "<font size=\"2px\" color=\"red\"><i>" . $text . "</font></i><br>";
    }
}
?>
<p id="sub">No Account yet? Click <a href="register.php">here</a> to register</p>

</form>
</div>
<?php require 'require/footer.php';?>
</body>
</html>
