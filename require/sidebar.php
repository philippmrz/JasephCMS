<?php
$db = new DatabaseConnection();
if ($db->auth()):
  $userid = $db->getCurUser();
?>
<div id='sidebar'>
  <a class='sidebar-elem' href='index' id='front-page' <?= $db->getActive('index') ? "status='active'" : ""?>>
    <svg viewBox="0 0 24 24"><path d='<?= getSVG('home');?>'/></svg>
    <span>frontpage</span>
  </a>
  <a class='sidebar-elem' href='saved' id='saved' <?= $db->getActive('saved') ? "status='active'" : ""?>>
    <svg viewBox="0 0 24 24"><path d='<?= getSVG('saved');?>'/></svg>
    <span>saved posts</span>
  </a>
  <a class='sidebar-elem' href='profile?id=<?=$userid?>' id='profile' <?= $db->getActive('myprofile') ? "status='active'" : ""?>>
    <svg viewBox="0 0 24 24"><path d='<?= getSVG('profile');?>'/></svg>
    <span>my profile</span>
  </a>
  <a class='sidebar-elem' href='drafts' id='drafts' <?= $db->getActive('drafts') ? "status='active'" : ""?>>
    <svg viewBox="0 0 24 24"><path d='<?= getSVG('drafts');?>'/></svg>
    <span>my drafts</span>
  </a>
  <a class='sidebar-elem' href='newpost' id='new-post' <?= $db->getActive('newpost') ? "status='active'" : ""?>>
    <svg viewBox="0 0 24 24"><path d='<?= getSVG('newpost');?>'/></svg>
    <span>new post</span>
  </a>
  <div class='category'>
    Community
  </div>
  <a class='sidebar-elem' href='users' id='users' <?= $db->getActive('users') ? "status='active'" : ""?>>
    <svg viewBox="0 0 24 24"><path d='<?= getSVG('users');?>'/></svg>
    <span>users</span>
  </a>
  <a class='sidebar-elem' href='friends' id='friends' <?= $db->getActive('friends') ? "status='active'" : ""?>>
    <svg viewBox="0 0 24 24"><path d='<?= getSVG('friends');?>'/></svg>
    <span>friends</span>
  </a>
  <div id='following'>
  </div>
</div>
<?php endif; ?>

<!-- Mobile only-->
<div id='navbar'>
  <a class='navbar-elem' href='index' <?= $db->getActive('index') ? "status='active'" : ""?>>
    <svg class='svg-24' viewBox="0 0 24 24"><path d='<?= getSVG('home');?>'/></svg>
  </a>
  <?php if ($db->auth()):?>
    <a class='navbar-elem' href='saved'<?= $db->getActive('saved') ? "status='active'" : ""?>>
      <svg class='svg-24' viewBox="0 0 24 24"><path d='<?= getSVG('saved');?>'/></svg>
    </a>
    <a class='navbar-elem' href='profile?id=<?=$userid?>' <?= $db->getActive('myprofile') ? "status='active'" : ""?>>
      <svg class='svg-24' viewBox="0 0 24 24"><path d='<?= getSVG('profile');?>'/></svg>
    </a>
    <a class='navbar-elem' href='drafts' <?= $db->getActive('drafts') ? "status='active'" : ""?>>
      <svg class='svg-24' viewBox="0 0 24 24"><path d='<?= getSVG('drafts');?>'/></svg>
    </a>
    <a class='navbar-elem' href='newpost' <?= $db->getActive('newpost') ? "status='active'" : ""?>>
      <svg class='svg-24' viewBox="0 0 24 24"><path d='<?= getSVG('newpost');?>'/></svg>
    </a>
  <?php else:?>
    <a class='navbar-elem' href='login' <?= $db->getActive('login') ? "status='active'" : ""?>>
      <svg class='svg-24' viewBox="0 0 24 24"><path d='<?= getSVG('login');?>'/></svg>
    </a>
    <a class='navbar-elem' href='register' <?= $db->getActive('register') ? "status='active'" : ""?>>
      <svg class='svg-24' viewBox="0 0 24 24"><path d='<?= getSVG('register');?>'/></svg>
    </a>
  <?php endif;?>
</div>
