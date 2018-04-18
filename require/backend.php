<?php
// PHP file for all backend logic, includes a class for all things DatabaseConnection

  function invertSortOrder() {
    return (isset($_GET['sort']) and $_GET['sort'] == 'ASC') ? "DESC" : "ASC";
  }

  // Returns SVG with name $svg from $svg_list
  function getSVG($svg) {
    $svg_list = [
      'sort' => 'M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z',
      'home' => 'M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z',
      'newpost' => 'M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z',
      'saved' => 'M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2z',
      'profile' => 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z',
      'settings' => 'M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z',
      'login' => 'M7,14A2,2 0 0,1 5,12A2,2 0 0,1 7,10A2,2 0 0,1 9,12A2,2 0 0,1 7,14M12.65,10C11.83,7.67 9.61,6 7,6A6,6 0 0,0 1,12A6,6 0 0,0 7,18C9.61,18 11.83,16.33 12.65,14H17V18H21V14H23V10H12.65Z',
      'register' => 'M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z',
      'drafts' => 'M12,2A7,7 0 0,0 5,9C5,11.38 6.19,13.47 8,14.74V17A1,1 0 0,0 9,18H15A1,1 0 0,0 16,17V14.74C17.81,13.47 19,11.38 19,9A7,7 0 0,0 12,2M9,21A1,1 0 0,0 10,22H14A1,1 0 0,0 15,21V20H9V21Z',
      'savedraft' => 'M16.5,6V17.5A4,4 0 0,1 12.5,21.5A4,4 0 0,1 8.5,17.5V5A2.5,2.5 0 0,1 11,2.5A2.5,2.5 0 0,1 13.5,5V15.5A1,1 0 0,1 12.5,16.5A1,1 0 0,1 11.5,15.5V6H10V15.5A2.5,2.5 0 0,0 12.5,18A2.5,2.5 0 0,0 15,15.5V5A4,4 0 0,0 11,1A4,4 0 0,0 7,5V17.5A5.5,5.5 0 0,0 12.5,23A5.5,5.5 0 0,0 18,17.5V6H16.5Z',
      'expand-vertical' => 'M12,16A2,2 0 0,1 14,18A2,2 0 0,1 12,20A2,2 0 0,1 10,18A2,2 0 0,1 12,16M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10M12,4A2,2 0 0,1 14,6A2,2 0 0,1 12,8A2,2 0 0,1 10,6A2,2 0 0,1 12,4Z',
      'confirm' => 'M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z',
      'delete' => 'M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z',
      'save' => 'M15,9H5V5H15M12,19A3,3 0 0,1 9,16A3,3 0 0,1 12,13A3,3 0 0,1 15,16A3,3 0 0,1 12,19M17,3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V7L17,3Z'
    ];
    return $svg_list[$svg];
  }

  // Returns the sort SVG to be used for sort floating action button
  function getSortSVG() {
    $rotate = (isset($_GET['sort']) and $_GET['sort'] == 'ASC') ? 'true' : 'false';
    $path = getSVG('sort');
    $svg = "<svg rotate='$rotate' xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path d='$path'/></svg>";
    return $svg;
  }

  // Returns a random string (0-9;a-Z;!;$;.) with length $length
  function randomString($length) {
      $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!$.";
      $c_length = strlen($characters);
      $string = "";
      for ($i = 0; $i < $length; $i++) {
          $string .= $characters[rand(0, $c_length - 1)];
      }
      return $string;
  }

 // Subtracts a date from the current time and returns string: 'x (unit[s]) ago'
 // Example: '5 minutes ago'
  function convertDate($date) {
    $from = new DateTime($date);
    $diff = $from->diff(new DateTime(date('Y-m-d H:i:s')));
    return ($diff->y == 0 ? ($diff->m == 0 ? ($diff->d == 0 ? ($diff->h == 0 ? ($diff->i == 0 ? 'under a minute' : $diff->i . ' ' . ($diff->i == 1 ? 'minute' : 'minutes')) : $diff->h . ' ' . ($diff->h == 1 ? 'hour' : 'hours')) : $diff->d . ' ' . ($diff->d == 1 ? 'day' : 'days')) : $diff->m . ' ' . ($diff->m == 1 ? 'month' : 'months')) : $diff->y . ' ' . ($diff->y == 1 ? 'year' : 'years')) . ' ago';
  }


  // Instantiate with $var = new DatabaseConnection
  // No need to create a new mysqli connection, everything is handled from the class
  // Just move your function to this class and call it with $var->yourFunction();
  class DatabaseConnection extends mysqli {

    // Path to avatar and temp avatar directories
    const AVATAR_DIRECTORY = "assets/avatar";
    const TEMP_AVATAR_DIRECTORY = "assets/avatar/temp";

    // Constructor, this gets called every time a new instance of DatabaseConnection is created
    function __construct() {
      require('credentials.php');
      $instance = @parent::__construct($db_servername, $db_username, $db_password, $db_name);

      if ($this->connect_error) {
        die($this->connect_errno . $this->connect_error);
      }

      // Creates directory for avatar storage
      if (!is_dir(self::AVATAR_DIRECTORY)) {
        mkdir(self::AVATAR_DIRECTORY, 0777, true);
      }

      // Creates directory for temp avatar storage
      if (!is_dir(self::TEMP_AVATAR_DIRECTORY)) {
        mkdir(self::TEMP_AVATAR_DIRECTORY, 0777, true);
      }
    }

    function getVisibility() {
      require('credentials.php');
      $userid = self::getCurUser();
      $getVisibility = @parent::query("SELECT VISIBILITY FROM $usertable WHERE USERID = $userid");
      $row = $getVisibility->fetch_assoc();
      if ($row['VISIBILITY'] == 'VISIBLE') {
        return true;
      } else {
        return false;
      }
    }

    function getTempImgPath() {
      require('credentials.php');
      $userID = self::getCurUser();
      $getTempImgPath = @parent::query("SELECT TEMP_PATH FROM $imgtable WHERE USERID = '$userID'");
      $row = $getTempImgPath->fetch_assoc();
      return $row['TEMP_PATH'];
    }

    function getImgPath($userID) {
      require('credentials.php');
      $getImgPath = @parent::query("SELECT PATH FROM $imgtable WHERE USERID = '$userID'");
      $row = $getImgPath->fetch_assoc();
      if (!is_null($row['PATH'])) {
        return $row['PATH'];
      } else {
        return 'assets/default-avatar.png';
      }
    }

    function checkImg() {
      $msg = [];
      if (!isset($_FILES["picFile"]) or!file_exists($_FILES["picFile"]["tmp_name"])) {
        array_push($msg, 'No image selected.');
      }
      $file = $_FILES["picFile"]["tmp_name"];
      $imgsize = getimagesize($file);
      $width = $imgsize[0];
      $height = $imgsize[1];
      if ($width / $height != 1) {
        array_push($msg, 'Image must have ratio of 1:1.');
      }
      return $msg;
    }

    function createImgPath() {
      require('credentials.php');
      $filename = $_FILES["picFile"]["name"];
      $extension = pathinfo($filename, PATHINFO_EXTENSION);
      $userID = self::getCurUser();
      $tempPathTarget = self::TEMP_AVATAR_DIRECTORY . '/av_' . $userID . '.' . $extension;
      $pathTarget = self::AVATAR_DIRECTORY . '/av_' . $userID . '.' . $extension;
      $checkRows = @parent::query("SELECT * FROM $imgtable WHERE USERID = '$userID'");
      if (!$checkRows or $checkRows->num_rows == 0) {
        $result = @parent::query("INSERT INTO $imgtable (USERID) VALUES ('$userID')");
        self::createImgPath();
      } else {
        $r = @parent::query("SELECT TEMP_PATH FROM $imgtable WHERE TEMP_PATH = '$tempPathTarget' AND USERID = '$userID'");
        if ($r->num_rows > 0) {
          unlink($tempPathTarget);
          $doubleImg = true;
        } else {
          $doubleImg = false;
        }
        if (move_uploaded_file($_FILES["picFile"]["tmp_name"], $tempPathTarget)) {
          if (!is_null(self::getTempImgPath()) && !$doubleImg){
            unlink(self::getTempImgPath());
          }
          $movetoTemp = @parent::query("UPDATE $imgtable SET TEMP_PATH = '$tempPathTarget' WHERE USERID = '$userID'");
        }
      }
    }

    function createPost($userid, $title, $content) {
      require('credentials.php');
      return @parent::query("INSERT INTO $posttable (USERID, TITLE, CONTENT) VALUES ('$userid', '$title', '$content')");
    }

    function createDraft($userid, $title, $content) {
      require('credentials.php');
      return @parent::query("INSERT INTO $drafttable (USERID, TITLE, CONTENT) VALUES ('$userid', '$title', '$content')");
    }

    function addToSavedPosts($postid) {
      require('credentials.php');
      $userid = self::getCurUser();
      $getSaved = @parent::query("SELECT POSTID FROM saved WHERE POSTID = '$postid' AND USERID = '$userid'");
      if ($getSaved->num_rows == 0) {
        @parent::query("INSERT INTO saved (USERID,POSTID) VALUES ($userid,'$postid')");
      }
    }

    function postsAusgeben($order) {
      require('credentials.php');

      $order = ($order == 'ASC') ? 'ASC' : 'DESC';

      if (basename($_SERVER['PHP_SELF']) == "myposts.php" or basename($_SERVER['PHP_SELF']) == "profile.php") {
        $userID = self::getCurUser();
        $sqlQuery = "SELECT POSTID, substring(TITLE, 1, 50) AS TITLE, substring(CONTENT, 1, 200) AS CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 12, 5) AS TIME, U.USERID AS USERID, USERNAME, VISIBILITY from $posttable P, $usertable U WHERE P.USERID = U.USERID AND P.USERID = $userID ORDER BY DATE $order";
      } else if (basename($_SERVER['PHP_SELF']) == "saved.php") {
        $userID = self::getCurUser();
        $sqlQuery = "SELECT P.POSTID, substring(TITLE, 1, 50) AS TITLE, substring(CONTENT, 1, 200) AS CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 12, 5) AS TIME, U.USERID, USERNAME, VISIBILITY from $posttable P, $usertable U, saved S WHERE P.USERID = U.USERID AND P.POSTID=S.POSTID AND S.USERID=$userID ORDER BY DATE ASC";
      } else {
        $sqlQuery = "SELECT POSTID, substring(TITLE, 1, 50) AS TITLE, CONTENT, DATE, U.USERID AS USERID, USERNAME, VISIBILITY from $posttable P, $usertable U WHERE U.USERID = P.USERID ORDER BY DATE $order";
      }
      $r = @parent::query($sqlQuery);
      $return = "";
      if (!$r) {
        return 'No posts saved yet.';
      }
      while ($row = $r->fetch_assoc()){
        if ($row['VISIBILITY'] == 'VISIBLE' or self::checkSelf($row['USERID']) or self::getRole(self::getCurUser()) == 'ADMIN') {
          if ($row['VISIBILITY'] == 'VISIBLE') {
            $uname = $row['USERNAME'];
          } else {
            $uname = $row['USERNAME'] . "<span class='anon'>(anonymous)</span>";
          }
          $img = self::getImgPath($row['USERID']);
        } else {
          $uname = 'Anonymous';
          $img = 'assets/default-avatar.png';
        }
        $date = convertDate($row['DATE']);
        $return .= <<<MYSQL
        <a class='post' href='onepost.php?id=$row[POSTID]'>
            <img class='thumbnail' src='$img'>

            <div class='post-without-tn'>
              <div class='post-info'>
                <p class='title'>$row[TITLE]</p>
                <div class='date-uname'>
                  <p class='username'>
                    $uname
                  </p>
                  <p class='date'>
                    $date
                  </p>
                </div>
              </div>
              <span class='post-text md'>$row[CONTENT]</span>
            </div>
        </a>
        <hr>
MYSQL;
      }
      return $return;
    }

    function einenPostAusgeben() {
      require('credentials.php');

      $r = @parent::query("SELECT TITLE, CONTENT, substring(DATE, 1, 10) AS DAY, substring(DATE, 12, 5) AS TIME, USERNAME, U.USERID AS USERID, VISIBILITY from $posttable P, $usertable U WHERE P.USERID = U.USERID AND POSTID = $_GET[id]");

      $row = $r->fetch_assoc();
      if ($row['VISIBILITY'] == 'VISIBLE' or self::checkSelf($row['USERID']) or self::getRole(self::getCurUser()) == 'ADMIN') {
        if ($row['VISIBILITY'] == 'VISIBLE') {
          $uname = "<a href='profile?id=$row[USERID]'>$row[USERNAME]</a>";
        } else {
          $uname = "<a href='profile?id=$row[USERID]'>$row[USERNAME] <span class='anon'>(anonymous)</span></a>";
        }
      } else {
        $uname = 'Anonymous';
      }
      return <<<RETURN
      <div id='post'>
        <p id='title'>$row[TITLE]</p>
        <div id='post-info'>
          <p id='username-top'>
            posted by $uname
          </p>
          <p id='date'>
            on $row[DAY] at $row[TIME]
          </p>
        </div>
        <span id='post-text' class='md'>$row[CONTENT]</span>
      </div>
RETURN;
    }

    function draftsAusgeben() {
      require('credentials.php');
      $userid = self::getCurUser();
      $sqlQuery = "SELECT DRAFTID, substring(TITLE, 1, 50) AS TITLE, CONTENT, U.USERID AS USERID, USERNAME from $drafttable D, $usertable U WHERE U.USERID = D.USERID AND U.USERID = '$userid'";
      $r = @parent::query($sqlQuery);
      $return = "";
      if (!$r) {
        return 'No drafts saved yet.';
      }
      while ($row = $r->fetch_assoc()){
        $img = self::getImgPath($row['USERID']);
        $uname = $row['USERNAME'];
        $return .= <<<MYSQL
        <a class='post' href='onedraft.php?id=$row[DRAFTID]'>
            <img class='thumbnail' src='$img'>

            <div class='post-without-tn'>
              <div class='post-info'>
                <p class='title'>$row[TITLE]</p>
                <div class='date-uname'>
                  <p class='username'>
                    $uname
                  </p>
                </div>
              </div>
              <span class='post-text md'>$row[CONTENT]</span>
            </div>
        </a>
        <hr>
MYSQL;
      }
      return $return;
    }

    function einenDraftAusgeben() {
      require('credentials.php');

      $r = @parent::query("SELECT TITLE, CONTENT, USERNAME, U.USERID AS USERID from $drafttable D, $usertable U WHERE D.USERID = U.USERID AND DRAFTID = $_GET[id]");

      $row = $r->fetch_assoc();
      $uname = $row['USERNAME'];
      return <<<RETURN
      <div id='post'>
        <p id='title'>$row[TITLE]</p>
        <div id='post-info'>
          <p id='username-top'>
            draft by $uname
          </p>
        </div>
        <span id='post-text' class='md'>$row[CONTENT]</span>
      </div>
RETURN;
    }

    function getCurUser() {
      require('credentials.php');
      if (isset($_COOKIE['identifier'])) {
        return self::getUserID($_COOKIE['identifier']);
      } else {
        return false;
      }
    }

    function getRole($userid) {
      require('credentials.php');
      return @parent::query("SELECT ROLE FROM $usertable WHERE USERID = '$userid'")->fetch_assoc()['ROLE'];
    }

    function getUserID($identifier) {
      require('credentials.php');
      return @parent::query("SELECT USERID FROM $usertable WHERE IDENTIFIER = '$identifier'")->fetch_assoc()['USERID'];
    }

    function getUsername($userid) {
      require('credentials.php');
      return @parent::query("SELECT USERNAME FROM $usertable WHERE USERID = '$userid'")->fetch_assoc()['USERNAME'];
    }

    function deletePost() {
      require('credentials.php');
      @parent::query("DELETE FROM $posttable WHERE POSTID = $_GET[id]");
      header('Location: index');
    }

    function deleteDraft() {
      require('credentials.php');
      @parent::query("DELETE FROM $drafttable WHERE DRAFTID = $_GET[id]");
      header('Location: index');
    }

    function checkRole($userid, $role) {
      require('credentials.php');
      $getRole = @parent::query("SELECT ROLE FROM $usertable WHERE USERID = '$userid'");
      $row = $getRole->fetch_assoc();
      return $row['ROLE'] == $role ? true : false;
    }

    function checkSelf($userid) {
      require('credentials.php');
      return ($userid == self::getCurUser()) ? true : false;
    }

    function updateRole($userid, $role) {
      require('credentials.php');
      $updateRole = @parent::query("UPDATE $usertable SET ROLE = '$role' WHERE USERID = '$userid'");
      return $updateRole;
    }

    function deleteUser($userid) {
      require('credentials.php');
      $deleteUser = @parent::query("DELETE FROM $usertable WHERE USERID = $userid");
      return $deleteUser;
    }

    function createIdentifier($userid) {
      require('credentials.php');
      $identifier = randomString(32);
      $checkExist = @parent::query("SELECT IDENTIFIER FROM $usertable WHERE IDENTIFIER = '$identifier'");
      $valid = true;
      while ($row = $checkExist->fetch_assoc()) {
        if ($identifier == $row['IDENTIFIER']) {
          $valid = false;
        }
      }
      if ($valid) { //identifier doesn't exist yet
        $createIdentifier = @parent::query("UPDATE $usertable SET IDENTIFIER = '$identifier' WHERE USERID = '$userid'");
      } else { // identifier already exists
        self::createIdentifier();
      }
    }

    function getIdentifier($userid) {
      require('credentials.php');
      $getIdentifier = @parent::query("SELECT IDENTIFIER FROM $usertable WHERE USERID = '$userid'");
      $row = $getIdentifier->fetch_assoc();
      if ($row['IDENTIFIER']) {
        return $row['IDENTIFIER'];
      } else { // user doesn't have identifier yet
        self::createIdentifier($userid);
        self::getIdentifier($userid);
      }
    }

    function deleteAuthCookies() {
      foreach ($_COOKIE as $key => $val) {
        if ($key != 'theme') {
          setcookie($key, '', 1);
        }
      }
    }

    function auth() {
      require('credentials.php');
      if (isset($_COOKIE['identifier']) and isset($_COOKIE['hashed_password'])) {
        $identifier = $_COOKIE['identifier'];
        $hashed_password = $_COOKIE['hashed_password'];
        $getDBpword = @parent::query("SELECT PASSWORD FROM $usertable WHERE IDENTIFIER = '$identifier'");
        $DBpword = $getDBpword->fetch_assoc()['PASSWORD'];
        if ($hashed_password == $DBpword) {
          return true;
        }
      }
      self::deleteAuthCookies();
      return false;
    }

    function pwordRequirements($pword){
      $msg = [];
      if (strlen($pword) < 6) {
      array_push($msg, "Password must be at minimum length of 6 letters.");
      } else {
        if (ctype_upper($pword) || ctype_lower($pword)) {
          array_push($msg, "Password must contain at least one lowercase and one uppercase character.");
        }
      }
      return $msg;
    }

    function login() {
      require('credentials.php');
      $msg = [];
      if (isset($_POST["logbtn"])) {
        $uname = $_POST['uname'];
        $pword = $_POST['password'];
        if (!empty($uname) && !empty($pword)) {
          if ($getInfo = @parent::query("SELECT PASSWORD, USERID FROM $usertable WHERE USERNAME='$uname'")) {
            $row = $getInfo->fetch_assoc();
            $dbPword = $row['PASSWORD'];
            if (password_verify($pword, $dbPword)) {
              //pass
              setcookie('identifier', self::getIdentifier($row['USERID']));
              setcookie('hashed_password', $dbPword);
              header("Location: index");
              exit;
            } else {
              //invalid
              array_push($msg, "Invalid password or username");
            }
          } else {
            //query error
            array_push($msg, "Query error");
          }
        } else {
          array_push($msg, "Please enter your username and your password");
        }
      }
      return $msg;
    }

    function register() {
      require('credentials.php');
      $msg = [];
      if (isset($_POST["regbtn"])) {
        $uname = $_POST["username"];
        $pword = $_POST["password"];
        $pwordval = $_POST["passwordval"];

        $msg = self::pwordRequirements($pword);

        $r = @parent::query("SELECT USERNAME FROM $usertable WHERE UPPER(USERNAME) = UPPER('$uname')");
        if($r){
          if ($r->num_rows > 0) {
            array_push($msg, "Username already exists");
          }
        }

        if ($pword != $pwordval) {
          array_push($msg, "Passwords must match");
        }

        //no error|valid = true
        if (empty($msg)) {
          $hashed_password = password_hash($pword, PASSWORD_DEFAULT);
          if ($r = @parent::query("INSERT INTO $usertable (USERNAME,PASSWORD) VALUES ('$uname','$hashed_password')")) {
            $userid = @parent::query("SELECT USERID FROM $usertable WHERE USERNAME = '$uname'")->fetch_assoc()['USERID'];
            self::createIdentifier($userid);
            setcookie('identifier', self::getIdentifier($userid));
            setcookie('hashed_password', $hashed_password);
            header('Location: index');
            exit;
          } else {
            array_push($msg, "Query error");
          }
        }
      }
      return $msg;
    }
  }

?>
