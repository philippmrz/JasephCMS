<div id='sidebar'>
  <a class='sidebar-elem' href='index' id='front-page'
      <?= ($_SERVER['REQUEST_URI'] == "/") ? "status='active'" : ""?>>
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