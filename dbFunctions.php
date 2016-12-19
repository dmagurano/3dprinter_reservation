<?php

include 'printers.php';
include 'validation.php';


function dbConnect() {
    $dbhost  = 'localhost';
    $dbname  = 'prenotazione_macchine';
    $dbuser  = 'root';
    $dbpass  = '';
    try{
        $conn = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        if (!$conn)
            throw new Exception("Error connecting to the database, please try again later");

        return $conn;
    } catch (Exception $e) {
        return false;
    }

}

function myQuery($conn, $query){
    return @mysqli_query($conn, $query);
}

function dbClose($conn){
    return @mysqli_close($conn);
}

function myFetchArray($result){
    return @mysqli_fetch_array($result);
}

function sanitizeStringSQL($conn,$var)
{
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return @mysqli_real_escape_string($conn,$var);
}

function loginHandle(){
    if(isset($_REQUEST['email']) && isset($_REQUEST['password'])){

        try {
			if($_REQUEST['email'] == '' || $_REQUEST['password'] == '')
				throw new Exception("Email or password fields cannot be empty");
		
            $conn = dbConnect();
            if (!$conn)
                throw new Exception("Error connecting to the database, please try again later");

            /* validating input */
            $email = sanitizeStringSQL($conn, $_REQUEST['email']);
            $password = sanitizeStringSQL($conn,$_REQUEST['password']);
            

            $query = "SELECT id, nome, cognome from users WHERE email='$email' AND password=SHA1('$password')";

            $result = myQuery($conn, $query);
            if (!$result)
                throw new Exception("Error connecting to the database, please try again later");
            if (mysqli_num_rows($result) == 0) {
                throw new Exception("Incorrect Email or password");
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        $riga = myFetchArray($result);

        $_SESSION['user_id'] = $riga['id'];
        $_SESSION['user_nome'] = $riga['nome'];
        $_SESSION['user_cognome'] = $riga['cognome'];

        mysqli_free_result($result);
        dbClose($conn);

        return 'loginOk';


    }

}

function mostraPrenotazioniTutte(){


    $query = "SELECT id_macchina, TIME_FORMAT(inizio, '%H:%i' ), durata FROM prenotazioni ORDER BY inizio";

    try{
        $conn = dbConnect();
        if (!$conn)
            throw new Exception("Error connecting to the database, please try again later");
        $result = myQuery($conn, $query);
        if (!$result)
            throw new Exception("Error connecting to the database, please try again later");
        if (mysqli_num_rows($result) == 0)
            throw new Exception("There are no reservation to show");

    } catch (Exception $e) {
        printAlert($e->getMessage());
        dbClose($conn);
        include 'footer.php';
        exit;
    }

    echo '<div class="region-table">';

    echo "<table class=\"table table-striped\">
        <thead>
        <tr>
            <th>Start Time (h:m)</th>
            <th>End Time (h:m)</th>
            <th>Duration (min)</th>
            <th>Printer</th>
        </tr>
        </thead>
        <tbody>
        ";

    while($riga = myFetchArray($result)){

        $orafine = strftime("%H:%M", strtotime($riga['1']) + 60*$riga['durata']);

        echo"<tr>
            <td>$riga[1]</td>
            <td>$orafine</td>
            <td>$riga[durata]</td>
            <td>$riga[id_macchina]</td>
        </tr>";
    }

    echo "</tbody>
          </table>
          </div>";

    mysqli_free_result($result);
    dbClose($conn);
}

function mostraPrenotazioniUtente(){

    $query = "SELECT id_macchina, TIME_FORMAT(inizio, '%H:%i' ), durata, id FROM prenotazioni WHERE id_utente = $_SESSION[user_id] ORDER BY inizio";

    try{
        $conn = dbConnect();
        if (!$conn)
            throw new Exception("Error connecting to the database, please try again later");
        $result = myQuery($conn, $query);
        if (!$result)
            throw new Exception("Error connecting to the database, please try again later");
        if (mysqli_num_rows($result) == 0)
            throw new Exception("There are no reservation to show");

    } catch (Exception $e) {
        printAlert($e->getMessage());
        dbClose($conn);
        include 'footer.php';
        exit;
    }

    echo '<div class="region-table">';



    echo "<table class=\"table table-striped\">
        <thead>
        <tr>
            <th>Start Time (h:m)</th>
            <th>End Time (h:m)</th>
            <th>Duration (min)</th>
            <th>Printer</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        ";

    while($riga = myFetchArray($result)){

        $orafine = strftime("%H:%M", strtotime($riga['1']) + 60*$riga['durata']);

        echo"<tr>
                <td>$riga[1]</td>
                <td>$orafine</td>
                <td>$riga[durata]</td>
                <td>$riga[id_macchina]</td>
                <td>
                <form action=\"myarea.php\" method=\"post\">
                    <input type='hidden' name='pren_id' value='$riga[id]'>
                    <label for=\"delete$riga[id]\" style='cursor: pointer'><span><i style='top:3px' class=\"glyphicon glyphicon-trash\"></i></span></label>
                    <input id=\"delete$riga[id]\" type=\"submit\" value=\"Go\" class=\"hidden\" />
                </form>
                </td>
            </tr>";
    }

    echo "</tbody>
          </table>
          </div>";

    mysqli_free_result($result);
    dbClose($conn);
}

function gestioneCancellaPrenotazione(){
    if(isset($_REQUEST['pren_id']) && $_REQUEST['pren_id'] != ''){

        try {
            $conn = dbConnect();
            if (!$conn)
                throw new Exception("Error connecting to the database, please try again later");

            /* validating input */

            $id = sanitizeStringSQL($conn,$_REQUEST['pren_id']);

            if(!isPositiveInt($id))
                throw new Exception("Reservation id is not valid!");

            /* checking that a minute has passed since reservation */

            $query = "SELECT CURRENT_TIMESTAMP, `insert_time` FROM `prenotazioni` WHERE id=$id";



            $result = myQuery($conn, $query);
            if (!$result)
                throw new Exception("Error connecting to the database, please try again later");
            if (mysqli_num_rows($result) == 0)
                throw new Exception("Error, reservation id not valid");

            $riga = myFetchArray($result);

            mysqli_free_result($result);

            $diff = strtotime($riga['0']) - strtotime($riga['1']);

            if($diff < 60)
                throw new Exception("You need to wait one minute before deleting a reservation");

            /* actually delete the reservation */

            $query = "DELETE FROM `prenotazioni` WHERE `prenotazioni`.`id` = $id";



            $result = myQuery($conn, $query);

            if(!$result)
                throw new Exception("Error, impossible to delete the reservation");

            /* everything OK */

            printSuccess("Succesfully deleted your reservation!");





            }
        catch (Exception $e) {
            printAlert($e->getMessage());
        }

        if(isset($conn))
            dbClose($conn);

    }
}

function gestioneRegistrazione(){
    if(isset($_REQUEST['nome']) && isset($_REQUEST['cognome']) && isset($_REQUEST['regEmail']) && isset($_REQUEST['regPassword']) && isset($_REQUEST['confPassword'])){
        try{

            /* controllo siano presenti tutti i parametri */

            if($_REQUEST['nome'] == '' || $_REQUEST['cognome'] == '' || $_REQUEST['regEmail'] == '' || $_REQUEST['regPassword'] == '' || $_REQUEST['confPassword'] == '')
                throw new Exception("You need to fill every field!");

            $conn = dbConnect();
            if (!$conn)
                throw new Exception("Error connecting to the database, please try again later");

            $nome = sanitizeStringSQL($conn,$_REQUEST['nome']);
            $cognome = sanitizeStringSQL($conn,$_REQUEST['cognome']);
            $email = strtolower(sanitizeStringSQL($conn,$_REQUEST['regEmail']));
            $password = sanitizeStringSQL($conn,$_REQUEST['regPassword']);
			$confPassword = sanitizeStringSQL($conn,$_REQUEST['confPassword']);

            /* checking every parameter */

            $check_param = controllaNewRegistrazione($nome,$cognome,$email,$password,$confPassword);

            if($check_param != 'ok')
                throw new Exception($check_param);

            /* inserisco l'utente, ritorna errore se già presente in quanto chiave primaria */

            $query = "INSERT INTO `users` (`id`, `nome`, `cognome`, `email`, `password`) VALUES (NULL, '$nome', '$cognome', '$email', SHA1('$password'))";

            $result = myQuery($conn,$query);

            if(!$result){

                if(@mysqli_errno($conn) == '1062')
                    throw new Exception("Esiste gi&agrave; un utente con l'email $email. Inserire un altro indirizzo e riprovare");
                else
                    throw new Exception("Errore nella connessione al database, riprovare pi&ugrave; tardi");
            }

            /* TUTTO OK */

            printSuccess("Registration successful! You can now log in");

            redirectAfterReg();

            include 'footer.php';

            exit;


        } catch (Exception $e) {
            printAlert($e->getMessage());
            if(isset($conn))
                dbClose($conn);
        }

    }
    
    
    
    
}

function gestioneInserimento(){
    global $printers;


    if(isset($_REQUEST['inizio']) && isset($_REQUEST['durata']) && $_REQUEST['inizio'] != '' && $_REQUEST['durata'] != ''){

        try {
            $conn = dbConnect();
            if (!$conn)
                throw new Exception("Error connecting to the database, please try again later");

            $newinizio = sanitizeStringSQL($conn,$_REQUEST['inizio']);
            $newdurata = sanitizeStringSQL($conn,$_REQUEST['durata']);

            /* checking parameters */

            $check_param = controllaNewPrenotazione($newinizio,$newdurata);

            if($check_param != 'ok')
                throw new Exception($check_param);

            /* checking that the reservation does not exceed the day */

            $newinizio = strtotime($newinizio);
            $newfine = $newinizio + 60*$newdurata;

            $maxfine = strtotime("23:59");


            if( $newfine > $maxfine)
                throw new Exception("Maximum end time for a reservation is 23:59.<BR>Choose another time period and try again.");

        } catch (Exception $e) {
            printAlert($e->getMessage());
            dbClose($conn);
            return;
        }

        /* checking overlapping */

        try{
            @mysqli_autocommit($conn, false);

            $query = "SELECT * FROM `prenotazioni` WHERE 1 FOR UPDATE ";

            $result = myQuery($conn,$query);

            if(!$result)
                throw new Exception("Error connecting to the database, please try again later");


            while($riga = myFetchArray($result)){

                $oldinizio = strtotime($riga['inizio']);
                $oldfine = $oldinizio + 60*$riga['durata'];

                /* check overlap */

                if( ($newinizio >= $oldinizio && $newinizio <= $oldfine) ||
                        ($oldinizio >= $newinizio && $oldinizio <= $newfine) )
                {
                    $printers[$riga['id_macchina']] = 'busy';
                }

            }

            $newprinter = false;

            foreach($printers as $key=>$value){
                if($printers[$key] == 'free'){
                    $newprinter = $key;
                    break;
                }
            }

            if(!$newprinter) // nothing found
                throw new Exception("No printers avaliable in the selected interval!");

            $newinizio = strftime("%H:%M",$newinizio);
            @mysqli_free_result($result);

            $query = "INSERT INTO `prenotazioni` (`id`, `id_utente`, `id_macchina`, `inizio`, `durata`, `insert_time`) 
                      VALUES (NULL, '$_SESSION[user_id]', '$newprinter', '$newinizio', '$newdurata', CURRENT_TIMESTAMP)";

            $result = myQuery($conn,$query);

            if(!$result)
                throw new Exception("Error inserting new reservation, please try again later");

            if(!@mysqli_commit($conn))
                throw new Exception("Error inserting new reservation, please try again later");

            /* tutto ok */

            printSuccess("Reservation successful!");


        } catch (Exception $e) {
            @mysqli_rollback($conn);
            printAlert($e->getMessage());
        }

        dbClose($conn);

    }

}