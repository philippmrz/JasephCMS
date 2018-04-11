<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <link rel="stylesheet" href="style/index.css" id="pagestyle">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php'; ?>
  <div id='content'>
    <?php
      $db = new DatabaseConnection();
      echo $db->postsAusgeben($_GET['sort']);

    if(isLoggedIn()): ?>
      <a title='Sort chronologically or reverse chronologically' class='floating-action-btn' href='index.php?sort=<?= invertSortOrder()?>'>
        <?= getSortSVG() ?>
      </a>
    <?php endif; ?>
  </div>
</div>

<script>applyStyle();</script>

</body>
</html>
