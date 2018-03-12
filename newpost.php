<!doctype html>
<html>
<head>
  <?php require('require/head.php');?>
</head>
<body>
  <?php require('require/header.php');?>
  <script>applyStyle();</script>
  <div class="content">
    <h1>New Post</h1>
    <form action="post" method="POST">
      <input id="titleField" name="title" type="text" placeholder="Post title"/><br><br>
      <textarea id="contentArea" name="content" placeholder="Post content" rows="20" cols="100" spellcheck="false"></textarea><br>
      <button type="submit" id="register" class="btn">Post</button>
    </form>
  </div>
  <?php require 'require/footer.php';?>
</body>
</html>
