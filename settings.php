<?php
  require_once('require/backend.php');

  if (!isLoggedIn()):
  header("Location: index");
  endif;
  require 'require/credentials.php';

  $mysqli = new mysqli($servername, $username, $password, $dbname);

  if ($mysqli->connect_error) {
    die("Connecting to MySQL or database failed:<b><i> " . $mysqli->connect_error . "</b></i>");
  }

  $uname = $_COOKIE["uname"];

  $db = new DatabaseConnection();

  $userID = $db->getUserID();

  //get admin
  $getAdmin = $mysqli->query("SELECT ROLE FROM $usertable WHERE USERNAME = '$uname'");
  $rowAdmin = $getAdmin->fetch_assoc();
  $admin = $rowAdmin["ROLE"] == "ADMIN" ? true : false;

  $visibility = $db->getVisibility();

  if(isset($_POST["picSubmit"]) && isset($_FILES["picFile"])){
    if ($db->checkImg()) {
      $db->createImgPath();
    } else {
      $msg = $db->checkImg();
    }
  }

  //show image
  if (!is_null($db->getTempImgPath())) {
    $tempimg = $db->getTempImgPath();
  } else {
    $tempimg = $db->getImgPath($userID);
  }

  $displayimg = $tempimg;;

  if(isset($_POST["remove"])){
    $tempPath = $db->getTempImgPath();
    $path = $db->getImgPath($db->getUserID());
    if(!is_null($tempPath)){
      unlink($tempPath);
      $deletetempPath = $mysqli->query("UPDATE $imgtable SET TEMP_PATH = null WHERE USERID='$userID'");
    }
    if($path != "assets/default-avatar.png"){
      unlink($path);
      $deletePath = $mysqli->query("UPDATE $imgtable SET PATH = 'assets/default-avatar.png' WHERE USERID='$userID'");
    }
  }

  if (isset($_POST["cancelbtn"])) {
    if (!is_null($db->getTempImgPath())) {
      unlink($db->getTempImgPath());
      $deletetempPath = $mysqli->query("UPDATE $imgtable SET TEMP_PATH = null WHERE USERID='$userID'");
    }
    header("location: index");
  }

  if (isset($_POST["savebtn"])) {
    $msg = [];
    if (isset($_POST["newUname"])) {
      $newUname = $_POST["newUname"];
      if ($newUname != $uname) {
        $checkExist = $mysqli->query("SELECT USERNAME FROM $usertable WHERE UPPER(USERNAME) = UPPER('$newUname')");
        if($checkExist){
          if ($checkExist->num_rows > 0) {
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

    if (isset($_POST["visibility"])) {
      $visibility = true;
      $updateVis = $mysqli->query("UPDATE $usertable SET VISIBILITY = 'VISIBLE' WHERE USERID = '$userID'");
    } else {
      $visibility = false;
      $updateVis = $mysqli->query("UPDATE $usertable SET VISIBILITY = 'INVISIBLE' WHERE USERID = '$userID'");
    }

    if ($tempimg != "assets/default-avatar.png") {
      $tempPath = $db->getTempImgPath();
      $path = $db->getImgPath($db->getUserID());
      $newpath = DatabaseConnection::AVATAR_DIRECTORY . '/' . substr($tempPath, strlen(DatabaseConnection::TEMP_AVATAR_DIRECTORY));
      if (!is_null($tempPath)) {
        if (is_null($path)) {
          $savePath = $mysqli->query("UPDATE images SET PATH = '$newpath', TEMP_PATH = null WHERE USERID = '$userID'");
        } else {
          if ($path != "assets/default-avatar.png") {
            unlink($path);
            $savePath = $mysqli->query("UPDATE images SET PATH = '$newpath', TEMP_PATH = null WHERE USERID = '$userID'");
          } else {
            $savePath = $mysqli->query("UPDATE images SET PATH = '$newpath', TEMP_PATH = null WHERE USERID = '$userID'");
          }
        }
        rename($tempPath, $newpath);
      }
      $displayimg = $newpath;
    }
    sleep(1);
    header('location: index');
  }

$mysqli->close();
$db->close();
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/settings.css" id="pagestyle">
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
        <h1 id='settings-title2'>ACCOUNT</h1>
        <p>Username</p>
        <input id="settings-username" type="text" name="newUname" <?php echo "value=\"" . $uname . "\""; ?>/>
        <p>Avatar</p>
        <input type="hidden" value="1000000" name="FILE_SIZE_MAX">
        <input type="file" name="picFile" accept=".jpg, .jpeg, .png, .gif"><br>
        <input type="submit" name="picSubmit" value="Upload"><br>
        <input type="submit" name="remove" value="Remove"><br>

        <img id='settings-avatar' src=<?php echo "'$displayimg'";?> height="128" width="128"/>

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

        <h1 id='settings-title2'>ADMINISTRATION</h1>
        <p>Select Admins/Moderators from</p>
        <a href="userlist">List of Users</a><br>

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
