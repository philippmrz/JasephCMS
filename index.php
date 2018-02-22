<!doctype html>
<html>

<head>
  <?php require 'require/head.php';?>
</head>

<body>
    <?php require 'require/header.php';?>
    <a href="newpost">
        <img id="floating-action-button" src="assets/action-button.png">
    </a>
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
        echo "<div class='post'>";

        echo "<p class='posttitle'>";
        echo $row["TITLE"];
        echo "</p>";

        echo "<p class='posttext'>";
        echo $row["CONTENT"];
        echo "</p>";

        echo "</div>";
    }

} else {
    echo "Couldn't query database, try again";
}

?>
  </div>
</body>
</html>
