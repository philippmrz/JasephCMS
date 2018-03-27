//Check if User is logged in, if not, kick back to index
<?php if (!isset($_COOKIE["logcheck"])):?>
  <h1>Get Out</h1>
  <script>redirect('index');</script>

<?php
    require 'require/credentials.php';

    $mysqli = new mysqli($servername, $username, $password, $dbname);

    if ($mysqli->connect_error) {
        die("Connecting to MySQL or database failed:<b><i> " . $mysqli->connect_error . "</b></i>");
    }
    $uname = $_COOKIE["uname"];

    $visibility = true; //evtl setting ob user anonym bleiben will
    $get_admin = $mysqli->query("SELECT ROLE FROM $usertable WHERE USERNAME = '$uname'");
    if (!$get_admin) {
        echo $mysqli->error;
    }
    $row_admin = $get_admin->fetch_assoc();
    if ($row_admin["ROLE"] == "admin") {
        $admin = true;
    } else {
        $admin = false;
    }

    if (isset($_POST["cancelbtn"])) {
        ?><script>redirect("index");</script><?php
    }

    if (isset($_POST["savebtn"])) {
        if (isset($_POST["new_uname"])) {
            $new_uname = $_POST["new_uname"];
            if ($new_uname != $uname) {

                $result = $mysqli->query("UPDATE $usertable SET USERNAME = '$new_uname' WHERE USERNAME = '$uname'");
                if ($result) {
                    $uname = $new_uname;
                    setcookie("uname", $uname, time() + 86400 * 365);
                }
            }
        }
        if (isset($_POST["v"])) {
            $visibility = true;
        } else if (isset($_POST["inv"])) {
        $visibility = false;
    }
    ?><script>redirect("settings.php");</script><?php
}

$mysqli->close();
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <link rel="stylesheet" href="style/saved.css" id="pagestyle">
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
          <input id="input" type"text" name="new_uname" <?php echo "value=\"" . $uname . "\""; ?>/>
          <p>Change Password?</p>
        </div>

        <h1>PROFILE VISIBILITY</h1>
        <div id="content">
          <input type="radio" name="visibility" value="v" <?=($visibility) ? "checked" : "" ?> />
          <p>Visible (Everyone can see that you are the author of your posts)</p>
          <input type="radio" name="visibility" value="inv" <?= (!$visibility) ? "checked": "" ?>/>
          <p>Not Visible (The author of your posts is set to anonymous)</p>
        </div>

        <?php //wenn user admin is kann er neue admins/moderators machen ($admin vll mit was anderem austauschen)
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