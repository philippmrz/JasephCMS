<!doctype html>
<html>
<head>
  <link rel="icon" href="../icon_0.png"/>
  <link rel="stylesheet" href="style/normal.css" id="pagestyle"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans"/>
  <script src="script/script.js"></script>
  <title>jaseph</title>
</head>
<body>
  <div id="header">
    <a href="index.php">jaseph</a>
  </div>
  <div id="content">
    <?php
    // Credentials for this server
    $servername = 'localhost';
    $username   = 'root';
    $password   = '';
    $dbname     = 'jaseph';
    $usertable  = 'user';
    $posttable  = 'post';

    print_r($_POST); // Debug, prints out contents of form

    if(!isset($_POST["title"]) || empty($_POST["title"]) || ctype_space($_POST["title"])) { //Check if the title is not set/empty/only spaces. Exit if true.
      echo 'Title must not be empty!';
      goto exit_;//return;
    }

    if (!$link = mysqli_connect($servername, $username, $password)) { // Connects to the mysql using above credentials
      echo 'Could not connect to mysql server';
      goto exit_;//return;
    }

    if (!mysqli_select_db($link, $dbname)) { // Selects the $dbname database (in this case jaseph)
      echo 'Could not select mysql database.';
      goto exit_;//return;
    }

    $userid  = 1; // Temporary userid for sprint
    $title   = $_POST["title"];
    $content = $_POST["content"];

    $sql = "INSERT INTO $posttable (userid, title, content) VALUES ('$userid', '$title', '$content')"; // Inserts data into the 'post' database

    if (mysqli_query($link, $sql)) { // Runs mysql query
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($link);
    }

    mysqli_close($link); // Closes mysql connection
    exit_: ; // Workaround, if 'exit;' was used, it would ignore further actions, such as the following html block.
    ?>
    <br>
    <button id="swapper" onclick="swapStyle()">Hacker Mode</button>
  </div>
  <div id="footer">
    Created as a school project by Jakob Mainka, Philipp Merz and Sebastian Scheinert
  </div>
</body>
</html>
