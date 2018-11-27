<?php

$hostname = 'mssql2.iproject.icasites.nl'; // naam van server
$dbname = 'iproject4'; // naam van database
$username = 'iproject4'; // gebruikersnaam
$pw = 'F5H8b3Jqdg'; // password

try {
    $dbh = new PDO("sqlsrv:Server=$hostname;Database=$dbname;
                    ConnectionPooling=0", "$username", "$pw");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
  die ( "Fout met de database: {$e->getMessage()} " );
}

?>
