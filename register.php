<?php
// Credentials for this server
require 'require/credentials.php';

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connecting to MySQL or database failed:<b><i> " . $mysqli->connect_error . "</b></i>");
}

if (isset($_POST["regbtn"])) {
    ?>
    <script>
        deleteCookie("identifier");
        deleteCookie("token");
    </script>
    <?php

    $uname = $_POST["uname"];
    $pword = $_POST["pword"];
    $pwordval = $_POST["pwordval"];
    $msg = [];

    //invalid inputs
    if (empty($uname)) {
        array_push($msg, "Please enter a username");
    }

    if (empty($pword)) {
        array_push($msg, "Please enter a password");
    }

    if (strlen($pword) < 6) {
        array_push($msg, "Password must be at minimum length of 6 letters");
    } else {
        if (ctype_upper($pword) || ctype_lower($pword)) {
            array_push($msg, "Password must contain at least one lowercase and one uppercase character");
        }
    }

    $result = $mysqli->query("SELECT * FROM $usertable WHERE USERNAME='$uname'");
    if ($result->num_rows > 0) {
        array_push($msg, "Username already exists");
    }

    if ($pword != $pwordval) {
        array_push($msg, "Passwords must match");
    }

    if (count($msg) == 0) {
        $pword = password_hash($pword, PASSWORD_DEFAULT);
        $result = $mysqli->query("INSERT INTO $usertable (USERNAME,PASSWORD) VALUES ('$uname','$pword')");
        if ($result) {
            ?>
            <script>
                setCookie("logcheck","true");
                setCookie("uname", "<?php echo $uname;?>");

                redirect("index");
            </script>
            <?php
            exit;
        } else {
            echo "Query error";
        }
    }
}

$mysqli->close();
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <link rel="stylesheet" href="style/login.css" id="pagestyle">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <div id='sidebar'></div>
  <div id='content'>

    <form id='auth' method="POST" action="">
    <p id="title">Register</p>

      <?php
      if (isset($uname) && !empty($uname)) {
          echo '<input id="username" type="text" name="uname" placeholder="Username" value="' . $uname .'"/>';
      } else {
          echo '<input id="username" type="text" name="uname" placeholder="Username"/>';
      }
      ?>

      <input class="password" type="password" name="pword" placeholder="Password"/>
      <input class="password" type="password" name="pwordval" placeholder="Repeat Password"/>
      <input class='primary-btn' id="authbtn" type="submit" name="regbtn" value="Register"/>
    </form>
    <?php
    if (!empty($msg)) {
      foreach ($msg as $text) {
        echo "<font size=\"2px\" color=\"red\"><i>" . $text . "</font></i><br>";
      }
    }
    ?>
  </div>

</div>

<script>applyStyle();</script>

</body>
</html>
