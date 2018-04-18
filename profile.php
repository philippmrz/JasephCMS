<?php
require_once('require/backend.php');
require('require/credentials.php');
$db = new DatabaseConnection();

$admin = false;

if ($db->auth()) {
  $curuserid = $db->getCurUser();
  $admin = ($db->getRole($curuserid) == 'ADMIN') ? true : false;
}

if (!isset($_GET['id'])) {
  if ($db->auth()) {
    header("Location: profile?id=$curuserid");
  } else {
    header('Location: index');
  }
  exit();
} else {
  $profileID = $_GET['id'];
}


if (isset($curuserid)) {
  $isself = ($curuserid == $profileID) ? true : false;
}

$getInfo = $db->query("SELECT substring(REGISTERED, 1, 10) AS DAY, VISIBILITY FROM $usertable WHERE USERID = $profileID");
$rowInfo = $getInfo->fetch_assoc();
$since = $rowInfo['DAY'];
$visible = $rowInfo['VISIBILITY'] == 'VISIBLE' ? true : false;

if ($visible or $isself or $admin) {
  $getPosts = $db->query("SELECT POSTID, TITLE, substring(DATE, 1, 10) AS DAY, substring(DATE, 12, 5) AS TIME FROM $posttable WHERE USERID = $profileID");
  $amtPosts = $getPosts->num_rows;
}

?>
<html>
<head>
  <?php require 'require/head.php';?>
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/profile.css">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php'; ?>
  <div id='content'>
    <?php
    if ($visible or $isself or $admin):
    ?>
    <div id='profile-sheet'>
      <div id='profile-info'>
        <img id='profile-avatar' src='<?=$db->getImgPath($profileID)?>'/>
        <div id='profile-info-no-av'>
          <h1 id='profile-info-name'>
            <?=$db->getUsername($profileID)?>
            <?php
            if (!$visible and ($isself or $admin)) {
              ?>
              <span id='profile-info-anon'>(anonymous)</span>
              <?php
            }
            ?>
          </h1>
          <p><?=ucfirst(strtolower($db->getRole($profileID)))?></p>
          <p><?=$amtPosts . (($amtPosts > 1 or $amtPosts == 0) ? ' posts' : ' post')?></p>
          <p>Member since <?=$since?></p>
        </div>
      </div>
      <div id='profile-posts'>
        <h1 id='profile-title2'>
          <?php
          if ($isself) {
            echo 'Your Posts';
          } else {
            echo $db->getUsername($profileID) . '\'s Posts';
          }
          ?>
        </h1>
        <?php
          if ($amtPosts > 0) {
            $return = '';
            while ($row = $getPosts->fetch_assoc()) {
              $return .= <<<RETURN
              <a class='profile-post' href='onepost.php?id=$row[POSTID]'>
                <div class='profile-post-title'>
                  $row[TITLE]
                </div>
                <div class='profile-post-date'>
                  <span class='profile-post-day'>
                    on $row[DAY]
                  </span>
                  <span class='profile-post-time'>
                    at $row[TIME]
                  </span>
                </div>
              </a>
RETURN;
            }
            echo $return;
          } else {
            echo $db->getUsername($profileID) . ' hasn\'t posted anything yet.';
          }
        ?>
      </div>
    </div>
    <?php
    else:
    ?>
    <div id='profile-sheet'>
      <h1 id='profile-title2'>This profile is anonymous</h1>
    </div>
    <?php
    endif;
    ?>
  </div>
</div>

<script>applyStyle();</script>
<script>updateMD();</script>
</body>
</html>
