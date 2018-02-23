<!doctype html>
<html>
<head>
  <?php require('require/head.php');?>
</head>
<body>
  <?php require('require/header.php');?>
  <script>applyStyle();</script>
  <div class="content">
    <form action="post" method="POST">
      <input id="titleField" name="title" type="text" placeholder="Give your post a title!"/><br><br>
      <textarea id="contentArea" name="content" placeholder="Write something here!" rows="20" cols="100" spellcheck="false"></textarea><br>
      <button type="submit">Post</button>
    </form>
  </div>
  <?php require 'require/footer.php';?>
</body>
</html>
