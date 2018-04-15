<?php
$db = new DatabaseConnection();
if ($db->auth()):
?>
<div id='sidebar'>
  <a class='sidebar-elem' href='index' id='front-page'
    <?= (strpos($_SERVER['REQUEST_URI'], "/") || strpos($_SERVER['REQUEST_URI'], "/index")) ? "status='active'" : ""?>>
    frontpage
  </a>
  <a class='sidebar-elem' href='saved' id='saved'
    <?= (strpos($_SERVER['REQUEST_URI'], "/saved")) ? "status='active'" : ""?>>
    saved posts
  </a>
  <a class='sidebar-elem' href='myposts' id='my-posts'
    <?= (strpos($_SERVER['REQUEST_URI'], "/myposts")) ? "status='active'" : ""?>>
    my posts
  </a>
  <a class='sidebar-elem' href='settings' id='settings'
    <?= (strpos($_SERVER['REQUEST_URI'], "/settings")) ? "status='active'" : ""?>>
    settings
  </a>
  <a class='sidebar-elem' href='newpost' id='new-post'
    <?= (strpos($_SERVER['REQUEST_URI'], "/newpost")) ? "status='active'" : ""?>>
    new post
  </a>
</div>
<?php endif; ?>

<!-- Mobile only-->
<div id='navbar'>
  <a class='navbar-elem' href='index'
    <?= (strpos($_SERVER['REQUEST_URI'], "/") || strpos($_SERVER['REQUEST_URI'], "/index")) ? "status='active'" : ""?>>
    <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?php echo getSVG('home');?>'/></svg>
  </a>
  <?php if ($db->auth()):?>
    <a class='navbar-elem' href='saved'
      <?= (strpos($_SERVER['REQUEST_URI'], "/saved")) ? "status='active'" : ""?>>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('saved');?>'/></svg>
    </a>
    <a class='navbar-elem' href='myposts'
      <?= (strpos($_SERVER['REQUEST_URI'], "/myposts")) ? "status='active'" : ""?>>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('myposts');?>'/></svg>
    </a>
    <a class='navbar-elem' href='settings'
      <?= (strpos($_SERVER['REQUEST_URI'], "/settings")) ? "status='active'" : ""?>>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('settings');?>'/></svg>
    </a>
    <a class='navbar-elem' href='newpost'
      <?= (strpos($_SERVER['REQUEST_URI'], "/newpost")) ? "status='active'" : ""?>>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('newpost');?>'/></svg>
    </a>
  <?php else:?>
    <a class='navbar-elem' href='login'
      <?= (strpos($_SERVER['REQUEST_URI'], "/login")) ? "status='active'" : ""?>>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('login');?>'/></svg>
    </a>
    <a class='navbar-elem' href='register'
      <?= (strpos($_SERVER['REQUEST_URI'], "/register")) ? "status='active'" : ""?>>
      <svg class='svg-24' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d='<?= getSVG('register');?>'/></svg>
    </a>
  <?php endif;?>
</div>
