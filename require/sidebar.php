<?php  if (isLoggedIn()):?>
<div id='sidebar'>
  <a class='sidebar-elem' href='index' id='front-page'
      <?= ($_SERVER['REQUEST_URI'] == "/" || strpos($_SERVER['REQUEST_URI'], "/index") !== false) ? "status='active'" : ""?>>
      frontpage
  </a>
  <a class='sidebar-elem' href='saved' id='saved'
      <?= ($_SERVER['REQUEST_URI'] == "/saved") ? "status='active'" : ""?>>
      saved posts
  </a>
  <a class='sidebar-elem' href='myposts' id='my-posts'
      <?= ($_SERVER['REQUEST_URI'] == "/myposts") ? "status='active'" : ""?>>
      my posts
  </a>
  <a class='sidebar-elem' href='settings' id='settings'
      <?= ($_SERVER['REQUEST_URI'] == "/settings") ? "status='active'" : ""?>>
      settings
  </a>
  <a class='sidebar-elem' href='newpost' id='new-post'
      <?= ($_SERVER['REQUEST_URI'] == "/newpost") ? "status='active'" : ""?>>
      new post
  </a>
</div>
<?php endif; ?>

<!-- Mobile only-->
<div id='navbar'>
  <ul>
    <li><a href='index'>frontpage</a></li>
    <?php if (isLoggedIn()):?>
    <li><a href='saved'>saved posts</a></li>
    <li><a href='myposts'>my posts</a></li>
    <li><a href='settings'>settings</a></li>
    <li><a href='newpost'>new post</a></li>
    <li> </li>
    <?php endif;?>
  </ul>
</div>
