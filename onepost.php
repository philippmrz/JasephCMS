<?php
require_once 'require/DatabaseConnection.class.php';
$dbConnection = new DatabaseConnection();
if ($_GET['del'] == 1 && $dbConnection->getUserRole() == 'ADMIN') $dbConnection->deletePost(); ?>
<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <link rel="stylesheet" href="style/onepost.css" id="pagestyle">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php'; ?>
  <div id='content'>

  <?php if ($dbConnection->getUserRole() == 'ADMIN'): ?>
    <a href='onepost.php?del=1&id=<?=$_GET['id']?>'>
      <img src='assets/bin.svg'>
    </a>
  <?php endif; ?>

  <?= $dbConnection->einenPostAusgeben(); ?>
  </div>
</div>

<script>applyStyle();</script>

</body>
</html>