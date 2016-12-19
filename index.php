<?php

$active = 'index';
include 'header.php';


?>



    <div class="heading panel panel-default">
        <div class="panel-body">
            <h4><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>&nbsp;&nbsp;List of today reservations</h4>
        </div>
    </div>




<?php
mostraPrenotazioniTutte();
?>



<?php
include 'footer.php';
?>