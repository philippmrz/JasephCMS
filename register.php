<!doctype html>
<html>
<head>
  <link rel="icon" href="../assets/icon_0.png"/>
  <link rel="stylesheet" href="style/normal.css" id="pagestyle"/>
  <script src="script/script.js"></script>
  <title>jaseph</title>
</head>
<body>
  <div id="header">
    <a href="index.php">jaseph</a>
  </div>
  <div class="content">
    <form action="registered.php" method="POST">
      <input name="username" type="text" placeholder="Username"/><br>
      <input name="password" type="password" placeholder="Password"/><br>
      <input name="repeat" type="password" placeholder="Repeat Password"/><br>
      <input name="email" type="text" placeholder="E-Mail (Optional)"/><br>
      <button type="submit">Register</button><br>
    </form>
    <button id="swapper" onclick="swapStyle()">Hacker Mode</button>
  </div>
  <div id="footer">
    Created as a school project by Jakob Mainka, Philipp Merz and Sebastian Scheinert
  </div>
</body>
</html>
