<?php require_once 'require/backend.php';

$db = new DatabaseConnection();
if(!$db->auth()) {
  header('Location: index');
}

$userid = $db->getUserID($_COOKIE['identifier']);


if (isset($_GET['del'])) {
  if ($_GET['del'] == 1) {
    $db->deleteDraft();
  }
}

?>
<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <link rel="stylesheet" href="style/onepost.css">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php'; ?>
  <div id='content'>

  <?php if (isset($userid)):?>
    <a id='delete-draft' class='floating-action-btn' href='onedraft.php?del=1&id=<?=$_GET['id']?>'>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('delete');?>'/></svg>
    </a>
  <?php endif;?>
  <?= $db->einenDraftAusgeben(); ?>
  </div>
</div>

<script>applyStyle();</script>
<script>updateMD();</script>
</body>
</html>
