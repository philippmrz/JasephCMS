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
    <?php if (isset($_GET['id'])) {
      $db = new DatabaseConnection();
      $db->addToSavedPosts();
    }

    echo $db->postsAusgeben('DESC'); ?>
  </div>
</div>

<script>applyStyle();</script>
<script>updateMD();</script>
</body>
</html>
