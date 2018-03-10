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
