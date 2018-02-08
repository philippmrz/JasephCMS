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
    <form action="newpost.php"><button>New Post</button></form>
  </div>
  <div class="content">
    <?php
    // Credentials for this server
    require('code/credentials.php');

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

    $sql = "SELECT * FROM $usertable U, $posttable P WHERE U.USERID = P.USERID ORDER BY DATE DESC";

    //echo $sql . '<br>'; // Debug

    if($result = mysqli_query($link, $sql)) { // Runs mysql query
        //echo "Successfully ran mysql query.<br>"; // Debug
    } else {
        echo "Error: $sql<br>" . mysqli_error($link) . '<br>';
    }

    echo '<br>';
    while($row = mysqli_fetch_assoc($result)) {
      echo '<div class="post">';
      $postid = $userid = $uname = $date = $title = $content = null;
      foreach($row as $key => $val) {
        if($key == "POSTID") {
          $postid = $val;
        } elseif($key == "USERID") {
          $userid = $val;
        } elseif($key == "USERNAME") {
          $uname = $val;
        } elseif($key == "DATE") {
          $date = $val;
        } elseif($key == "TITLE") {
          $title = $val;
        } elseif($key == "CONTENT") {
          $content = $val;
        }
      }
      echo '<div class="postinfo">';
      echo "$uname<br>$date";
      echo '</div>';
      echo '<div class="posttitle">';
      echo $title;
      echo '</div>';
      echo '<div class="posttext">';
      echo nl2br($content);
      echo '</div>';
      echo '</div>';
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
