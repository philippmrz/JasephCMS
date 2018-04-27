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
      $db = new DatabaseConnection();
      echo $db->postsAusgeben(isset($_GET['sort']) ? $_GET['sort'] : 'DESC');

    if($db->auth()): ?>
      <a title='Sort chronologically or reverse chronologically' class='floating-action-btn' href='index.php?sort=<?= invertSortOrder()?>'>
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
