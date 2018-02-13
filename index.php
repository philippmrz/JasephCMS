<!doctype html>
<html>

<head>
  <?php require 'require/head.php';?>
</head>

<body>
  <?php require 'require/header.php';?>
  <div class="content">
    <form action="newpost" method="POST"><button>New Post</button></form>
  </div>
  <div class="content">
  <?php
// Credentials for this server
require 'require/credentials.php';

$mysqli = new mysqli("$servername", "$username", "$password", "$dbname");

if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
$query = "SELECT TITLE, CONTENT FROM $posttable";

if ($result = $mysqli->query($query)) {

    while ($row = $result->fetch_assoc()) {
        echo "Title: ", $row['TITLE'], "<br>", " Content: ", $row["CONTENT"];
        echo "<br><br>";
    }

} else {
    echo "Couldn't query database, try again";
}

?>
  </div>
  <div id="footer">
    <a href="https://github.com/phmrz/JasephCMS" title="Check out the main branch of this page on GitHub!">Created as a school project by Jakob Mainka, Philipp Merz and Sebastian Scheinert</a>
  </div>
</body>
</html>
