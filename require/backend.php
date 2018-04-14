<?php
// PHP file for all backend logic, includes a class for all things DatabaseConnection

  function isLoggedIn(){
    return (isset($_COOKIE['logcheck'])) ? true : false;
  }

  function invertSortOrder() {
    return (isset($_GET['sort']) and $_GET['sort'] == 'ASC') ? "DESC" : "ASC";
  }

  function getSVG($svg) {
      switch($svg) {
        case 'sort':
        return 'M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z';
        break;

        case 'home':
        return 'M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z';
        break;

        case 'newpost':
        return 'M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z';
        break;

        case 'saved':
        return 'M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2z';
        break;

        case 'myposts':
        return 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z';
        break;

        case 'settings':
        return 'M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z';
        break;

      }
  }

  function getSortSVG() {
    $rotate = (isset($_GET['sort']) and $_GET['sort'] == 'ASC') ? 'true' : 'false';
    $path = getSVG('sort');
    $svg = "<svg rotate='$rotate' xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path d='$path'/></svg>";
    return $svg;
  }

  function randomString($length) {
      $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!$.";
      $c_length = strlen($characters);
      $string = "";
      for ($i = 0; $i < $length; $i++) {
          $string .= $characters[rand(0, $c_length - 1)];
      }
      return $string;
  }



  // instanciate with $var = new DatabaseConnection
  // No need to create a new mysqli connection, everything is handled from the class
  // just move your function to this class and call it with $var->yourFunction();
  class DatabaseConnection extends mysqli {

    const AVATAR_DIRECTORY = "assets/avatar";
    const TEMP_AVATAR_DIRECTORY = "assets/avatar/temp";

    // constructor, this gets called every time a new instance of DatabaseConnection is created
    function __construct() {
      require('credentials.php');
      $instance = @parent::__construct("$servername", "$username", "$password", "$dbname");

      if ($this->connect_error) {
        die($this->connect_errno . $this->connect_error);
      }
      if (!is_dir(self::AVATAR_DIRECTORY)) {
        mkdir(self::AVATAR_DIRECTORY, 0777, true);
      }
      if (!is_dir(self::TEMP_AVATAR_DIRECTORY)) {
        mkdir(self::TEMP_AVATAR_DIRECTORY, 0777, true);
      }
    }

    function getVisibility() {
      require('credentials.php');
      $getVisibility = @parent::query("SELECT VISIBILITY FROM user WHERE USERNAME = '$_COOKIE[uname]'");
      $row = $getVisibility->fetch_assoc();
      if ($row['VISIBILITY'] == 'VISIBLE') {
        return true;
      } else {
        return false;
      }
    }

    function getTempImgPath() {
      require('credentials.php');
      $userID = self::getUserID();
      $getTempImgPath = @parent::query("SELECT TEMP_PATH FROM $imgtable WHERE USERID = $userID");
      $row = $getTempImgPath->fetch_assoc();
      return $row['TEMP_PATH'];
    }

    function getImgPath($userID) {
      require('credentials.php');
      $getImgPath = @parent::query("SELECT PATH FROM $imgtable WHERE USERID = $userID");
      if ($row = $getImgPath->fetch_assoc()) {
        return $row['PATH'];
      } else {
        return 'assets/default-avatar.png';
      }
    }

    function checkImg() {
      $msg = [];
      if (!isset($_FILES["picFile"]) or!file_exists($_FILES["picFile"]["tmp_name"])) {
        array_push($msg, 'No image selected.');
        return $msg;
      }
      $file = $_FILES["picFile"]["tmp_name"];
      $imgsize = getimagesize($file);
      $width = $imgsize[0];
      $height = $imgsize[1];
      if ($width / $height != 1) {
        array_push($msg, 'Image must have ratio of 1:1.');
        return $msg;
      }
      return true;
    }

    function createImgPath() {
      require('credentials.php');
      $filename = $_FILES["picFile"]["name"];
      $extension = pathinfo($filename, PATHINFO_EXTENSION);
      $userID = self::getUserID();
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
          $movetoTemp = @parent::query("UPDATE $imgtable SET TEMP_PATH = '$tempPathTarget' WHERE USERID='$userID'");
        }
      }
    }


    function addToSavedPosts() {
      $userid = self::getUserID();
//      if (@parent::query("SELECT FROM saved WHERE POSTID=$_GET[id]")->fetch_assoc()) {
        @parent::query("INSERT INTO saved(USERID,POSTID) VALUES($userid,$_GET[id])");
  //    }
    }

    function postsAusgeben($order) {
      require('credentials.php');

      $order = ($order == 'ASC') ? 'ASC' : 'DESC';

      if (basename($_SERVER['PHP_SELF']) == "myposts.php") {
        $userID = self::getUserID();
        $sqlQuery = "SELECT POSTID, substring(TITLE, 1, 50) AS TITLE, substring(CONTENT, 1, 200) AS CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 12, 5) AS TIME, U.USERID AS USERID, USERNAME, VISIBILITY from $posttable P, $usertable U WHERE P.USERID = U.USERID AND P.USERID = $userID ORDER BY DATE $order";
      } else if (basename($_SERVER['PHP_SELF']) == "saved.php") {
        $userID = self::getUserID();
        $sqlQuery = "SELECT P.POSTID, substring(TITLE, 1, 50) AS TITLE, substring(CONTENT, 1, 200) AS CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 12, 5) AS TIME, U.USERID, USERNAME from $posttable P, $usertable U, saved S WHERE P.USERID = U.USERID AND P.POSTID=S.POSTID AND S.USERID=$userID ORDER BY DATE ASC";
      } else {
        $sqlQuery = "SELECT POSTID, substring(TITLE, 1, 50) AS TITLE, CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 12, 5) AS TIME, U.USERID AS USERID, USERNAME, VISIBILITY from $posttable P, $usertable U WHERE U.USERID = P.USERID ORDER BY DATE $order";
      }
      $r = @parent::query($sqlQuery);
      $return = "";
      if (!$r) {
        return 'No posts saved yet.';
      }
      while ($row = $r->fetch_assoc()){
        $img = ($row['VISIBILITY'] == 'VISIBLE' ? self::getImgPath($row['USERID']) : 'assets/default-avatar.png');
        $uname = ($row['VISIBILITY'] == 'VISIBLE' ? $row['USERNAME'] : 'Anonymous');
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
                    on $row[DAY] at $row[TIME]
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
      $uname = ($row['VISIBILITY'] == 'VISIBLE' ? $row['USERNAME'] : 'Anonymous');
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

    function getUserRole() {
      require('credentials.php');
      $uname = $_COOKIE['uname'];
      return @parent::query("SELECT ROLE FROM $usertable WHERE USERNAME = '$uname'")->fetch_assoc()['ROLE'];
    }

    function getRole($userid) {
      require('credentials.php');
      return @parent::query("SELECT ROLE FROM $usertable WHERE USERID = '$userid'")->fetch_assoc()['ROLE'];
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

    function getUserID() {
      require('credentials.php');
      $uname = $_COOKIE['uname'];
      return @parent::query("SELECT USERID FROM $usertable WHERE USERNAME = '$uname'")->fetch_assoc()['USERID'];
    }

    function checkRole($userid, $role) {
      require('credentials.php');
      $getRole = @parent::query("SELECT ROLE FROM $usertable WHERE USERID = '$userid'");
      $row = $getRole->fetch_assoc();
      return $row['ROLE'] == $role ? true : false;
    }

    function checkSelf($userid) {
      require('credentials.php');
      return ($userid == self::getUserID()) ? true : false;
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

    function login() {
      require('credentials.php');
      $msg = [];
      if (isset($_POST["logbtn"])) {
        $uname = $_POST["uname"];
        $pword = $_POST["pword"];
        if (!empty($uname) && !empty($pword)) {
          if ($r = @parent::query("SELECT PASSWORD,USERNAME FROM $usertable WHERE USERNAME='$uname'")) {
            $row = $r->fetch_assoc();
            $dbP = $row["PASSWORD"];
            if (password_verify($pword, $dbP)) {
              //pass
              if (isset($_POST["stay_li"])) { //when checking "RM"
                $identifier = randomString(32);
                $token = randomString(32);
                $hashToken = hash("sha256", $token);
                $r = @parent::query("UPDATE $usertable SET IDENTIFIER = '$identifier', TOKEN = '$hashToken' WHERE USERNAME = '$uname'");
                setcookie("identifier","$identifier",time() + 86400 * 365);
                setcookie("token","$token",time() + 86400 * 365);
              }
              setcookie("logcheck","true");
              setcookie("uname","$uname");
              header("Location: index");
              exit;
            } else {
              //invalid
              array_push($msg, "Invalid password or username");
            }
          } else {
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
        unset($_COOKIE["identifier"]);
        unset($_COOKIE["token"]);
        $uname = $_POST["uname"];
        $pword = $_POST["pword"];
        $pwordval = $_POST["pwordval"];
        $valid = true;

        if (strlen($pword) < 6) {
        array_push($msg, "Password must be at minimum length of 6 letters");
          $valid = false;
        } else {
          if (ctype_upper($pword) || ctype_lower($pword)) {
            array_push($msg, "Password must contain at least one lowercase and one uppercase character");
            $valid = false;
          }
        }

        $r = @parent::query("SELECT USERNAME FROM $usertable WHERE UPPER(USERNAME) = UPPER('$uname')");
        if($r){
          if ($r->num_rows > 0) {
            array_push($msg, "Username already exists");
            $valid = false;
          }
        }

        if ($pword != $pwordval) {
          array_push($msg, "Passwords must match");
          $valid = false;
        }

        if ($valid) {
          $pword = password_hash($pword, PASSWORD_DEFAULT);
          if ($r = @parent::query("INSERT INTO $usertable (USERNAME,PASSWORD) VALUES ('$uname','$pword')")) {
            setcookie("logcheck","true");
            setcookie("uname", $uname);
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
