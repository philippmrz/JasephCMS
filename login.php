<?php
require_once('require/backend.php');
$db = new DatabaseConnection();
$msg = $db->login();
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/login.css">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php';?>
  <div id='content'>

    <form id='auth' method="POST" action="">

      <p id="title">Login</p>

      <input id="username" type="text" name="uname" placeholder="Username" <?= (isset($uname) and !empty($uname)) ? 'value=' . $uname : ''?> autofocus/><br>
      <input class="password" type="password" name="password" placeholder="Password"/><br>

      <input class='primary-btn' id="authbtn" type="submit" name="logbtn" value="Sign In"/><br>

      <?php
      if (!empty($msg)) {
        foreach ($msg as $text) {
          echo "<font size=\"2px\" color=\"red\"><i>" . $text . "</font></i><br>";
        }
      }
      ?>

      <p id="sub">No Account yet? Click <a href="register.php">here</a> to register</p>

    </form>
  </div>
</div>

<script>
  applyStyle();
</script>

</body>
</html>
