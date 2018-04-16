<?php
require_once('require/backend.php');

$db = new DatabaseConnection();
if (!$db->auth()) {
  header("Location: index");
}
require 'require/credentials.php';

$db = new DatabaseConnection();

$userid = $db->getUserID($_COOKIE['identifier']);

//get admin
$admin = ($db->getRole($userid) == 'ADMIN') ? true : false;

$visibility = $db->getVisibility();

if(isset($_POST["picSubmit"]) && isset($_FILES["picFile"])){
  if (empty($db->checkImg())) {
    $db->createImgPath();
  } else {
    $msg = $db->checkImg();
  }
}

if(isset($_POST["remove"])){
  $tempPath = $db->getTempImgPath();
  $path = $db->getImgPath($db->getUserID($_COOKIE['identifier']));
  if(!is_null($tempPath)){
    unlink($tempPath);
    $deletetempPath = $db->query("UPDATE $imgtable SET TEMP_PATH = null WHERE USERID='$userid'");
  }
  if($path != "assets/default-avatar.png"){
    unlink($path);
    $deletePath = $db->query("UPDATE $imgtable SET PATH = 'assets/default-avatar.png' WHERE USERID='$userid'");
  }
}

//show image
if (!is_null($db->getTempImgPath())) {
  $tempimg = $db->getTempImgPath();
} else {
  $tempimg = $db->getImgPath($userid);
}
$displayimg = $tempimg;;

$pwordmsg = [];
session_start();

if (isset($_POST['oldPword']) && isset($_POST['newPword']) && isset($_POST['pChangeCommit'])) {
  $oldPword = $_POST['oldPword'];
  $newPword = $_POST['newPword'];
  if ($getInfo = $db->query("SELECT PASSWORD, USERID FROM $usertable WHERE USERID='$userid'")) {
    $row = $getInfo->fetch_assoc();
    $dbPword = $row['PASSWORD'];
    if (!password_verify($oldPword, $dbPword)) {
      array_push($pwordmsg,"Old Password is incorrect.");
    } elseif (!empty($db->pwordRequirements($newPword))) {//invalid pword
      $pwordmsg = $db->pwordRequirements($newPword);
    } else {
      $hashed_password = password_hash($newPword, PASSWORD_DEFAULT);
      if ($r = $db->query("UPDATE $usertable SET PASSWORD = '$hashed_password' WHERE USERID ='$userid'")) {
        setcookie('hashed_password', $hashed_password);
        $_SESSION['pwordsuccess'] = 1;
        header('Location: settings');
        exit();
      }
    }
  }
}

if (isset($_SESSION['pwordsuccess']) and $_SESSION['pwordsuccess'] == 1) {
  array_push($pwordmsg, 'Password successfully changed.');
  unset($_SESSION['pwordsuccess']);
}


if (isset($_POST["cancelbtn"])) {
  if (!is_null($db->getTempImgPath())) {
    unlink($db->getTempImgPath());
    $deletetempPath = $db->query("UPDATE $imgtable SET TEMP_PATH = null WHERE USERID='$userid'");
  }
  header("location: index");
}

