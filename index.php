<!doctype html>
<html>
<head>
  <?php require('require/head.php');?>
</head>
<body>
  <?php require('require/header.php');?>
  <div class="content">
    <form action="newpost" method="POST"><button>New Post</button></form>
  </div>
  <div class="content">
    <?php
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
    <!--button onclick="cooCheck()">Check Mate</button>-->
    <!--<button onclick="deleteAllCookies()">Delete Cookies</button>-->
  </div>
  <div id="footer">
    <a href="https://github.com/phmrz/JasephCMS" title="Check out the main branch of this page on GitHub!">Created as a school project by Jakob Mainka, Philipp Merz and Sebastian Scheinert</a>
  </div>
</body>
</html>
