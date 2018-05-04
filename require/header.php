<?php
require_once('backend.php');
$db = new DatabaseConnection();
?>

<div id='header'>
  <a id='logo-wrapper' href='index' title='Go to main page'>
    <svg id='svg-logo' viewBox="0 0 120 200">
      <path id='svg-logo-inner' d="<?= getSVG('logo-inner')?>" />
      <path id='svg-logo-outer' d="<?= getSVG('logo-outer')?>" />
    </svg>
    <svg id='svg-jaseph' viewBox=" 130 0 500 200">
      <path d="<?= getSVG('jaseph')?>" />
    </svg>
    <!--<img id='head-logo'>-->
  </a>
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
    $img = $db->getImgPath($db->getCurUser());
    ?>
    <a href=settings.php><img id='profile' src='<?=$img?>' title='Your settings'></a>
    <a href='#'>
      <button id='logout' class='secondary-btn' onclick='logout()'>log out</button>
    </a>
    <?php endif;?>
    <img id='mask' src='assets/mask.png' onclick='swapStyle()' title='Change page theme'>
  </div>
</div>