if (isset($_POST["savebtn"])) {
  $msg = [];
  if (isset($_POST["newUname"])) {
    $newUname = $_POST["newUname"];
    if ($newUname != $db->getUsername($userid)) {
      $checkExist = $db->query("SELECT USERNAME FROM $usertable WHERE UPPER(USERNAME) = UPPER('$newUname')");
      if($checkExist){
        if ($checkExist->num_rows > 0) {
          array_push($msg, "Username already exists");
        }
      }
      if(empty($msg)){
        $result = $db->query("UPDATE $usertable SET USERNAME = '$newUname' WHERE USERID = $userid");
      }
    }
  }

  if (isset($_POST["visibility"])) {
    $visibility = true;
    $updateVis = $db->query("UPDATE $usertable SET VISIBILITY = 'VISIBLE' WHERE USERID = '$userid'");
  } else {
    $visibility = false;
    $updateVis = $db->query("UPDATE $usertable SET VISIBILITY = 'INVISIBLE' WHERE USERID = '$userid'");
  }

  if ($tempimg != "assets/default-avatar.png") {
    $tempPath = $db->getTempImgPath();
    $path = $db->getImgPath($db->getUserID($_COOKIE['identifier']));
    $newpath = DatabaseConnection::AVATAR_DIRECTORY . '/' . substr($tempPath, strlen(DatabaseConnection::TEMP_AVATAR_DIRECTORY));
    if (!is_null($tempPath)) {
      if (is_null($path)) {
        $savePath = $db->query("UPDATE images SET PATH = '$newpath', TEMP_PATH = null WHERE USERID = '$userid'");
      } else {
        if ($path != "assets/default-avatar.png") {
          unlink($path);
          $savePath = $db->query("UPDATE images SET PATH = '$newpath', TEMP_PATH = null WHERE USERID = '$userid'");
        } else {
          $savePath = $db->query("UPDATE images SET PATH = '$newpath', TEMP_PATH = null WHERE USERID = '$userid'");
        }
      }
      rename($tempPath, $newpath);
    }
    $displayimg = $newpath;
  }
  sleep(1);
  header('location: index');
}

$db->close();
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/settings.css">
  <script src="script/settings.js"></script>
</head>
<body>
<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php';?>
  <div id='content'>
    <div id="settings-sheet">
      <h1 id="settings-title">My Settings</h1>
      <form method="POST" action="" enctype="multipart/form-data">
        <div class='btn-wrapper'>
          <input class="secondary-btn" type="submit" name="cancelbtn" value="Cancel"/>
          <input class="secondary-btn" type="submit" name="savebtn" value="Save Changes"/>
        </div>
        <hr>
        <h1 id='settings-title2'>ACCOUNT</h1>
        <p>Username</p>
        <input id="settings-username" type="text" name="newUname" <?php echo "value=\"" . $db->getUsername($userid) . "\""; ?>/>
        <p>Change Password</p>
        <input class="password" type="password" name="oldPword" placeholder="Old Password"/><br>
        <input class="password" type="password" name="newPword" placeholder="New Password"/><br>
        <input class='secondary-btn' type="submit" name="pChangeCommit" value="Commit"><br>
        <p>
          <?php
          if (!empty($pwordmsg)) {
            foreach ($pwordmsg as $text) {
              echo "<font size=\"2px\" color=\"red\"><i>" . $text . "</font></i><br>";
            }
          }
          ?>
        </p>
        <p>Avatar</p>
        <input type="hidden" value="1000000" name="FILE_SIZE_MAX">
        <input type="file" name="picFile" accept=".jpg, .jpeg, .png, .gif"><br>
        <input class='secondary-btn' type="submit" name="picSubmit" value="Upload"><br>
        <input class='secondary-btn' type="submit" name="remove" value="Remove"><br>

        <img id='settings-avatar' src=<?php echo "'$displayimg'";?> height="128" width="128"/>
        <hr>
        <h1 id='settings-title2'>PROFILE VISIBILITY</h1>
        <div id='settings-visibility'>
          <?php
          $checked = ($visibility) ? "checked" : "";
          ?>
          <div class='switch'>
            <?php echo "<input id='settings-visibility-switch' class='switch-checkbox' type='checkbox' name='visibility' onchange='vis();' $checked>";?>
            <label class='switch-label' for='settings-visibility-switch'></label>
          </div>
          <p id='settings-visibility-info'></p>
        </div>
        <?php
        if ($admin): ?>
        <hr>
        <h1 id='settings-title2'>ADMINISTRATION</h1>
        <a href='userlist'>List of Users</a>

        <?php endif;?>
        <p>
          <?php
          if (!empty($msg)) {
            foreach ($msg as $text) {
              echo "<font size=\"2px\" color=\"red\"><i>" . $text . "</font></i><br>";
            }
          }
          ?>
        </p>
        <hr>
        <div class='btn-wrapper'>
          <input class="secondary-btn" type="submit" name="cancelbtn" value="Cancel"/>
          <input class="secondary-btn" type="submit" name="savebtn" value="Save Changes"/>
        </div>
      </form>
    </div>
  </div>
</div>

<script>applyStyle();</script>
<script>vis();</script>
</body>
</html>
