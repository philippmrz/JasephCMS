<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <link rel="stylesheet" href="style/index.css" id="pagestyle">
</head>
<body>

<?php if (isset($_COOKIE["logcheck"])): ?>
  <a href="newpost" title="Create a new post">
    <img id="floating-button-newpost" src="assets/newpost-button.png"/>
  </a>
<?php endif; ?>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <div id='sidebar'></div>
  <div id='content'>
    <div class='post'>
      <img class='thumbnail' src='assets/dummy-thumbnail.png'>

      <div class='post-without-tn'>
        <div class='post-info'>
          <p class='title'>dummy title text long one</p>
          <div class='date-uname'>
            <a class='username'>username</a>
            <p class='at'>at</p>
            <p class='date'>2018-12-12</p>
          </div>
        </div>
        <p class='post-text'>lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem dolor ipsum sit amet sit dolor ipsum dolor sit dolor amet sit lorem. Lorem ipsum dolor sit amet sit amet dolor impsum alorem ipsum dolor sit amet lorem ipsum dolor sit</p>
      </div>
    </div>
    <div class='post'>
      <img class='thumbnail' src='assets/dummy-thumbnail.png'>

      <div class='post-without-tn'>
        <div class='post-info'>
          <p class='title'>dummy title text long one</p>
          <div class='date-uname'>
            <a class='username'>username</a>
            <p class='at'>at</p>
            <p class='date'>2018-12-12</p>
          </div>
        </div>
        <p class='post-text'>lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem dolor ipsum sit amet sit dolor ipsum dolor sit dolor amet sit lorem. Lorem ipsum dolor sit amet sit amet dolor impsum alorem ipsum dolor sit amet lorem ipsum dolor sit</p>
      </div>
    </div>
    <div class='post'>
      <img class='thumbnail' src='assets/dummy-thumbnail.png'>

      <div class='post-without-tn'>
        <div class='post-info'>
          <p class='title'>dummy title text long one</p>
          <div class='date-uname'>
            <a class='username'>username</a>
            <p class='at'>at</p>
            <p class='date'>2018-12-12</p>
          </div>
        </div>
        <p class='post-text'>lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem ipsum dolor sit amet lorem dolor ipsum sit amet sit dolor ipsum dolor sit dolor amet sit lorem. Lorem ipsum dolor sit amet sit amet dolor impsum alorem ipsum dolor sit amet lorem ipsum dolor sit</p>
      </div>
    </div>
  </div>
</div>

<script>applyStyle();</script>

</body>
</html>
