<?php

$active = 'myarea';

include 'header.php';

if(!userLogged()){
    printAlert("To access this area you need to login");
    include 'footer.php';
    exit;
}

gestioneCancellaPrenotazione();

?>



    <div class="heading panel panel-default">
        <div class="panel-body">
            <h4><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>&nbsp;&nbsp;List of user's reservations</h4>
        </div>
    </div>




<?php
    mostraPrenotazioniUtente();
?>



<?php
include 'footer.php';
?>