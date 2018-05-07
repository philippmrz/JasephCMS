<?php
require_once('require/backend.php');
require('require/credentials.php');

if (!$db->auth()) {
  header('location: index');
}

$userid = $db->getCurUser();

//check for admin
if ($db->getRole($userid) != 'ADMIN') {
  header('location: index');
}

if (isset($_POST["canclbtn"])) {
  header("location: settings");
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
  <link rel="stylesheet" href="style/userlist.css">
  <script src="script/userlist.js"></script>
</head>
<body>
  <div id='grid-wrap'>
    <?php require 'require/header.php';?>
    <?php require 'require/sidebar.php';?>
    <div id='content'>
      <h1 id="userlist-title">List of Users</h1>
      <div id='status-sheet'>
        <h1 id='status-title'>Status</h1>
        <div id='status-text'>
          <?php
          if (!empty($msg)) {
            foreach ($msg as $text) {
              echo $text."<br>";
            }
          }
          ?>
        </div>
      </div>
      <div id='userlist-sheet'>
      <form method="POST" action="">
        <?php
        $result= $db->query("SELECT USERID AS ID, USERNAME AS NAME, REGISTERED FROM $usertable");
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

                  <th class='userlist-role'>Common</th>
                  <th class='userlist-role'>Mod</th>
                  <th class='userlist-role'>Admin</th>
                  <th class='userlist-role'>Delete</th>

                  <th class='userlist-manage'>Manage</th>

                </tr>
                <?php
                }
                ?>
                <tr>
                <?php
                foreach ($row as $elem) {
                  echo"<td>".htmlspecialchars($elem)."</td>";
                }
                ?>

                <td class='userlist-role'><input type="radio" name="chkusers<?php echo $row["ID"]; ?>" value="common" <?php echo ($db->getRole($row['ID']) == 'COMMON') ? 'checked' : '';?>/></td>
                <td class='userlist-role'><input type="radio" name="chkusers<?php echo $row["ID"]; ?>" value="mod" <?php echo ($db->getRole($row['ID']) == 'MOD') ? 'checked' : '';?>/></td>
                <td class='userlist-role'><input type="radio" name="chkusers<?php echo $row["ID"]; ?>" value="admin" <?php echo ($db->getRole($row['ID']) == 'ADMIN') ? 'checked' : '';?>/></td>
                <td class='userlist-role'><input type="radio" name="chkusers<?php echo $row["ID"]; ?>" value="delete"/></td>

                <td class='userlist-manage'>
                  <select name='chkusers<?php echo $row["ID"]; ?>'>
                    <option value='common' <?php echo ($db->getRole($row['ID']) == 'COMMON') ? 'selected' : '';?>>Common</option>
                    <option value='mod' <?php echo ($db->getRole($row['ID']) == 'MOD') ? 'selected' : '';?>>Mod</option>
                    <option value='admin' <?php echo ($db->getRole($row['ID']) == 'ADMIN') ? 'selected' : '';?>>Admin</option>
                    <option value='delete'>Delete</option>
                  </select>
                </td>

              </tr>
              <?php
            }
          } else {
            echo "query error";
          }
          ?>
        </table>
        <div class='btn-wrapper'>
          <input class='secondary-btn' type="submit" name="confbtn" value="Confirm"/>
          <input class='secondary-btn' type="submit" name="canclbtn" value="Cancel"/>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  applyStyle();
  rezUserlist();
</script>
</body>
</html>
