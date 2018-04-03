<?php
  function isLoggedIn(){
    return (isset($_COOKIE['logcheck'])) ? true : false;
  }

  function invertSortOrder() {
    return (!$_GET['sort'] or $_GET['sort'] == 'DESC') ? "ASC" : "DESC";
  }

  function getSortSVG() {
    return (!$_GET['sort'] or $_GET['sort'] == 'DESC') ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z"/></svg>' : '<svg rotate="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z"/></svg>';
  }

  class DatabaseConnection extends mysqli {
    function __construct() {
      require('credentials.php');
      @parent::__construct("$servername", "$username", "$password", "$dbname");

      if ($this->connect_error) {
        die($this->connect_errno . $mysqli->connect_error);
      }
    }

    function postsAusgeben($order) {
      $order = ($order == 'ASC') ? 'DESC' : 'ASC';
      require('credentials.php');
      $r = @parent::query("SELECT substring(TITLE, 1, 50) AS TITLE, substring(CONTENT, 1, 200) AS CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 11, 19) AS TIME, USERNAME from $posttable, $usertable WHERE $posttable.USERID = $usertable.USERID ORDER BY DATE $order");
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
                <p class='date'>$row[DAY] at $row[TIME]</p>
              </div>
            </div>
            <p class='post-text'>$row[CONTENT]...</p>
          </div>
        </div>
MYSQL;
      }
      return $return;
    }
  }
?>