<?php

$maxstrlen = 30;

function isPositiveInt($i) {
    if (!is_numeric($i) || $i < 1 || $i != round($i)) {
        return false;
    } else
        return true;
}


function controllaNome($name){
    global $maxstrlen;

    if(preg_match("/^[a-zA-Z ]*$/",$name) && strlen($name) < $maxstrlen)
        return true;
    else
        return false;
}

function controllaMail($email){
    global $maxstrlen;

    if(filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email < $maxstrlen))
        return true;
    else
        return false;
}

function controllaTempo($time){

    if(preg_match("#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#", $time))
        return true;
    else
        return false;
}

function controllaPassword($password){
    if (strlen($password) < 2 || strlen($password) > 30)
        return false;
    else
        return true;
}

function matchPassword($pass1,$pass2) {
	if($pass1 != $pass2)
		return false;
	else 
		return true;
}

function controllaNewPrenotazione($inizio, $durata){

    $mess = '';

    if(!isPositiveInt($durata))
        $mess .= '<li>Duration not valid</li>';

    if(!controllaTempo($inizio))
        $mess .= '<li>Start time not valid</li>';

    if($mess == '')
        return 'ok';
    else
        return $mess;

}

function controllaNewRegistrazione($nome,$cognome,$email,$password,$confPassword){

    $mess = '';

    if(!controllaNome($nome))
        $mess .= '<li>Name is not valid</li>';

    if(!controllaNome($cognome))
        $mess .= '<li>Surname is not valid</li>';

    if(!controllaMail($email))
        $mess .= '<li>Email address not valid</li>';

    if(!controllaPassword($password))
        $mess .= '<li>Password not valid</li>';
	
	if(!matchPassword($password,$confPassword))
		$mess .= '<li>Passwords do not match</li>';

    if($mess == '')
        return 'ok';
    else
        return $mess;

}