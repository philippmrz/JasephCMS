<?php
require_once('require/backend.php');
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
    <div id='imgchange' class='overlay'>
      <div class='popup'>
        <a class="close" href="#">&times;</a>
        <div class='popup-content'>
          Changed profile image.<br>
          You may need to force a refresh to make the change visible in your browser.<br>
          (Usually CTRL + F5)
        </div>
      </div>
    </div>
    <?php if (!$db->auth()): ?>
      <div id='jumbotron'>
        <p>Register to start blogging and to access more features.</p><br>
        <a id='jumbotron-btn' href='register' class='primary-btn'>register</a>
      </div>
    <?php endif;

    echo $db->postsAusgeben(isset($_GET['sort']) ? $_GET['sort'] : 'DESC');

    if($db->auth()): ?>
      <a title='Sort chronologically or reverse chronologically' class='floating-action-btn' href='index?sort=<?= invertSortOrder()?>'>
        <?= getSortSVG() ?>
      </a>
    <?php endif; ?>
  </div>
</div>

<script>
  applyStyle();
  updateMD();
</script>
</body>
</html>
