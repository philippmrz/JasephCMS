<?php
require_once('require/backend.php');
require('require/credentials.php');

if (!$db->auth()) {
  header('Location: index');
}
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/users.css">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php'; ?>
  <div id='content'>
    <div id='users-sheet'>
      <div id='user-head'>
        <div></div>
        <div>User</div>
        <div>Posts</div>
        <div>Member since</div>
        <div>Last activity</div>
      </div>
      <?php
      $getUsers = $db->query("
      SELECT
        U.USERID, U.USERNAME, substring(REGISTERED, 1, 10) AS REGISTERED, U.LASTSEEN, I.PATH, COUNT(P.POSTID) AS POSTS
        FROM $usertable U
        LEFT JOIN $imgtable I ON U.USERID = I.USERID
        LEFT JOIN $posttable P ON U.USERID = P.USERID
        AND U.VISIBILITY = 'VISIBLE'
        GROUP BY U.USERID
      ");
      $return = '';
      while ($row = $getUsers->fetch_assoc()) {
        $userid = $row['USERID'];
        $uname = htmlspecialchars($row['USERNAME']);
        $registered = $row['REGISTERED'];
        $lastseen = convertDate($row['LASTSEEN']);
        $imgpath = is_null($row['PATH']) ? 'assets/default-avatar.png' : $row['PATH'];
        $posts = $row['POSTS'];
        $return .= <<<RETURN
          <div class='user'>
            <img src='$imgpath'/>
            <div>$uname</div>
            <div>$posts</div>
            <div>$registered</div>
            <div>$lastseen</div>
          </div class='user'>
RETURN;
      }
      echo $return;
      ?>
    </div>
  </div>
</div>

<script>
  applyStyle();
</script>
</body>
</html>
