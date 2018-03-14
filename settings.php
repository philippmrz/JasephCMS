<?php
// Credentials for this server
require 'require/credentials.php';

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connecting to MySQL or database failed:<b><i> ". $mysqli->connect_error . "</b></i>");
}

$uname = $_COOKIE["uname"];
$visibility = //evtl setting ob user anonym bleiben will
$admin = //checken ob user admin is


if(isset($_POST["canclbtn"])){
  ?><script>redirect("index");</script><?php
}

if(isset($_POST["savbtn"])){
  if(isset($_POST["new_uname"])){//update username
    $new_uname = $_POST["new_uname"];
    if($new_uname != $uname){

      $result = $mysqli->query("UPDATE $usertable SET USERNAME = '$new_uname' WHERE USERNAME = '$uname'");
      if($result){
        $uname = $new_uname;
        setcookie("uname",$uname,time()+86400*356);
      }
    }
  }
  if(isset($_POST["v"])){
    $visibility = true;
  }
  else{
    if(isset($_POST["inv"])){
      $visibility = false;
    }
  }
  ?><script>redirect("settings.php");</script><?php
}

$mysqli->close();
?>
<!doctype html>
<html>

<head>
  <?php require 'require/head.php';?>
</head>

<body>

<?php require 'require/header.php';?>
<script>applyStyle();</script>
<p id="settings-title">My Settings</p>
<div id="settings">
  <form method="POST" action="">
    <input id="btn" type="submit" name="canclbtn" value="Cancel"/>
    <input id="btn" type="submit" name="savbtn" value="Save Changes"/><br></br>

    <h1>ACCOUNT</h1></br>
    <div id="content">
      Username: <input id="input" type"text" name="new_uname" <?php echo "value=\"".$uname."\"";?>/>
      </br> Change Password?
    </div>

    <h1>PROFILE VISIBILITY</h1></br>
    <div id="content">
      <input type="radio" name="visibility" value="v"
      <?php if($visibility){echo "checked";}?> />
      Visible <font size=2px color=#c0c0c0><i>
        (Everyone can see that you are the author of your posts)</font></i>
    </br></br>
      <input type="radio" name="visibility" value="inv"
      <?php if(!$visibility){echo"checked";}?>/>
      Not Visible <font size=2px color=#c0c0c0><i>
        (The author of your posts is set to anonymous)</font></i>
    </div>

    <?php //wenn user admin is kann er neue admins/moderators machen ($admin vll mit was anderem austauschen)
    if(!$admin){echo "<font color=#c0c0c0>";}?>
      <h1>ADMINISTRATION</h1></br>
      <div id="content">
        Select Admins/Moderators from
        <?php if($admin){echo"<a href=\"userlist.php\">List of Users</a>";}
              else{echo "List of Users";}
              if(!$admin){echo"</font>";}?>
      </div>

      <input id="btn" type="submit" name="canclbtn" value="Cancel"/>
      <input id="btn" type="submit" name="savbtn" value="Save Changes"/><br></br>

  </form>
</div>
</div>
<?php require 'require/footer.php';?>
</body>
</html>
