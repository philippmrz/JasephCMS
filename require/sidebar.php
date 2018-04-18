<?php
$db = new DatabaseConnection();
if ($db->auth()):
  $userid = $db->getCurUser();
?>
<div id='sidebar'>
  <a class='sidebar-elem' href='index' id='front-page'
    <?= (strpos($_SERVER['REQUEST_URI'], '/index') !== false or $_SERVER['REQUEST_URI'] == '') ? "status='active'" : ""?>>
    frontpage
  </a>
  <a class='sidebar-elem' href='saved' id='saved'
    <?= strpos($_SERVER['REQUEST_URI'], '/saved') !== false ? "status='active'" : ""?>>
    saved posts
  </a>
  <a class='sidebar-elem' href='profile?id=<?=$userid?>' id='profile'
    <?= strpos($_SERVER['REQUEST_URI'], "/profile?id=$userid") !== false ? "status='active'" : ""?>>
    my profile
  </a>
  <a class='sidebar-elem' href='drafts' id='drafts'
    <?= strpos($_SERVER['REQUEST_URI'], '/drafts') !== false ? "status='active'" : ""?>>
    my drafts
  </a>
  <a class='sidebar-elem' href='newpost' id='new-post'
    <?= strpos($_SERVER['REQUEST_URI'], '/newpost') !== false ? "status='active'" : ""?>>
    new post
  </a>
</div>
<?php endif; ?>

<!-- Mobile only-->
<div id='navbar'>
  <a class='navbar-elem' href='index'
    <?= (strpos($_SERVER['REQUEST_URI'], '/index') !== false or $_SERVER['REQUEST_URI'] == '') ? "status='active'" : ""?>>
    <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?php echo getSVG('home');?>'/></svg>
  </a>
  <?php if ($db->auth()):?>
    <a class='navbar-elem' href='saved'
      <?= strpos($_SERVER['REQUEST_URI'], '/saved') !== false ? "status='active'" : ""?>>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('saved');?>'/></svg>
    </a>
    <a class='navbar-elem' href='profile'
      <?= strpos($_SERVER['REQUEST_URI'], '/profile') !== false ? "status='active'" : ""?>>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('profile');?>'/></svg>
    </a>
    <a class='navbar-elem' href='drafts'
      <?= strpos($_SERVER['REQUEST_URI'], '/drafts') !== false ? "status='active'" : ""?>>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('drafts');?>'/></svg>
    </a>
    <a class='navbar-elem' href='newpost'
      <?= strpos($_SERVER['REQUEST_URI'], '/newpost') !== false ? "status='active'" : ""?>>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('newpost');?>'/></svg>
    </a>
  <?php else:?>
    <a class='navbar-elem' href='login'
      <?= strpos($_SERVER['REQUEST_URI'], '/login') !== false ? "status='active'" : ""?>>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('login');?>'/></svg>
    </a>
    <a class='navbar-elem' href='register'
      <?= (strpos($_SERVER['REQUEST_URI'], "/register")) ? "status='active'" : ""?>>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('register');?>'/></svg>
    </a>
  <?php endif;?>
</div>
