<?php
    require_once('require/DatabaseConnection.class.php');

    if (!isLoggedIn()):
    header("Location: index");
    endif;
    require 'require/credentials.php';

    $mysqli = new mysqli($servername, $username, $password, $dbname);

    if ($mysqli->connect_error) {
        die("Connecting to MySQL or database failed:<b><i> " . $mysqli->connect_error . "</b></i>");
    }
    $uname = $_COOKIE["uname"];

    $getAdmin = $mysqli->query("SELECT ROLE FROM $usertable WHERE USERNAME = '$uname'");
    if (!$getAdmin) {
        echo $mysqli->error;
    }
    $rowAdmin = $getAdmin->fetch_assoc();
    if ($rowAdmin["ROLE"] == "ADMIN") {
        $admin = true;
    } else {
        $admin = false;
    }

    if(!isset ($_COOKIE["visibility"])){
      $visibility = true;
      setcookie("visibility","true",time() + 86400 * 365);
    }
    else{
      $visibility = $_COOKIE["visibility"];
    }

    if (isset($_POST["cancelbtn"])) {
        ?><script>redirect("index");</script><?php
    }

    if (isset($_POST["savebtn"])) {
        $msg = [];
        if (isset($_POST["newUname"])) {
            $newUname = $_POST["newUname"];
            if ($newUname != $uname) {

              $checkExist = $mysqli->query("SELECT USERNAME FROM $usertable WHERE UPPER(USERNAME) = UPPER('$newUname')");
              if($checkExist){
                  if ($result->num_rows > 0) {
                      array_push($msg, "Username already exists");
                  }
              }
              if(empty($msg)){
                $result = $mysqli->query("UPDATE $usertable SET USERNAME = '$newUname' WHERE USERNAME = '$uname'");
                if ($result) {
                    $uname = $newUname;
                    setcookie("uname", $uname);
                }
              }
            }
        }
        if ($_POST["visibility"] == "v") {
            $visibility = true;
            setcookie("visibility","true",time() + 86400 * 365);
        } else if ($_POST["visibility"] == "inv") {
        $visibility = false;
        setcookie("visibility","false",time() + 86400 * 365);
        }

    ?><script>redirect("settings.php");</script><?php
}


$mysqli->close();
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/settings.css" id="pagestyle">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php';?>
  <div id='content'>
    <p id="settings-title">My Settings</p>
    <div id="settings">
      <form method="POST" action="">
        <input id="btn" type="submit" name="cancelbtn" value="Cancel"/>
        <input id="btn" type="submit" name="savebtn" value="Save Changes"/>

        <h1>ACCOUNT</h1></br>
        <div id="content">
          <p>Username: </p>
          <input id="input" type"text" name="newUname" <?php echo "value=\"" . $uname . "\""; ?>/>
          <p><?php
          if (!empty($msg)) {
            foreach ($msg as $text) {
              echo "<font size=\"2px\" color=\"red\"><i>" . $text . "</font></i><br>";
            }
          }
          ?></p>
          <p>Change Password?</p>
        </div>

        <h1>PROFILE VISIBILITY</h1>
        <div id="content">
          <input type="radio" name="visibility" value="v" <?=($visibility) ? "checked" : "" ?> />
          <p>Visible (Everyone can see that you are the author of your posts)</p>
          <input type="radio" name="visibility" value="inv" <?= (!$visibility) ? "checked": "" ?>/>
          <p>Not Visible (The author of your posts is set to anonymous)</p>
        </div>

        <?php
        if ($admin): ?>

        <h1>ADMINISTRATION</h1>
        <div id="content">
          <p>Select Admins/Moderators from</p>
          <a href="userlist">List of Users</a>
        </div>

        <?php endif;?>

        <input id="btn" type="submit" name="cancelbtn" value="Cancel"/>
        <input id="btn" type="submit" name="savebtn" value="Save Changes"/>
      </form>
    </div>
  </div>
</div>

<script>applyStyle();</script>

</body>
</html>
