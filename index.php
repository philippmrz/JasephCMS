<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <link rel="stylesheet" href="style/index.css" id="pagestyle">
</head>
<body>

<?php if (isset($_COOKIE["logcheck"])): ?>
  <a href="newpost" title="Create a new post">
    <img id="floating-button-newpost" src="assets/newpost-button.png"/>
  </a>
<?php endif; ?>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <div id='sidebar'></div>
  <div id='content'>
    <?php

      $db = new DatabaseConnection();
      echo $db->postsAusgeben();
    ?>
  </div>
</div>

<script>applyStyle();</script>

</body>
</html>
