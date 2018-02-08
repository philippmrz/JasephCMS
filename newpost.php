<!doctype html>
<html>
<head>
  <?php require('head.php');?>
</head>
<body>
  <div id="header">
    <a href="index.php" title="jaseph.com"><img id="headerimg" src="assets/jaseph_black.png"/></a>
  </div>
  <div class="content">
    <form action="post.php" method="POST">
      <input id="titleField" name="title" type="text" placeholder="Give your post a title!"/><br><br>
      <textarea id="contentArea" name="content" placeholder="Write something here!" rows="20" cols="100" spellcheck="false"></textarea><br>
      <button type="submit">Post</button>
    </form>
    <button id="swapper" onclick="swapStyle()">Hacker Mode</button>
  </div>
  <div id="footer">
    <a href="https://github.com/phmrz/JasephCMS" title="Check out the main branch of this page on GitHub!">Created as a school project by Jakob Mainka, Philipp Merz and Sebastian Scheinert</a>
  </div>
</body>
</html>
