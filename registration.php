<?php


include 'header.php';

gestioneRegistrazione();


?>


    <div class="container reg-panel">
        <div class="row">
            <div class="region-table">
                <div class="panel-body">
                    <form id="reg-form" method="post" action="registration.php" role="form">
                        <div class="form-group">
                            <h2>Create an account</h2>
                            <p><small>You need one to do a reservation</small></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="Nome">Name</label>
                            <input name='nome' id="Nome" type="text" maxlength="30" class="form-control" placeholder="Insert name... (max 30 chars.)" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="Cognome">Surname</label>
                            <input name="cognome" id="Cognome" type="text" maxlength="30" class="form-control" placeholder="Insert surname... (max 30 chars.)" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="Email">Email (your username)</label>
                            <input name="regEmail" id="Email" type="email" maxlength="30" class="form-control" placeholder="Insert email... (max 30 chars.)" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="Password">Password</label>
                            <input name="regPassword" id="Password" type="password" maxlength="30" class="form-control"
                                   data-toggle="tooltip" title="Password length must be from 2 and 30 characters" placeholder="Insert password..." length="40" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="ConfermaPassword">Confirm password</label>
                            <input id="ConfermaPassword" name="confPassword" type="password" maxlength="30" class="form-control" placeholder="Confirm password..." required>
                        </div>
                        <div class="form-group">
                            <button id="Submit" type="submit" onclick="return controlloRegistrazione()" class="btn btn-primary btn-block">Register</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

<?php

include 'footer.php';

?>