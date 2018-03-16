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
if (isset($_COOKIE["logcheck"])):
require 'require/credentials.php';

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connecting to MySQL or database failed:<b><i> ". $mysqli->connect_error . "</b></i>");
}

  $uname = ?><script>getCookie("uname");</script><?php

$visibility = true;//evtl setting ob user anonym bleiben will
$get_admin = $mysqli->query("SELECT ROLE FROM $usertable WHERE USERNAME = '$uname'");
if(!$get_admin) {
    echo $mysqli->error;
}
$row_admin = $get_admin->fetch_assoc();
if($row_admin["ROLE"] == "admin") {
    $admin = true;
} else {
    $admin = false;
}

if(isset($_POST["cancelbtn"])){
  ?><script>redirect("index");</script><?php
}

if(isset($_POST["savebtn"])){
  if(isset($_POST["new_uname"])){
    $new_uname = $_POST["new_uname"];
    if($new_uname != $uname){

      $result = $mysqli->query("UPDATE $usertable SET USERNAME = '$new_uname' WHERE USERNAME = '$uname'");
      if($result){
        $uname = $new_uname;
        setcookie("uname",$uname,time()+86400*365);
      }
    }
  }
  if(isset($_POST["v"])){
      $visibility = true;
  } else if (isset($_POST["inv"])){
      $visibility = false;
  }
  ?><script>redirect("settings.php");</script><?php
}

$mysqli->close();
?>
<p id="settings-title">My Settings</p>
<div id="settings">
  <form method="POST" action="">
    <input id="btn" type="submit" name="cancelbtn" value="Cancel"/>
    <input id="btn" type="submit" name="savebtn" value="Save Changes"/><br></br>

    <h1>ACCOUNT</h1></br>
    <div id="content">
      Username: <input id="input" type"text" name="new_uname" <?php echo "value=\"".$uname."\"";?>/>
      </br> Change Password?
    </div>

    <h1>PROFILE VISIBILITY</h1></br>
    <div id="content">
      <input type="radio" name="visibility" value="v"
      <?php if($visibility){echo "checked";}?> />
      Visible <font size=2px color=#a0a0a0><i>
        (Everyone can see that you are the author of your posts)</font></i>
    </br></br>
      <input type="radio" name="visibility" value="inv"
      <?php if(!$visibility){echo "checked";}?>/>
      Not Visible <font size=2px color=#a0a0a0><i>
        (The author of your posts is set to anonymous)</font></i>
    </div>

    <?php //wenn user admin is kann er neue admins/moderators machen ($admin vll mit was anderem austauschen)
    if($admin):?>
      <h1>ADMINISTRATION</h1></br>
      <div id="content">
        Select Admins/Moderators from
        <a href="userlist">List of Users</a>
      </div>
    <?php endif;?>
      <input id="btn" type="submit" name="cancelbtn" value="Cancel"/>
      <input id="btn" type="submit" name="savebtn" value="Save Changes"/><br></br>
  </form>
</div>
</div>
<?php else:?>
    <div class="content">
      <h1>Get Out</h1>
      <script>redirect('index');</script>
    </div>
<?php endif;?>
<?php require 'require/footer.php';?>
</body>
</html>
