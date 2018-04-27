<?php require_once 'require/backend.php';

$db = new DatabaseConnection();

if(!isset($_GET['id'])) {
  header('Location: index');
}

if(!$db->auth()) {
  header('Location: ');
}

if (isset($_COOKIE['identifier'])) {
  $userid = $db->getCurUser();
}

if (isset($_GET['del'])) {
  if ($_GET['del'] == 1 && $db->getRole($userid) == 'ADMIN') {
    $db->deletePost();
  }
}

?>
<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/onepost.css">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php'; ?>
  <div id='content'>

    <?php
    if ($db->auth()) {
      if ($db->getSaved($_GET['id'])) {
        ?>
        <a id='remove-saved' class='floating-action-btn' href='saved.php?rem=1&id=<?= $_GET['id']?>' title='Remove this post from your saved posts'>
          <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('saved-check');?>'/></svg>
        </a>
        <?php
      } else {
        ?>
        <a class='floating-action-btn' href='saved.php?id=<?= $_GET['id']?>' title='Add this post to your saved posts'>
          <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('saved-add');?>'/></svg>
        </a>
        <?php
      }
      if ($db->getRole($userid) == 'ADMIN'){
        ?>
        <a id='delete-post' class='floating-action-btn' href='onepost.php?del=1&id=<?=$_GET['id']?>' title='Delete this post'>
          <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('delete');?>'/></svg>
        </a>
        <?php
      }
    }
    ?>
    <?= $db->einenPostAusgeben(); ?>
  </div>
</div>

<script>
  applyStyle();
  updateMD();
  x = document.getElementById('remove-saved');
  if (x) {
    x.addEventListener('mouseover', function() {
      document.querySelector('#remove-saved svg path').setAttribute('d', '<?= getSVG('saved-remove')?>');
    });
    x.addEventListener('mouseout', function() {
      document.querySelector('#remove-saved svg path').setAttribute('d', '<?= getSVG('saved-check')?>');
    });
  }
</script>
</body>
</html>
