<?php
require_once('require/backend.php');
require('require/credentials.php');
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/users.css">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php'; ?>
  <div id='content'>
  </div>
</div>

<script>
  applyStyle();
</script>
</body>
</html>
