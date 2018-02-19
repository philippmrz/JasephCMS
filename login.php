<!doctype html>
<html lang="de">
  <head>
  <title>Jaseph</title>
  </head>
  <body>

<?php
$server = "localhost";
$user = "root";
$pw = "";
$db = "data";
$msg = array();

$connect = new mysqli($server,$user,$pw,$db);

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
        array_push($msg,"invalid password or username");
      }
    }
    else{
      echo"query error";
    }
  }
  else{
    array_push($msg,"please enter your username and your password");
  }
}
$connect->close();
?>
<center>
<h3>Login</h3>
<form method="POST" action="">
<table border=1>
	<tr>
		<td>Username:  </td>
		<td>
      <?php
      if(isset($uname) && !empty($uname)){
        echo "<input type=\"text\" name=\"uname\"value=\"" . $uname ."\"/></td>";
      }
      else{
        echo "<input type=\"text\" name=\"uname\"/></td>";
      }
    ?>
    </td>
	</tr>
  <tr>
    <td>Password: </td>
    <td><input type="password" name="pword"/></td>
  </tr>
</table></br>
<input type="submit" name="logbtn" value="Do it"/></br>
<?php
if(!empty($msg)){
  foreach($msg as $text){
    echo "<font size=\"2px\" color=\"red\"><i>" .$text . "</font></i><br>";
  }
}
?>
<h6>No Account yet? Click <a href="register.php">here</a> to register</h6>

</form>
</center>

</body>
</html>
