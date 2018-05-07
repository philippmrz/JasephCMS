<?php require_once 'require/backend.php';
require 'require/credentials.php';
$db = new DatabaseConnection();

if(!$db->auth() or !isset($_GET['id'])) {
  header('Location: index');
} else {
  $GETid = $db->escape_string($_GET['id']);
}

$getOwner = $db->query("SELECT USERID FROM $drafttable WHERE DRAFTID = $GETid");
$owner = $getOwner->fetch_assoc()['USERID'];
if (($owner != $db->getCurUser()) and $db->getRole($db->getCurUser()) != 'ADMIN') {
  header('Location: index');
}

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

  <a id='delete-draft' class='floating-action-btn' href='onedraft?del=1&id=<?=$_GET['id']?>' title='Delete this draft'>
    <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('delete');?>'/></svg>
  </a>

  <a id='use-draft' class='floating-action-btn' href='newpost?draftid=<?= $_GET['id']?>' title='Transfer this draft to the new post page'>
    <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('newpost');?>'/></svg>
  </a>

  <?= $db->einenDraftAusgeben($GETid); ?>
  </div>
</div>

<script>
  applyStyle();
  updateMD();
</script>
</body>
</html>
