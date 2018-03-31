<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
</head>
<body>
  <?php require 'require/header.php';?>
  <script>applyStyle();</script>
  <div class="content">
    <?php if (isset($_COOKIE["logcheck"])): ?>
      <?php if (!isset($_POST["title"])): ?>
        <h1>New Post</h1>
        <form action="" method="POST">
          <div id="titlecharswrapper">0/200</div>
          <input id="titleField" name="title" type="text" placeholder="Post title" autocomplete="" maxlength="200" oninput="updateCharsLeft(200, 'title')" required/><br><br>
          <div id="contentcharswrapper">0/10000</div>
          <textarea id="contentArea" name="content" placeholder="Post content" rows="20" cols="100" spellcheck="false" maxlength="10000" oninput="updateCharsLeft(10000, 'content')" required></textarea><br>
          <div id="btnpostwrapper">
            <button type="submit" id="btnpost" class="btn">Post</button>
          </div>
        </form>
      <?php else: ?>
        <?php
// Credentials for this server
require 'require/credentials.php';

$success = true;

if (!isset($_POST["title"]) || empty($_POST["title"]) || ctype_space($_POST["title"])) { //Check if the title is not set/empty/only spaces. Exit if true.
    echo 'Something went wrong. Title must not be empty! For some reason submitting an empty input was allowed.<br>';
    $success = false;
}

if (!isset($_POST["content"]) || empty($_POST["content"]) || ctype_space($_POST["content"])) { //Check if the content is not set/empty/only spaces. Exit if true.
    echo 'Something went wrong. Content must not be empty! For some reason submitting an empty input was allowed.<br>';
    $success = false;
}

$mysqli = new mysqli("$servername", "$username", "$password", "$dbname");

if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

if ($success) {
    $uname = $_COOKIE["uname"];
    $get_uid = $mysqli->query("SELECT USERID FROM $usertable WHERE USERNAME = '$uname'");
    if (!$get_uid) {
        echo $mysqli->error;
    }
    $row_uid = $get_uid->fetch_assoc();
    $db_uid = $row_uid["USERID"];

    $title = $mysqli->escape_string($_POST["title"]);
    $content = $mysqli->escape_string($_POST["content"]);

    $insert = $mysqli->query("INSERT INTO $posttable (USERID, TITLE, CONTENT) VALUES ('$db_uid', '$title', '$content')"); // Inserts data into the post table
    if (!$insert) {
        echo $mysqli->error;
    }

    $mysqli->close();
    ?>
            <h1>Successfully created new post!</h1>
            <script>redirect("index", 5);</script>
            <?php
} else {
    ?>
            <h1>Creation of new post failed!</h1>
            <?php
}
?>
      <?php endif;?>
    <?php else: ?>
    <h1>Get Out</h1>
    <script>redirect('index');</script>
    <?php endif;?>
  </div>
</body>
</html>
