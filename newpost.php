<?php
require_once('require/backend.php');
require('require/credentials.php');

$db = new DatabaseConnection();

if (!$db->auth()) {
  header('Location: index');
}

if (isset($_POST["submit-post"])) {
  $db->createPost(
    $db->getUserID($_COOKIE['identifier']), //userid
    $db->escape_string($_POST["title"]), //title
    $db->escape_string($_POST["content"]) //content
  );
  header("Location: index");
}

if (isset($_POST["submit-draft"])) {
  $db->createDraft(
    $db->getUserID($_COOKIE['identifier']), //userid
    $db->escape_string($_POST["title"]), //title
    $db->escape_string($_POST["content"]) //content
  );
  header("Location: drafts");
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

      <button type="submit" id="btnpost" class="floating-action-btn" name="submit-post" >
        <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('confirm');?>'/></svg>
      </button>

      <div id="newpost-expand" class="floating-action-btn" onclick='toggleExpand();'>
        <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('expand-vertical');?>'/></svg>
      </div>

      <div id='expand-wrapper'>

        <div id="newpost-expand-drafts" class="floating-action-btn">
          <a href='drafts'><svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('drafts');?>'/></svg></a>
        </div>

        <button type="submit" id="newpost-expand-newdraft" class="floating-action-btn" name="submit-draft">
          <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('savedraft');?>'/></svg>
        </button>

        <button type="submit" id="newpost-expand-submit" class="floating-action-btn" name="submit-post">
          <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('confirm');?>'/></svg>
        </button>

      </div>
    </form>
  </div>
</div>

<script>
  applyStyle();
  rezNewpost();
  updateMD();
</script>
</body>
</html>
