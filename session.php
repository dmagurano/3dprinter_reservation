<?php
session_start();

function userLogged(){
    if (isset($_SESSION['user_id'])){
        return true;
    } else
        return false;
}

function checkInactivity(){
  if(userLogged()){
      $t=time();
      $diff=0;

      if (isset($_SESSION['time'])){
          $t0=$_SESSION['time'];
          $diff=($t-$t0);  // inactivity period
      }

      if ($diff > 120) { // new or with inactivity period too long
          endSession();
          /*header('HTTP/1.1 307 temporary redirect');
          header('Location: index.php?SessionTimeout');
          // redirect client to login page
          exit; // IMPORTANT to avoid further output from the script*/
          return true;

      } else {
          $_SESSION['time']=time(); /* update time */
            return false;
      }
  }
}

function endSession(){
    $_SESSION=array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600*24,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();  // destroy session
}