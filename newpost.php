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
      <div id="titlecharswrapper">0/200</div>
      <input id="titleField" name="title" type="text" placeholder="Post title" maxlength="200" oninput="updateCharsLeft(200, 'title')"/><br><br>
      <div id="contentcharswrapper">0/10000</div>
      <textarea id="contentArea" name="content" placeholder="Post content" rows="20" cols="100" spellcheck="false" maxlength="10000" oninput="updateCharsLeft(10000, 'content')"></textarea><br>
      <div id="btnpostwrapper">
        <button type="submit" id="btnpost" class="btn">Post</button>
      </div>
    </form>
  </div>
  <?php require 'require/footer.php';?>
</body>
</html>
