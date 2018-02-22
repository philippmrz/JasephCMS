<!doctype html>
<html lang="de">
  <head>
  <title>Jaseph</title>
  </head>
  <body>

<?php
require 'require/credentials.php';
$msg = array();

$connect = new mysqli($servername, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connecting to MySQL or database failed:<b><i> " . $connect->connect_error . "</b></i>");
}

if (isset($_POST["regbtn"])) {
    $uname = $_POST["uname"];
    $pword = $_POST["pword"];
    $pwordval = $_POST["pwordval"];

    if (empty($uname)) {
        array_push($msg, "please enter a username");
    }

    if (empty($pword)) {
        array_push($msg, "please enter a password");
    }
    $result = $connect->query("SELECT * FROM user WHERE USERNAME='$uname'");
    if ($result->num_rows > 0) {
        array_push($msg, "username already exists");
    }

    if ($pword != $pwordval) {
        array_push($msg, "passwords must match");
    }

    if (count($msg) == 0) {
        $pword = password_hash($pword, PASSWORD_DEFAULT);
        $result = $connect->query("INSERT INTO user (USERNAME,PASSWORD)
            VALUES ('$uname','$pword')");
        if ($result) {
            header("Location: login.php");
            exit;
        } else {
            echo "query error";
        }
    }

}
$connect->close();
?>

<center>
  <h2>Register</h2>
  <form method="POST" action="">
  <table border=1>
  	<tr>
  		<td>Username:  </td>
  		<td>
        <?php
if (isset($uname) && !empty($uname)) {
    echo "<input type=\"text\" name=\"uname\"value=\"" . $uname . "\"/></td>";
} else {
    echo "<input type=\"text\" name=\"uname\"/></td>";
}
?>
  	</tr>
    <tr>
      <td>Password: </td>
      <td><input type="password" name="pword"/></td>
    </tr>
    <tr>
      <td>Password(again): </td>
      <td><input type="password" name="pwordval"/></td>
    </tr>
  </table></br>
  <input type="submit" name="regbtn" value="Register"/></br>
</form>
<?php
if (!empty($msg)) {
    foreach ($msg as $text) {
        echo "<font size=\"2px\" color=\"red\"><i>" . $text . "</font></i><br>";
    }
}
?>
</center>

</body>
</html>
