<?php
require_once('backend.php');
?>

<div id='header'>
  <a id='logo-wrapper' href='index' title='Go to main page'>
    <svg id='svg-logo' viewBox="0 0 120 170">
      <path id='svg-logo-inner' d="<?= getSVG('logo-inner')?>" />
      <path id='svg-logo-outer' d="<?= getSVG('logo-outer')?>" />
    </svg>
    <svg id='svg-jaseph' viewBox=" 130 0 500 170">
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
    <a href=settings><img id='profile' src='<?=$img?>' title='Your settings'></a>
    <a href='#'>
      <button id='logout' class='secondary-btn' onclick='logout()'>log out</button>
    </a>
    <?php endif;?>
    <div id='design-wrapper'>
      <svg id='palette' viewBox="0 0 24 24" onclick='toggleDesignMenu()' title='Customize the page theme'>
        <path d="<?= getSVG('palette')?>" />
      </svg>
      <div id='design-menu' class='dropdown'>
        <svg id='mask' viewBox="0 0 81 35" onclick='swapStyle()' title='Swap between light and dark theme'>
          <path d="<?= getSVG('mask')?>" />
        </svg>
        <div id='design-color'>
          <input id='color-slider' type='range' oninput='yuh()' min='0' max='360'/>
        </div>
      </div>
    </div>
  </div>
</div>
