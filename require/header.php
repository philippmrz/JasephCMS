<div id="head-wrap">
    <a href="index" title="Go to main page"><img id="head-logo" src="assets/jaseph_normal.png"></a>
    <div id="btn-holder">
    <?php if(!isset($_COOKIE["logcheck"])):?>
        <a href="login"><button id="login" class="btn">login</button></a>
        <a href="register"><button id="register" class="btn">register</button></a>
    <?php else:?>
        <a href="#"><button id="logout" class="btn" onclick="logout()">log out</button></a>
        <a href="settings"><button id="nav-settings" class ="btn">settings</button></a>
    <?php endif;?>
        <img id="mask" src="assets/mask.png" onclick="swapStyle()" title="Change page theme">
    </div>
</div>
