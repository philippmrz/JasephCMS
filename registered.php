<!doctype html>
<html>
<head>
  <?php require('require/head.php');?>
</head>
<body>
  <?php require('require/header.php');?>
  <div class="content">
    <?php
    $reguser = $_POST["username"];
    $userpass = $_POST["password"];
    $repeat   = $_POST["repeat"];
    $email    = $_POST["email"];

    // Credentials for this server
    require('require/credentials.php');

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
    $check = true;
    while($row = mysqli_fetch_assoc($result)) {
      foreach($row as $val) {
        if(strtolower($reguser) == strtolower($val)) {
          echo 'Username exists already.<br>';
          $check = false;
        }
      }
    }

    if($check) {
      $sql = "INSERT INTO user (USERNAME, PASSWORD, EMAIL) VALUES ('$reguser', '$userpass', '$email')";
      if(mysqli_query($link, $sql)) { // Runs mysql query
        echo "New record created successfully.<br>";
      } else {
        echo "Error: $sql<br>" . mysqli_error($link) . '<br>';
      }
    }

    mysqli_close($link); // Closes mysql connection

    } else {
      echo 'failed<br>';
    }
    ?>
  </div>
  <div id="footer">
    <a href="https://github.com/phmrz/JasephCMS" title="Check out the main branch of this page on GitHub!">Created as a school project by Jakob Mainka, Philipp Merz and Sebastian Scheinert</a>
  </div>
</body>
</html>
