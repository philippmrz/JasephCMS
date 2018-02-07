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
  <div class="continent">
    <form action="newpost.php"><button>New Post</button></form>
  </div>
  <div id="content">
    <?php
    /*
    This doesn't quite work yet. :thinking:
    */
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
    if($success) {

    $sql = "SELECT * FROM user, post";//$sql = "SELECT * FROM $posttable, $usertable";

    echo $sql . '<br>';

    if($result = mysqli_query($link, $sql)) { // Runs mysql query
        echo "Successfully ran mysql query.<br>";
    } else {
        echo "Error: $sql<br>" . mysqli_error($link) . '<br>';
    }

    print_r($result);
    echo '<br>';
    echo '<table>';
    while($row = mysqli_fetch_array($result)) {
      echo '<tr>';
      echo "do it";
      foreach($row as $elem) {
        echo "<td>$elem</td>";
      }
      echo '</tr>';
    }
    echo '</table>';

    mysqli_close($link); // Closes mysql connection

    } else {echo 'failed<br>';}
    ?>
    <button id="swapper" onclick="swapStyle()">Hacker Mode</button>
  </div>
  <div id="footer">
    Created as a school project by Jakob Mainka, Philipp Merz and Sebastian Scheinert
  </div>
</body>
</html>
