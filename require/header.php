<?php
$db = new DatabaseConnection();
?>

<div id='header'>
  <a id='logo-wrapper' href='index' title='Go to main page'><img id='head-logo'></a>
  <div id='btn-holder'>
    <?php if(!$db->auth()):?>
    <a href='login'>
      <button id='login' class='secondary-btn'>login</button>
    </a>
    <a href='register'>
      <button id='register' class='primary-btn'>register</button>
    </a>

    <?php else:?>
    <?php
    $db = new DatabaseConnection();
    $img = $db->getImgPath($db->getUserID($_COOKIE['identifier']));
    ?>
    <a href=settings.php><img id='profile' src='<?=$img?>' title='Your settings'></a>
    <a href='#'>
      <button id='logout' class='secondary-btn' onclick='logout()'>log out</button>
    </a>
    <?php endif;?>
    <img id='mask' src='assets/mask.png' onclick='swapStyle()' title='Change page theme'>
  </div>
</div>
