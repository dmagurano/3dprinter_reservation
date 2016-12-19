<?php

$active = 'prenotazione';

include 'header.php';

if(!userLogged()){
    printAlert("To access this area you need to log in");
    include 'footer.php';
    exit;
}

gestioneInserimento();

?>


    <!-- FORM INSERIMENTO -->
    <div class="heading panel panel-default">
        <div class="panel-body">
            <h4><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;&nbsp;New reservation</h4>
        </div>
    </div>

    <div class="panel region-table panel-default" style="border-radius:0px">
        <div class="panel-body">
            <form id="reg-form" method="post" action="#" role="form">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="timepicker">Insert start time..</label>
                        <div class="input-group clockpicker">
                            <input id="timepicker" name="inizio" type="text" class="form-control" maxlength="5" placeholder="hh:mm" required>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                        </div>
                        <script type="text/javascript">
                            $('.clockpicker').clockpicker({
                                donetext: 'Ok',
                                placement: 'right'
                            });
                        </script>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="durata">Duration (min)</label>
                        <input name="durata" id="durata" type="number" min="1" maxlength="50" class="form-control" placeholder="Insert Duration (>= 1)" required>
                    </div>

                    <div class="form-group">
                        <button id="Submit" type="submit" onclick="return controlloPrenotazione()" class="btn btn-default ">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>






    <!-- LISTA PREN -->

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



