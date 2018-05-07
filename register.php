<?php
require_once('require/backend.php');
$db = new DatabaseConnection();
$msg = $db->register();
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <link rel="stylesheet" href="style/login.css">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php';?>
  <div id='content'>

    <form id='auth' method="POST" action="">
    <p id="title">Register</p>

      <input id="username" type="text" name="username" placeholder="Username" maxlength="20"/>
      <input class="password" type="password" name="password" placeholder="Password"/>
      <input class="password" type="password" name="passwordval" placeholder="Repeat Password"/>
      <input class='primary-btn' id="authbtn" type="submit" name="regbtn" value="Register"/>

      <?php
      if (!empty($msg)) {
        foreach ($msg as $text) {
          echo "<span class='info-msg'>$text</span>";
        }
      }
      ?>
    </form>
  </div>

</div>

<script>
  applyStyle();
</script>

</body>
</html>
