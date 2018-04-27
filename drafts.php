<?php
require_once('require/backend.php');
require('require/credentials.php');
$db = new DatabaseConnection();

if (!$db->auth()) {
  header('Location: index');
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
    <?php
    $userid = $db->getCurUser();
    if ($getDrafts = $db->query("SELECT DRAFTID FROM $drafttable WHERE USERID = $userid")) {
      if ($getDrafts->num_rows > 0) {
        echo $db->draftsAusgeben();
      } else {
        echo 'You haven\'t saved any drafts yet';
      }
    } else {
      echo 'query error';
    }
    ?>
  </div>
</div>

<script>
  applyStyle();
  updateMD();
</script>
</body>
</html>
