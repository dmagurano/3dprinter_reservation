<?php

/* Numero di stampanti disponibili nel sistema */

$nPrinters = 4;

$printers = array();

for($i=1 ; $i<=$nPrinters ; $i++){
    $printers["$i"] = 'free';
}

?>