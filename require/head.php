<link rel="icon" href="assets/icon.png"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css?family=Open+Sans|Lato|Slabo+27px" rel="stylesheet">
<link rel="stylesheet" href="style/general.css" id="pagestyle">
<script src="script/script.js"></script>
<title>JasephCMS</title>
<?php
//Include classes automatically
spl_autoload_register(function ($class) {
  include 'require/' . $class . '.class.php';
});
?>
