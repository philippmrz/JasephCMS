<?php
// Credentials for this server
require 'require/credentials.php';

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
  die("Connecting to MySQL or database failed:<b><i> ". $mysqli->connect_error . "</b></i>");
}

$uname = $_COOKIE["uname"];

//check for admin
$getAdmin = $mysqli->query("SELECT ROLE FROM $usertable WHERE USERNAME = '$uname'");
if (!$getAdmin) {
  echo $mysqli->error;
}
$rowAdmin = $getAdmin->fetch_assoc();
if ($rowAdmin["ROLE"] != "ADMIN" || !isset($_COOKIE["logcheck"])) {
  ?>
  <div class="content">
    <h1>Get Out</h1>
    <?php header('location: index.php');?>
  </div>
  <?php
} else {
  $admin = true;
}

if (isset($_POST["canclbtn"])) {
  header("location: settings.php");
}

function checkRole($userid, $role) {
  $getRole = $mysqli->query("SELECT ROLE FROM $usertable WHERE USERID = '$userid'");
  $row = $getRole->fetch_assoc();
  return $row['ROLE'] == $role ? true : false;
}

function updateRole($userid, $role) {
  $getUserID = $mysqli->query("SELECT USERID FROM $usertable WHERE USERID = $_COOKIE['uname']");
  $row = $getUserID->fetch_assoc();
  if ($row['USERID'] != $userid) {
    $updateRole = $mysqli->query("UPDATE $usertable SET ROLE = '$role' WHERE USERID = '$userid'");
    return $updateRole;
  } else {
    return false; // can't change your own role
  }
}

function deleteUser($userid) {
  $getUserID = $mysqli->query("SELECT USERID FROM $usertable WHERE USERID = $_COOKIE['uname']");
  $row = $getUserID->fetch_assoc();
  if ($row['USERID'] != $userid) {
    $deleteUser = $mysqli->query("DELETE FROM $usertable WHERE USERID = $userid");
    return $deleteUser;
  }
  return false; // can't delete self
}

if (isset($_POST["confbtn"])) {
  $msg = [];
  $usernames = $mysqli->query("SELECT USERNAME FROM $usertable");
  while ($row = $usernames->fetch_assoc()) {
    //check every user
    foreach ($row as $uname) {
      $getUserID = $mysqli->query("SELECT USERID FROM $usertable WHERE USERNAME = '$uname'");
      $idRow = $getUserID->fetch_assoc();
      $userid = $idRow["USERID"];
      if (isset($_POST["chkusers$userid"])) {
        switch ($_POST["chkusers$userid"]) {
          case "admin":
          $newRole = "ADMIN";
          break;

          case "mod":
          $newRole = "MOD";
          break;

          case "common":
          $newRole = "COMMON";
          break;

          case "delete":
          deleteUser($userid);
          break;
        }
        if (!checkRole($userid,$newRole)) {
          updateRole($userid,$newRole);
        } else {
          array_push($msg)
        }
      }
    }
  }
}

?>

<!doctype html>
<html>

<head>
  <?php require 'require/head.php';?>
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/userlist.css" id="pagestyle">
</head>

<body>
  <div id='grid-wrap'>
    <?php require 'require/header.php';?>
    <?php require 'require/sidebar.php';?>
    <div id='content'>
      <div id='userlist-sheet'>
        <h1 id="userlist-title">List of Users</h1>
      <?php
      if (!empty($msg)) {
        echo "Status: <br>";
        echo "-------------<br>";
        foreach ($msg as $text) {
          echo $text."<br>";
        }
        echo "-------------<br>";
      }
      ?>
      <form method="POST" action="">
        <?php
        $result= $mysqli->query("SELECT USERID, USERNAME, REGISTERED, ROLE FROM $usertable");
        $fl = true;
        if ($result) {
          while ($row = $result->fetch_assoc()) {
            if ($fl) {
              $fl = false;
              $heads = array_keys($row);
              ?>
              <table>
                <tr>
                  <?php
                  foreach ($heads as $elem) {
                    echo "<th>".$elem."</th>";
                  }
                  ?>
                  <th>Delete</th><th>Common</th><th>Mod</th><th>Admin</th>
                </tr>
                <?php
                }
                ?>
                <tr>
                <?php
                foreach ($row as $elem) {
                  echo"<td>".$elem."</td>";
                }
                ?>
                <td><input type="radio" name="chkusers<?php echo $row["USERID"]; ?>" value="delete"/></td>
                <td><input type="radio" name="chkusers<?php echo $row["USERID"]; ?>" value="common"/></td>
                <td><input type="radio" name="chkusers<?php echo $row["USERID"]; ?>" value="mod"/></td>
                <td><input type="radio" name="chkusers<?php echo $row["USERID"]; ?>" value="admin"/></td>
              </tr>
              <?php
            }
          } else {
            echo "query error";
          }
          ?>
        </table>
        <input class='secondary-btn' type="submit" name="confbtn" value="Confirm"/>
        <input class='secondary-btn' type="submit" name="canclbtn" value="Cancel"/>
      </form>
    </div>
  </div>
</div>
<script>applyStyle();</script>
</body>
</html>
