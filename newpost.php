<?php require_once('require/backend.php');
require('require/credentials.php');

$db = new DatabaseConnection();

if (!$db->auth()) {
  header('Location: index');
}

if (isset($_POST["title"])) {

  $userid = $db->getUserID($_COOKIE['identifier']);

  $title = $db->escape_string($_POST["title"]);
  $content = $db->escape_string($_POST["content"]);

  $insert = $db->query("INSERT INTO $posttable (USERID, TITLE, CONTENT) VALUES ('$userid', '$title', '$content')");
  header("Location: index");

}
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <link rel="stylesheet" href="style/newpost.css" id="pagestyle">
  <script src="script/newpost.js"></script>
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php'; ?>
  <div id='content'>
    <form id='newpost' action="" method="POST">

      <div id='post-sheet'>

        <div id='title-wrapper'>
          <p class='char-counter' id="titlecharswrapper">200</p>
          <input id="titleField" name="title" type="text" placeholder="Title" maxlength="200" oninput="updateCharsLeft(200, 'title')" autocomplete='off' required autofocus>
        </div>

        <div id='content-wrapper'>
          <p class='char-counter' id="contentcharswrapper">10000</p>
          <textarea id="contentArea" name="content" placeholder="Post content" spellcheck="false" maxlength="10000" oninput="refreshContentArea()" autocomplete='off' required></textarea>
        </div>

      </div>

      <div id='preview-sheet'>
        <span id="preview" class="md"><p></p></span>
      </div>

      <button type="submit" id="btnpost" class="floating-action-btn">&#10003;</button>
    </form>
  </div>
</div>

<script>applyStyle();</script>
<script>updateMD();</script>
</body>
</html>
