<!doctype html>
<html>
<head>
<head>
  <?php require('head.php');?>
</head>
<body>
  <div id="header">
    <a href="index.php" title="jaseph.com"><img id="headerimg" src="assets/jaseph_black.png"/></a>
  </div>
  <div class="content">
    <?php
    // Credentials for this server
    require('credentials.php');

    $success = true;

    print_r($_POST); // Debug, prints out contents of form
    echo '<br>';
    if(!isset($_POST["title"]) || empty($_POST["title"]) || ctype_space($_POST["title"])) { //Check if the title is not set/empty/only spaces. Exit if true.
      echo 'Title must not be empty!<br>';
      $success = false;
    }

    if(!$link = mysqli_connect($servername, $username, $password)) { // Connects to the mysql using above credentials
      echo 'Could not connect to mysql server.<br>';
      $success = false;
    }

    if(!mysqli_select_db($link, $dbname)) { // Selects the $dbname database (in this case jaseph)
      echo 'Could not select mysql database.<br>';
      $success = false;
    }

    if($success) {

    $userid  = 1; // Temporary userid for sprint
    $title   = mysqli_real_escape_string($link,$_POST["title"]);
    $content = mysqli_real_escape_string($link,$_POST["content"]);

    $sql = "INSERT INTO $posttable (userid, title, content) VALUES ('$userid', '$title', '$content')"; // Inserts data into the 'post' database

    if(mysqli_query($link, $sql)) { // Runs mysql query
        echo "New record created successfully.<br>";
    } else {
        echo "Error: $sql<br>" . mysqli_error($link) . '<br>';
    }

    mysqli_close($link); // Closes mysql connection

  } else {
    echo 'failed<br>';
  }
    ?>
    <br>
    <button id="swapper" onclick="swapStyle()">Hacker Mode</button>
  </div>
  <div id="footer">
    <a href="https://github.com/phmrz/JasephCMS" title="Check out the main branch of this page on GitHub!">Created as a school project by Jakob Mainka, Philipp Merz and Sebastian Scheinert</a>
  </div>
</body>
</html>
