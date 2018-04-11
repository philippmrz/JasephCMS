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
      @parent::__construct("$servername", "$username", "$password", "$dbname");

      if ($this->connect_error) {
        die($this->connect_errno . $mysqli->connect_error);
      }
    }

    function postsAusgeben($order) {
      require('credentials.php');

      $order = ($order == 'ASC') ? 'ASC' : 'DESC';

      $userID = self::getUserID();
      if (basename($_SERVER['PHP_SELF']) == "myposts.php")
        $sqlQuery = "SELECT POSTID, substring(TITLE, 1, 50) AS TITLE, substring(CONTENT, 1, 200) AS CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 11, 19) AS TIME, USERNAME from $posttable, $usertable WHERE $posttable.USERID = $usertable.USERID AND $posttable.USERID = $userID ORDER BY DATE $order";

      else
        $sqlQuery = "SELECT POSTID, substring(TITLE, 1, 50) AS TITLE, CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 11, 19) AS TIME, USERNAME from $posttable, $usertable WHERE $posttable.USERID = $usertable.USERID ORDER BY DATE $order";

      $r = @parent::query($sqlQuery);

      $return = "";
      while ($row = $r->fetch_assoc()){
        $return .= <<<MYSQL
        <a class='post' href='onepost.php?id=$row[POSTID]'>
            <img class='thumbnail' src='assets/dummy-thumbnail.png'>

            <div class='post-without-tn'>
              <div class='post-info'>
                <p class='title'>$row[TITLE]</p>
                <div class='date-uname'>
                  <p class='username'>
                    $row[USERNAME]
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

      $r = @parent::query("SELECT TITLE, CONTENT, substring(DATE, 1, 10) AS DAY, substring(DATE, 11, 19) AS TIME, USERNAME from $posttable, $usertable WHERE $posttable.USERID = $usertable.USERID AND POSTID = $_GET[id]");

      $row = $r->fetch_assoc();
      return <<<RETURN
      <div id='post'>
        <p id='title'>$row[TITLE]</p>
        <div id='post-info'>
          <p id='username-top'>
            posted by $row[USERNAME]
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
            array_push($msg, "query error");
          }
        } else {
          array_push($msg, "Please enter your username and your password");
        }
      }
    }

    function register() {
      if (isset($_POST["regbtn"])) {
        unset($_COOKIE["identifier"]);
        unset($_COOKIE["token"]);
        $uname = $_POST["uname"];
        $pword = $_POST["pword"];
        $pwordval = $_POST["pwordval"];
        $msg = [];

        //invalid inputs
        if (empty($uname)) {
          array_push($msg, "Please enter a username");
        }

        if (empty($pword)) {
          array_push($msg, "Please enter a password");
        }

        if (strlen($pword) < 6) {
          array_push($msg, "Password must be at minimum length of 6 letters");
        } else {
          if (ctype_upper($pword) || ctype_lower($pword)) {
            array_push($msg, "Password must contain at least one lowercase and one uppercase character");
          }
        }

        $r = @parent::query("SELECT USERNAME FROM $usertable WHERE UPPER(USERNAME) = UPPER('$uname')");
        if($r){
          if ($r->num_rows > 0) {
            array_push($msg, "Username already exists");
          }
        }

        if ($pword != $pwordval) {
          array_push($msg, "Passwords must match");
        }

        if (count($msg) == 0) {
          $pword = password_hash($pword, PASSWORD_DEFAULT);
          if ($r = @parent::query("INSERT INTO $usertable (USERNAME,PASSWORD) VALUES ('$uname','$pword')")) {
            setcookie("logcheck","true");
            setcookie("uname", $uname);
            header('Location: index');
            exit;
          } else {
            echo "Query error";
          }
        }
      }
    }
  }
?>
