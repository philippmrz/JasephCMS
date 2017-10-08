<!DOCTYPE html>
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

  <style>
    #title{
      margin-bottom: .6em;
    }

    @media (min-device-width: 800px) {
      textarea, #title{
        width: 70% !important;
        margin: 0 auto;
        margin-bottom: .5em
      }
    }
  </style>

</head>
<body>
<header id="luxbar" class="luxbar-static">
  <input type="checkbox" class="luxbar-checkbox" id="luxbar-checkbox"/>
  <div class="luxbar-menu luxbar-menu-right luxbar-menu-dark">
    <ul class="luxbar-navigation">
      <li class="luxbar-header">
        <a href="../../index.html" class="luxbar-brand">PHP playground</a>
          <label class="luxbar-hamburger luxbar-hamburger-spin"
          id="luxbar-hamburger" for="luxbar-checkbox"> <span></span> </label>
      </li>
      <li class="luxbar-item"><a href="../form/client.html" id="">Multiplication</a></li>
      <li class="luxbar-item"><a href="../table/client.html" id="">Table</a></li>
      <li class="luxbar-item"><a href="../sort-array/client.html" id="">Sort</a></li>
      <li class="luxbar-item"><a href="../fizzbuzz/modulo.php" id="">FizzBuzz</a></li>
    </ul>
  </div>
</header>
<div class="container-fluid">
  <h1>Input for database</h1>
  <p class="description-p">
    Here you can insert new information into your database.
  </p>

<form action="insert.php" method="post">
    <br>
    <br>
    <input class="form-control" id="title" type="text" name="title" placeholder="Cool title">
    <textarea class="form-control" type="text" name="content" rows="8" placeholder="Ridiculously awesome story with many words."></textarea>
    <br>
    <input type="submit" class="btn btn-primary">
  </form>
</div>
<footer class='footer navbar-fixed-bottom'>
  2017@Philipp Merz
</footer>

</body>
</html>
