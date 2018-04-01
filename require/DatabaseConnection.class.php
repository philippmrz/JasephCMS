<?php
  class DatabaseConnection extends mysqli {
    function __construct() {
      require('credentials.php');
      @parent::__construct("$servername", "$username", "$password", "$dbname");

      if ($this->connect_error) {
        die($this->connect_errno . $mysqli->connect_error);
      }
    }

    function postsAusgeben() {
      require('credentials.php');
      $r = @parent::query("SELECT TITLE, CONTENT, substring(DATE, 1, 10) AS DATE, substring(DATE, 11, 19) AS TIME, USERNAME from $posttable, $usertable WHERE $posttable.USERID = $usertable.USERID ORDER BY DATE DESC");
      while ($row = $r->fetch_assoc()){

        $return .= <<<MYSQL
        <div class='post'>
          <img class='thumbnail' src='assets/dummy-thumbnail.png'>

          <div class='post-without-tn'>
            <div class='post-info'>
              <p class='title'>$row[TITLE]</p>
              <div class='date-uname'>
                <a class='username'>$row[USERNAME]</a>
                <p class='at'>on</p>
                <p class='date'>$row[DATE] at $row[TIME]</p>
              </div>
            </div>
            <p class='post-text'>$row[CONTENT]</p>
          </div>
        </div>
MYSQL;
      }
      return $return;
    }
  }
?>