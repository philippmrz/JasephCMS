<?php
// Credentials for this server
require 'require/credentials.php';

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connecting to MySQL or database failed:<b><i> ". $mysqli->connect_error . "</b></i>");
}

$uname = $_COOKIE["uname"];

//check for admin/mod
$getAdmin = $mysqli->query("SELECT ROLE FROM $usertable WHERE USERNAME = '$uname'");
if (!$getAdmin) {
    echo $mysqli->error;
}
$rowAdmin = $getAdmin->fetch_assoc();
if ($rowAdmin["ROLE"] != "ADMIN" || !isset($_COOKIE["logcheck"])) {
  ?>
  <div class="content">
    <h1>Get Out</h1>
    <script>redirect('index');</script>
  </div>
  <?php
}
else{
    $admin = true;
}

if (isset($_POST["canclbtn"])) {
    ?><script>redirect("settings.php")</script><?php
}
if (isset($_POST["confbtn"])) {
  $msg = [];
  $userlist = $mysqli->query("SELECT USERNAME FROM $usertable");
  while ($row = $userlist->fetch_assoc()) {
    //check every user
    foreach ($row as $user) {
      $getUserID = $mysqli->query("SELECT USERID FROM $usertable WHERE USERNAME = '$user'");
      $idRow = $getUserID->fetch_assoc();
      $id = $idRow["USERID"];
      if (isset($_POST["chkusers$id"])) {
        switch ($_POST["chkusers$id"]) {
          case "admin":
            $newRole = "ADMIN";
            break;

          case "mod":
            $newRole = "MOD";
            break;

          case "block":
            $newRole = "BLOCKED";
            break;

          case "delete":
            $delete = $mysqli->query("DELETE FROM $usertable WHERE USERID = $id");
            if ($delete) {
              array_push($msg, "Successfully deleted User no.$id");
            } else {
              array_push($msg, "Couldn't carry out changes (db query failed)");
            }
            break;
        }
        //change table, get re:message
        if (isset($new_role)) {
          $role = $mysqli->query("UPDATE $usertable SET ROLE = '$new_role' WHERE USERID = $id");
          $get_uname = $mysqli->query("SELECT USERNAME FROM $usertable WHERE USERID = $id");
          $urow = $get_uname->fetch_assoc();
          $uname = $urow["USERNAME"];
          if ($role && $get_uname) {
            array_push($msg, "Changed Role of $uname to $newRole");
          } else {
            array_push($msg, "Couldn't carry out changes (db query failed)");
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
</head>

<body>
  <div id='grid-wrap'>
    <?php require 'require/header.php';?>
    <?php require 'require/sidebar.php';?>
    <div id='content'>
      <script>applyStyle();</script>
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
              <table border=3>
                <tr>
                  <?php
                  foreach ($heads as $elem) {
                    echo "<th>".$elem."</th>";
                  }
                  ?>
                  <th>Delete</th><th>Block</th><th>Mod</th><th>Admin</th>
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
                <td><input type="radio" name="chkusers<?php echo $row["USERID"]; ?>" value="block"/></td>
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
        <br>
        <input type="submit" name="confbtn" value="Confirm"/>
        <br>
        <input type="submit" name="canclbtn" value="Cancel"/>
      </form>
    </div>
</div>
<script>applyStyle();</script>
</body>
</html>
