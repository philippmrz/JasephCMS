<!doctype html>
<html>

<head>
  <?php require 'require/head.php';?>
</head>

<body>

<?php
require 'require/header.php';

// Credentials for this server
require 'require/credentials.php';
$msg = array();

$connect = new mysqli($servername,$username,$password,$dbname);

if($connect->connect_error){
  die("Connecting to MySQL or database failed:<b><i> ". $connect->connect_error . "</b></i>");
}

if(isset($_POST["logbtn"])){
  $uname = $_POST["uname"];
  $pword = $_POST["pword"];
  if(!empty($uname) && !empty($pword)){

	$result = $connect->query("SELECT PASSWORD,USERNAME FROM user WHERE USERNAME='$uname'");
	$row = $result->fetch_assoc();
	$db_p = $row["PASSWORD"];
    if($result){
      if(password_verify($pword,$db_p)){
        //pass
      header("location:"/*mainpage*/);
        exit;
      }
      else{
        //invalid
        array_push($msg,"Invalid password or username");
      }
    }
    else{
      echo"query error";
    }
  }
  else{
    array_push($msg,"Please enter your username and your password");
  }
}
$connect->close();
?>
<div id="auth">
<p id="title">Login</p>
<form method="POST" action="">
  <?php
  if(isset($uname) && !empty($uname)) {
    echo '<input id="username" "type="text" name="uname" placeholder="Username" value="'. $uname .'"/><br>';
  } else {
    echo '<input id="username" type="text" name="uname" placeholder="Username"/><br>';
  }
  ?>
  <input id="password" type="password" name="pword" placeholder="Password"/><br>
  <input id="authbtn" type="submit" name="logbtn" value="Sign In"/><br>
<?php
if(!empty($msg)){
  foreach($msg as $text){
    echo "<font size=\"2px\" color=\"red\"><i>" .$text . "</font></i><br>";
  }
}
?>
<p id="sub">No Account yet? Click <a href="register.php">here</a> to register</p>

</form>
</div>
</body>
</html>
