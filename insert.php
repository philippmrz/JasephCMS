<!doctype html>
<html lang='de'>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<title></title>
<link rel="shortcut icon" type="image/png" href="../favicon.ico"/>
<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.rawgit.com/balzss/luxbar/ae5835e2/build/luxbar.min.css">
<link rel='stylesheet' type='text/css' href='https://cdn.rawgit.com/phmrz/phmrz.github.io/master/standard.css'>
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>

</head>
<body>
<header id="luxbar" class="luxbar-static">
  <input type="checkbox" class="luxbar-checkbox" id="luxbar-checkbox"/>
  <div class="luxbar-menu luxbar-menu-right luxbar-menu-dark">
    <ul class="luxbar-navigation">
      <li class="luxbar-header">
        <a href="../index.html" class="luxbar-brand"></a>
          <label class="luxbar-hamburger luxbar-hamburger-spin"
          id="luxbar-hamburger" for="luxbar-checkbox"> <span></span> </label>
      </li>
      <li class="luxbar-item"><a href="" id=""></a></li>
    </ul>
  </div>
</header>
<div class="container-fluid">
  <h1>Thanks for submitting!</h1>
  <?php
    $success = False;
    $title = $_POST['title'];
    $content = $_POST['content'];

    require('db_password.php');
    $con = new mysqli($servername, $username, $password, $dbname);

    if ($con->connect_error){
      die('connection failed');
    }

    $query = "INSERT INTO feed (title, content) VALUES ('$title', '$content');";

    if ($con->query($query) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $query. "<br>" . $conn->error;
    }

    $con->close();
  ?>
  <p class="description-p">Your changes have
    <?php

  ?>
   been submitted.

  <br><a href="feed.php">Here</a> you can check out your changes.</p>
</div>
<footer class='footer navbar-fixed-bottom'>
  2017@Philipp Merz
</footer>

</body>
</html>
