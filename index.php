<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
</head>
<body>
  <?php require 'require/header.php';?>
  <?php if (isset($_COOKIE["logcheck"])): ?>
    <a href="newpost">
      <img id="floating-action-button" src="assets/action-button.png">
    </a>
  <?php endif; ?>
  <script>applyStyle();</script>
  <div class="content">
    <?php
      // Credentials for this server
      require 'require/credentials.php';

      $mysqli = new mysqli("$servername", "$username", "$password", "$dbname");

      if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        exit();
      }
  function random_string($length) {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!$.";
    $c_length = strlen($characters);
    $string = "";
    for ($i=0;$i<$length;$i++) {
        $string .= $characters[rand(0, $c_length -1)];
    }
    return $string;
}

//if revisit when "stay logged in" was checked before
if (isset($_COOKIE["identifier"]) && isset($_COOKIE["token"])) {
    $identifier = $_COOKIE["identifier"];
    $token = $_COOKIE["token"];
    $result = $mysqli->query("SELECT TOKEN FROM user WHERE IDENTIFIER='$identifier'");
    $row = $result->fetch_assoc();
    $db_token = $row["TOKEN"];
    $hash_token = hash("sha256", $token);

    if ($hash_token == $db_token) {//new token
        $new_token = random_string(32);
        $hash_new_token = hash("sha256", $new_token);
        $update = $mysqli->query("UPDATE user SET TOKEN = '$hash_new_token' WHERE IDENTIFIER = '$identifier'");
        $get_uname = $mysqli->query("SELECT USERNAME WHERE IDENTIFIER = '$identifier'");
        $row_u = $get_uname->fetch_assoc();
        $db_uname = $row_u["USERNAME"];
        setcookie("identifier", $identifier, time()+86400*356);
        setcookie("token", $new_token, time()+86400*356);
        setcookie("logcheck", "true", time()+86400*356);
        setcookie("uname", $db_uname, time()+86400*356);


        header("location: //mach deine page hier rein");
        exit;
    } else {
        die("You're a cheater");
    }
}
      $query = "SELECT TITLE, CONTENT, SUBSTRING(DATE, 1, 10) AS DATE FROM $posttable";

      //Fetch posts from database and echo them to paragraphs
      if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
          echo "<div class='post'>";

          echo "<p class='post-title-date'>";

          echo $row["TITLE"];

          echo "<span class='align-right'>";
          echo $row["DATE"];
          echo "</span>";

          echo "</p>";

          echo "<p class='posttext'>";
          echo nl2br($row["CONTENT"]);
          echo "</p>";

          echo "</div>";
        }
      } else {
        echo "Couldn't query database, try again";
      }
      ?>
  </div>
  <?php require 'require/footer.php';?>
</body>
</html>
