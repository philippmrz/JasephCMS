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
    <?php
    $reguser = $_POST["username"];
    $userpass = $_POST["password"];
    $repeat   = $_POST["repeat"];
    $email    = $_POST["email"];

    // Credentials for this server
    require('credentials.php');

    $success = true;

    if(!$link = mysqli_connect($servername, $username, $password)) { // Connects to the mysql using above credentials
      echo 'Could not connect to mysql server<br>';
      $success = false;
    }

    if(!mysqli_select_db($link, $dbname)) { // Selects the $dbname database (in this case jaseph)
      echo 'Could not select mysql database.<br>';
      $success = false;
    }

    if($userpass != $repeat) {
      echo "Passwords don't match.<br>";
      $success = false;
    }

    if($success) {

    $sql = "SELECT USERNAME FROM user";//$sql = "SELECT * FROM $posttable, $usertable";

    if($result = mysqli_query($link, $sql)) { // Runs mysql query
        echo "Successfully ran mysql query.<br>";
    } else {
        echo "Error: $sql<br>" . mysqli_error($link) . '<br>';
    }

    echo '<br>';
    while($row = mysqli_fetch_assoc($result)) {
      foreach($row as $val) {
        if(strtolower($reguser) == strtolower($val)) {
          echo 'Username exists already.';
        } else {
          $sql = "INSERT INTO user (USERNAME, PASSWORD, EMAIL) VALUES ('$reguser', '$userpass', '$email')";
          if(mysqli_query($link, $sql)) { // Runs mysql query
            echo "New record created successfully.<br>";
          } else {
            echo "Error: $sql<br>" . mysqli_error($link) . '<br>';
          }
        }
      }
    }

    mysqli_close($link); // Closes mysql connection

    } else {
      echo 'failed<br>';
    }
    ?>
    <button id="swapper" onclick="swapStyle()">Hacker Mode</button>
  </div>
  <div id="footer">
    Created as a school project by Jakob Mainka, Philipp Merz and Sebastian Scheinert
  </div>
</body>
</html>
