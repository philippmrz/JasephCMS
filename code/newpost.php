<!doctype html>
<html>
<head>
  <link rel="icon" href="../icon_0.png"/>
  <link rel="stylesheet" href="style/normal.css" id="pagestyle"/>
  <script src="script/script.js"></script>
  <title>jaseph</title>
</head>
<body>
  <div id="header">
    <a href="index.php">jaseph</a>
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
    Created as a school project by Jakob Mainka, Philipp Merz and Sebastian Scheinert
  </div>
</body>
</html>
