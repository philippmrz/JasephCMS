<?php
require_once('require/backend.php');
$db = new DatabaseConnection();
$db->login();
?>
<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/login.css" id="pagestyle">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php';?>
  <div id='content'>

    <form id='auth' method="POST" action="">

      <p id="title">Login</p>

      <?php
      if (isset($uname) && !empty($uname)) {
        echo '<input id="username" type="text" name="uname" placeholder="Username" value="' . $uname . '"/><br>';
      } else {
        echo '<input id="username" type="text" name="uname" placeholder="Username"/><br>';
      }
      ?>
      <input class="password" type="password" name="pword" placeholder="Password"/><br>

      <input class='primary-btn' id="authbtn" type="submit" name="logbtn" value="Sign In"/><br>

      <div id='remember-me'>
        <p>Remember me</p>
        <input type="checkbox" name="stay_li"/><br>
      </div>

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

<script>applyStyle();</script>

</body>
</html>
