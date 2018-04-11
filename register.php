<?php
$db = new DatabaseConnection();
$db->register();
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
  <?php require 'require/sidebar.php';?>
  <div id='content'>

    <form id='auth' method="POST" action="">
    <p id="title">Register</p>

      <input id="username" type="text" name="uname" placeholder="Username"/>
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
