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

    //get admin (getUserRole funzt net warum auch immer)
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

    $userID = $db->getUserID();

    $vis = $db->getVisibility();
    if($vis = 'VISIBILE'){
      $visibility = true;
    }
    elseif($vis = 'INVISIBILE'){
      $visibility = false;
    }

    if(isset($_POST["picSubmit"]) && isset($_FILES["picFile"])){
      $imgPath = $db->createImgPath();
    }

    //show image
    if(!is_null($db->getTempPath())){
        $img = $db->getTempPath();
    }
    elseif(!is_null($db->getPath($db->getUserID()))){
      $img = $db->getPath($db->getUserID());
    }
    else{
      $img = "assets/avatar/default-avatar";
    }


    if (isset($_POST["cancelbtn"])) {
      if(!is_null($db->getTempPath())){
        unlink($db->getTempPath());
        $deletetempPath = $mysqli->query("UPDATE images SET TEMP_PATH = null");
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

      if ($_POST["visibility"] == "v") {
            $visibility = true;
            $updateVis = $mysqli->query("UPDATE $usertable SET VISIBILITY = 'VISIBLE' WHERE USERID = '$userID'");
        } else if ($_POST["visibility"] == "inv") {
        $visibility = false;
        $updateVis = $mysqli->query("UPDATE $usertable SET VISIBILITY = 'INVISIBLE' WHERE USERID = '$userID'");
      }

      if($img != "assets/default-avatar"){
        $tempPath = $db->getTempPath();
        $path = $db->getPath($db->getUserID());
        if(!is_null($tempPath)){
          if(is_null($path)){
            $savePath = $mysqli->query("UPDATE images SET PATH = '$tempPath', TEMP_PATH = null WHERE USERID = '$userID'");
          }
          else{
            unlink($path);
            $savePath = $mysqli->query("UPDATE images SET PATH = '$tempPath', TEMP_PATH = null WHERE USERID = '$userID'");
          }
        }
      }
  //  header("location: index");
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
      <form method="POST" action="" enctype="multipart/form-data">
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

            <input type="hidden" value="1000000" name="FILE_SIZE_MAX">
            <input type="file" name="picFile" accept=".jpg, .jpeg, .png">
            <input type="submit" name="picSubmit" value="Upload"><br>

            <img src="<?php echo $img;?>" height="256" width="256"/>

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
