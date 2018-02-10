<!doctype html>
<html>
<head>
  <?php require('require/head.php');?>
</head>
<body>
  <?php require('require/header.php');?>
  <div class="content">
    <form action="registered" method="POST">
      <input name="username" type="text" placeholder="Username"/><br>
      <input name="password" type="password" placeholder="Password"/><br>
      <input name="repeat" type="password" placeholder="Repeat Password"/><br>
      <input name="email" type="text" placeholder="E-Mail (Optional)"/><br>
      <button type="submit">Register</button><br>
    </form>
  </div>
  <div id="footer">
    <a href="https://github.com/phmrz/JasephCMS" title="Check out the main branch of this page on GitHub!">Created as a school project by Jakob Mainka, Philipp Merz and Sebastian Scheinert</a>
  </div>
</body>
</html>
