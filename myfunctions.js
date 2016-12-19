/* For the sidebar */

$(document).ready(function() {
    $('[data-toggle=offcanvas]').click(function() {
        $('.row-offcanvas').toggleClass('active');
    });
});


$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});

/* for cookies enabled */

function showCookiesMessage(cookiesEnabled) {
    if (cookiesEnabled != 'true'){
        $('#main_alert').html("Il sito richiede i cookies per funzionare correttamente.<br>" +
            "Si prega di abilitarli dalle impostazioni del browser");

        $('#main_alert').show();

        $('.center').hide();
    }

}

$(document).ready(function() {
    var jqxhr = $.get('cookiesEnabled.php');
    jqxhr.done(showCookiesMessage);
});

/* input validation */

function showAlert(message){
    $('#main_alert').html(message);
    $('#main_alert').show();
}

function passwordMatches(pass1,pass2) {
    if(pass1 == pass2)
        return true;
    else
        return false;
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if( re.test(email) && (email.length < 30))
        return true;
    else
        return false;
}

function validateName(name) {
    var re = /^[a-zA-Z ]*$/;
    if( re.test(name) && (name.length < 30))
        return true;
    else
        return false;

}

function validatePassword(password){
    if (password.length < 2 || password.length > 30)
        return false;
    else
        return true;
}

function isPositiveInteger(n) {
    return n >>> 0 === parseFloat(n);
}

function validateTime(time){
    var re = /([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}/;
    return re.test(time);
}

function controlloRegistrazione(){
	
	$('#error-alert').hide();

    var pass1 = document.getElementById('Password').value;
    var pass2 = document.getElementById('ConfermaPassword').value;
    var email = document.getElementById('Email').value;
    var nome = document.getElementById('Nome').value;
    var cognome = document.getElementById('Cognome').value;

    var mess = '';

    if(pass1 == '' || pass2 == '' || email == '' || nome == '' || cognome == '') {
        mess += '<li>All fields are required</li>'
        showAlert('<ul>' + mess + '</ul>');
        return false;
    }

    if(!validateName(nome))
        mess += '<li>Name not valid</li>';
    if(!validateName(cognome))
        mess += '<li>Surname not valid</li>';

    if(!validateEmail(email))
        mess += '<li>Email address not valid</li>';

    if(!validatePassword(pass1))
        mess += '<li>Password lenght must be from 2 to 30 characters</li>'

    if(!passwordMatches(pass1,pass2))
        mess += '<li>Passwords do not match</li>'

    if (mess == '')
        return true;
    else
        showAlert('<ul>' + mess + '</ul>');
        return false;
}

function controlloPrenotazione(){

    $('#success-alert').hide();
	$('#error-alert').hide();

    var inizio = document.getElementById('timepicker').value;
    var durata = document.getElementById('durata').value;

    var mess = '';

    if(inizio == '') {
        mess += '<li>Not valid start time</li>'
        showAlert('<ul>' + mess + '</ul>');
        return false;
    }
	
	if(durata == '' ) {
        mess += '<li>Duration is not valid</li>'
        showAlert('<ul>' + mess + '</ul>');
        return false;
    }

    if(!validateTime(inizio))
        mess += '<li>Start time not valid</li>';
    if(!isPositiveInteger(durata))
        mess += '<li>Duration not valid</li>';

    if (mess == '')
        return true;
    else
        showAlert('<ul>' + mess + '</ul>');
    return false;
}