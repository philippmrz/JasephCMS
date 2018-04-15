<?php
require_once('require/backend.php');
$db = new DatabaseConnection();

if (isset($_GET['id'])) {
  $db->addToSavedPosts($_GET['id']);
}
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/index.css">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php'; ?>
  <div id='content'>
    <?php echo $db->postsAusgeben('DESC'); ?>
  </div>
</div>

<script>applyStyle();</script>
<script>updateMD();</script>
</body>
</html>
