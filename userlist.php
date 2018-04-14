<?php
require_once('require/backend.php');
require('require/credentials.php');

$db = new DatabaseConnection();

//check for admin
if ($db->getUserRole() != 'ADMIN' || !isset($_COOKIE["logcheck"])) {
  ?>
  <div class="content">
    <h1>Get Out</h1>
    <?php header('location: index.php');?>
  </div>
  <?php
}

if (isset($_POST["canclbtn"])) {
  header("location: settings.php");
}

if (isset($_POST["confbtn"])) {
  $msg = [];
  $usernames = $db->query("SELECT USERNAME FROM $usertable");
  while ($row = $usernames->fetch_assoc()) {
    //check every user
    foreach ($row as $uname) {
      $getUserID = $db->query("SELECT USERID FROM $usertable WHERE USERNAME = '$uname'");
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
          if (!$db->checkSelf($userid)) {
            if ($db->deleteUser($userid)) {
              array_push($msg, 'Deleted user ' . $db->getUsername($userid) . '.');
            } else {
              array_push($msg, 'Could not delete user ' . $db->getUsername($userid) . '.');
            }
          } else {
            array_push($msg, 'You can\'t delete your own account.');
          }
          break;
        }
        if (isset($newRole)) {
          if (!$db->checkRole($userid, $newRole)) {
            if (!$db->checkSelf($userid)) {
              if ($db->updateRole($userid, $newRole)) {
                array_push($msg, 'Set ' . $db->getUsername($userid) . '\'s role to ' . $newRole . '.');
              } else {
                array_push($msg, 'Could not set ' . $db->getUsername($userid) . '\'s role to ' . $newRole . '.');
              }
            } else {
              array_push($msg, 'You can\'t change your own role.');
            }
          }
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
      <h1 id="userlist-title">List of Users</h1>
      <div id='status-sheet'>
        <h1 id='status-title'>Status</h1>
        <p>
          <?php
          if (!empty($msg)) {
            foreach ($msg as $text) {
              echo $text."<br>";
            }
          }
          ?>
        </p>
      </div>
      <div id='userlist-sheet'>
      <form method="POST" action="">
        <?php
        $result= $db->query("SELECT USERID, USERNAME, REGISTERED, ROLE FROM $usertable");
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
                <td><input type="radio" name="chkusers<?php echo $row["USERID"]; ?>" value="common" <?php echo ($db->getRole($row['USERID']) == 'COMMON') ? 'checked' : '';?>/></td>
                <td><input type="radio" name="chkusers<?php echo $row["USERID"]; ?>" value="mod" <?php echo ($db->getRole($row['USERID']) == 'MOD') ? 'checked' : '';?>/></td>
                <td><input type="radio" name="chkusers<?php echo $row["USERID"]; ?>" value="admin" <?php echo ($db->getRole($row['USERID']) == 'ADMIN') ? 'checked' : '';?>/></td>
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
