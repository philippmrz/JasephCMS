<?php
// PHP file for all backend logic, includes a class for all things DatabaseConnection

  function isLoggedIn(){
    return (isset($_COOKIE['logcheck'])) ? true : false;
  }

  function invertSortOrder() {
    return (isset($_GET['sort']) and $_GET['sort'] == 'ASC') ? "DESC" : "ASC";
  }

  function getSortSVG() {
    return (isset($_GET['sort']) and $_GET['sort'] == 'ASC') ? '<svg rotate="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z"/></svg>' : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z"/></svg>';
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
    // constructor, this gets called every time a new instance of DatabaseConnection is created
    function __construct() {
      require('credentials.php');
      $instance = @parent::__construct("$servername", "$username", "$password", "$dbname");

      if ($this->connect_error) {
        die($this->connect_errno . $this->connect_error);
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
      $getTempImgPath = @parent::query("SELECT TEMP_PATH FROM $imgtable WHERE USERID = '$userID'");
      return $getTempImgPath->fetch_assoc()['TEMP_PATH'];
    }

    function getImgPath($userID) {
      require('credentials.php');
      $getImgPath = @parent::query("SELECT PATH FROM $imgtable WHERE USERID = '$userID'");
      if ($getImgPath->num_rows == 0 or is_null($getImgPath->fetch_assoc()['PATH'])) {
        return 'assets/default-avatar.png';
      } else {
        return $getImgPath->fetch_assoc()['PATH'];
      }
    }

    /*function getImgPath($userID) {
      require('credentials.php');
      $getImgPath = @parent::query("SELECT PATH FROM $imgtable WHERE USERID = '$userID'");
      if ($getImgPath->num_rows == 0 or is_null($getImgPath->fetch_assoc()['PATH'])) {
        return 'assets/default-avatar.png';
      } else {
        return $getImgPath->fetch_assoc()['PATH'];
      }
    }*/

    function createImgPath() {
      require('credentials.php');
      $msg = [];
      if (!isset($_FILES["picFile"]) or!file_exists($_FILES["picFile"]["tmp_name"])) {
        return;
      }
      $file = $_FILES["picFile"]["tmp_name"];
      $imgsize = getimagesize($file);
      $width = $imgsize[0];
      $height = $imgsize[1];
      if ($width / $height != 1) {
        array_push($msg, 'Image must have ratio of 1:1.');
        return $msg;
      }

      $avatarDirectory = "assets/avatar/";
      $tempAvatarDirectory = "assets/avatar/temp/";
      $filename = $_FILES["picFile"]["name"];
      $extension = pathinfo($filename, PATHINFO_EXTENSION);
      $userID = self::getUserID();
      $pathTarget = $tempAvatarDirectory . 'av_' . $userID . '.' . $extension;

      $checkRows = @parent::query("SELECT * FROM $imgtable WHERE USERID = '$userID'");
      if ($checkRows->num_rows == 0) {
        $result = @parent::query("INSERT INTO $imgtable (USERID) VALUES ('$userID')");
        self::createImgPath();
      } else {
        $r = @parent::query("SELECT TEMP_PATH FROM $imgtable WHERE TEMP_PATH = '$pathTarget' AND USERID = '$userID'");
        if ($r->num_rows > 0) {
          unlink($pathTarget);
          $doubleImg = true;
        } else {
          $doubleImg = false;
        }
        if (move_uploaded_file($_FILES["picFile"]["tmp_name"], $pathTarget)) {
          if (!is_null(self::getTempImgPath()) && !$doubleImg){
            unlink(self::getTempImgPath());
          }
          $movetoTemp = @parent::query("UPDATE $imgtable SET TEMP_PATH = '$pathTarget' WHERE USERID='$userID'");
        }
      }
      return $pathTarget;
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

      $userID = self::getUserID();
      if (basename($_SERVER['PHP_SELF']) == "myposts.php") {
        $sqlQuery = "SELECT POSTID, substring(TITLE, 1, 50) AS TITLE, substring(CONTENT, 1, 200) AS CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 12, 5) AS TIME, U.USERID AS USERID, USERNAME, VISIBILITY from $posttable P, $usertable U WHERE P.USERID = U.USERID AND P.USERID = $userID ORDER BY DATE $order";
      } else if (basename($_SERVER['PHP_SELF']) == "saved.php") {
        $sqlQuery = "SELECT P.POSTID, substring(TITLE, 1, 50) AS TITLE, substring(CONTENT, 1, 200) AS CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 12, 5) AS TIME, U.USERID, USERNAME from post P,user U, saved S WHERE P.USERID = U.USERID AND P.POSTID=S.POSTID AND S.USERID=$userID ORDER BY DATE ASC";
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
      return @parent::query("SELECT ROLE FROM user WHERE USERNAME = '$_COOKIE[uname]'")->fetch_assoc()[ROLE];
    }

    function deletePost() {
      @parent::query("DELETE FROM post WHERE POSTID = $_GET[id]");
      header('Location: index');
    }

    function getUserID() {
      return @parent::query("SELECT USERID FROM user WHERE USERNAME = '$_COOKIE[uname]'")->fetch_assoc()['USERID'];
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
