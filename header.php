<?php
include 'session.php';
include 'functions.php';
include 'dbFunctions.php';
if(!isset($active)) $active=''; 
if(!isset($message)) $message=''; //per alert

redirectHttps();

/* inactivity handle*/

$inactivity = checkInactivity();

/* login handle */

if(checkCookie())
    $message .= loginHandle();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.css" rel=stylesheet type="text/css">
    <link href="bootstrap/css/bootstrap-clockpicker.css" rel=stylesheet type="text/css">
    <link href="style.css" rel=stylesheet type="text/css">
    <script type="text/javascript" src="jquery-2.2.4.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap-clockpicker.js"></script>
    <script type="text/javascript" src="myfunctions.js"></script>
    <title>3D Printer Reservation System</title>
</head>
<body>
<!-- wrapper for sticky footer -->
<div class="page-wrap container">

    <!-- NAVBAR -->

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">3D Printer Reservation System</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if (!userLogged())
                    {
                        printNavbarLogin();
                    } else
                    {
                        printNavbarLogged();
                    }
                    ?>
                </ul>

            </div>
        </div>
    </nav>

    <!-- CONTAINER -->

    <div class="container-fluid">
        <div class="row row-offcanvas row-offcanvas-left">

            <!-- SIDEBAR -->
            <div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation" >

                <ul class="nav nav-sidebar">
                    <li <?php if($active == 'index') echo 'class="active"';?>><a href="index.php">
                            <span class="glyphicon glyphicon-list" aria-hidden="true"></span>&nbsp;&nbsp;All reservations</a></li>
                    <li <?php if($active == 'myarea') echo 'class="active"';?>><a href="myarea.php">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;My reservations</a></li>
                    <li <?php if($active == 'prenotazione') echo 'class="active"';?>><a href="reservation.php">
                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;&nbsp;New reservation</a></li>
                </ul>

            </div><!--/span-->

            <!-- MAIN CONTENT -->
            <div class="col-sm-9 col-md-10 main" >

                <!-- cookie checking -->

                <div style="display:none" id="main_alert" class="container alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </div>

                <!-- javascript checking -->
                <noscript>
                    <div style="text-align: center" class="container alert alert-danger">
                        <p>For the best user experience Javascript is required to load this page. Please activate it into your browser settings and reload the page</p>
                    </div>
                </noscript>
                
                <?php
                    /* in case of expired session print alert and terminate visualization */

                    if($inactivity){
                        printAlert("Expired Session, please log in again");
                        include 'footer.php';
                        exit;
                    }

                    /* alert for login errors*/
                    if($message == 'loginOk')
                        printSuccess("Welcome $_SESSION[user_nome] $_SESSION[user_cognome]");
                    else
                        printAlert($message);
                ?>
                
                <!--toggle sidebar button-->
                <p <?php if($active == 'registrazione') echo 'style=display:none' ?> class="visible-xs">
                    <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas"><i class="glyphicon glyphicon-chevron-left"></i></button>
                </p>

                <div class="center">

                


