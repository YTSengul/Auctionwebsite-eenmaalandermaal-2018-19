<?php

$hostname = 'mssql2.iproject.icasites.nl';
$databasename = 'iproject4';

try {
    $db = new PDO("sqlsrv:Server=$hostname;Database=$databasename;ConnectionPooling=0", "iproject4","F5H8b3Jqdg");
} catch (PDOException $e) {
    echo("Connectie met de database mislukt. Activeer de 'getMessage' een regel hieronder om de foutmelding te lezen.");
    //echo $e->getMessage();
}

?>