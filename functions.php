<?php

function sanitizeString($var)
{
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $var;
}

function redirectHttps(){
    if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
        $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $redirect);
        exit();
    }

}


function printAlert($message){
    if(isset($message) && ($message != '')){
        echo '<BR>';
        echo '<div id="error-alert" style="max-width: 550px;" class="container alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><ul>'.$message.'</div></ul>';
    }
}

function printSuccess($message){
    if(isset($message) && ($message != '')){
        echo '<div id="success-alert" style="max-width: 550px;" class="container alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$message.'</div>';
    }
}

function checkCookie(){
    if(!isset($_COOKIE['cookieCheck'])){
        return false;
    }
    else return true;
}

function redirectAfterReg(){
    echo '<script>
    $(document).ready(function () {
        // Handler for .ready() called.
        window.setTimeout(function () {
            location.href = "index.php";
        }, 3000);
    });
</script>';
}

function printNavbarLogin(){
    echo "<form id=\"signin\" action='index.php' class=\"navbar-form navbar-right\" role=\"form\" method=\"post\">

                        <div class=\"input-group\">
                            <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-user\"></i></span>
                            <input id=\"email\" type=\"email\" class=\"form-control\" name=\"email\" value=\"\" placeholder=\"Email Address\" required>
                        </div>

                        <div class=\"input-group\">
                            <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-lock\"></i></span>
                            <input id=\"password\" type=\"password\" class=\"form-control\" name=\"password\" value=\"\" placeholder=\"Password\" required>
                        </div>

                        <button type=\"submit\" class=\"btn btn-primary\"><span><i class=\"glyphicon glyphicon-log-in\"></i></span>&nbsp;Login</button>
                    </form>
                    <li>
                        <form action=\"registration.php\" >
                            <button class=\"btn btn-primary navbar-btn\">Register</button>
                        </form>
                    </li>";
}

function printNavbarLogged(){
    echo "<p class=\"navbar-text\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$_SESSION[user_nome] $_SESSION[user_cognome]</p>
                    <li>
                        <form id='signout' action=\"logout.php\">
                            <button class=\"btn btn-primary navbar-btn\"><span><i class=\"glyphicon glyphicon-log-out\"></i></span>&nbsp;Logout</button>
                        </form>
                    </li>";
}

