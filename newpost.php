<?php
require_once('require/backend.php');
require('require/credentials.php');

$db = new DatabaseConnection();

if (!$db->auth()) {
  header('Location: index');
}

session_start();

$newpostmsg = [];
if(empty($_SESSION['newpostmsg'])) {
  echo 'reset session';
  $_SESSION['newpostmsg'] = [];
}

if (isset($_GET["draftid"])) {
  $draftid = $_GET["draftid"];
  echo "$draftid<br>";
  $userid = $db->getUserID($_COOKIE['identifier']);
  //get draft information
  $getDraft = $db->query("SELECT * FROM $drafttable WHERE DRAFTID = '$draftid'");
  //check if draft exists
  if ($getDraft->num_rows > 0) {
    $row = $getDraft->fetch_assoc();
    //draft exists
    echo 'exists<br>';
    //check if draft belongs to user
    if ($row['USERID'] == $userid) {
      //draft belongs to user
      echo 'belongs to user<br>';
      $drafttitle = $row['TITLE'];
      $draftcontent = $row['CONTENT'];
      array_push($newpostmsg, 'draft loaded successfully');
    } else {
      //draft does not belong to user
      echo 'doesnt belong to user<br>';
      array_push($_SESSION['newpostmsg'], 'draft does not belong to you');
      header('Location: newpost#popup1');
    }
  } else {
    //draft does not exist;
    echo 'doesnt exist<br>';
    array_push($_SESSION['newpostmsg'], 'draft does not exist');
    header('Location: newpost#popup1');
  }
}

foreach ($_SESSION['newpostmsg'] as $val) {
  array_push($newpostmsg, $val);
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
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/newpost.css">
  <script src="script/newpost.js"></script>
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php'; ?>
  <div id='content'>

    <div id='popup1' class='overlay'>
      <div class='popup'>
        <a class="close" href="#">&times;</a>
        <div class='popup-content'>
          <?php
          if (!empty($newpostmsg)) {
            foreach ($newpostmsg as $val) {
              echo "$val<br>";
            }
            $_SESSION['newpostmsg'] = [];
          }
          ?>
        </div>
      </div>
    </div>

    <div class='floating-action-btn' style='left: 5vh;'>
      <a href='#popup1'>Open</a>
    </div>

    <form id='newpost' action="" method="POST">

      <div id='post-sheet'>

        <div id='title-wrapper'>
          <p class='char-counter' id="titlecharswrapper">200</p>
          <input id="titleField" name="title" type="text" placeholder="Title" maxlength="200" oninput="updateCharsLeft(200, 'title')" autocomplete='off' <?= (isset($drafttitle)) ? "value=$drafttitle" : '' ?> required autofocus>
        </div>

        <div id='content-wrapper'>
          <p class='char-counter' id="contentcharswrapper">10000</p>
          <textarea id="contentArea" name="content" placeholder="Post content" spellcheck="false" maxlength="10000" oninput="refreshContentArea()" autocomplete='off' <?= (isset($draftcontent)) ? "value=$draftcontent" : '' ?> required></textarea>
        </div>

      </div>

      <div id='preview-sheet'>
        <span id="preview" class="md"><p></p></span>
      </div>

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
  addCtrlEnterListener();
  rezNewpost();
  updateMD();
</script>
</body>
</html>
