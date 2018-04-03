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
    <?php
      $dbConnection = new DatabaseConnection();
      echo $dbConnection->einenPostAusgeben();
    ?>
  </div>
</div>

<script>applyStyle();</script>

</body>
</html>