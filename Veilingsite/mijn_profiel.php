<?php
include_once "components/connect.php";
include_once 'components/meta.php';
?>
<body>
<?php include_once 'components/header.php'; ?>

<?PHP

Function prijs($Voorwerpnummer)
{
    global $dbh;

// Selects the top most Startprijs and Highest Bodbedrag based on the Voorwerpnummer.
    $queryPrice = "SELECT TOP 1 V.Startprijs, B.Bodbedrag
                       FROM Bod B FULL OUTER JOIN Voorwerp V ON B.Voorwerp = V.Voorwerpnummer
                       WHERE Voorwerpnummer = :Voorwerpnummer
                       ORDER BY B.Bodbedrag DESC, V.Startprijs DESC";

    $Prices = $dbh->prepare($queryPrice);
// $Prices->bindParam(":a", $b);
    $Prices->bindParam(":Voorwerpnummer", $Voorwerpnummer);
    $Prices->execute();
    while ($Price = $Prices->fetch()) {
        //Er is minimaal 1 keer geboden (daarvan is automatisch het hoogste bedrag al gepakt).
        if ($Price['Bodbedrag'] != null) {
            return $Price['Bodbedrag'];
        } else {
            return $Price['Startprijs'];
        }
    }
}

function get_from_voorwerp($header, $column, $filter, $order_by, $up_or_down, $tabel)
{
    global $dbh;
    $gebruikersnaam = $_SESSION["ingelogde_gebruiker"];
    if ($tabel == 'Voorwerp') {
        $sql_get_voorwerpen_query = "select top 8 * from Voorwerp where Verkoper = '$gebruikersnaam' AND $column = $filter order by $order_by $up_or_down";
    } elseif ($tabel == 'Bod') {
        $sql_get_voorwerpen_query = "select top 8 * from Voorwerp v RIGHT JOIN Bod b ON b.Voorwerp = v.Voorwerpnummer WHERE B.Gebruikersnaam = '" . $gebruikersnaam . "'";
    }
    $sql_get_voorwerpen = $dbh->prepare($sql_get_voorwerpen_query);
    $sql_get_voorwerpen->execute();
    //$sql_get_voorwerpen_data = $sql_get_voorwerpen->fetchAll();
    //var_dump($sql_neem_voorwerpen_data);
    if ($sql_get_voorwerpen->rowCount() == 0) {
        return;
    } else {
        echo '    <div class="grid-x grid-padding-x home-veilingen-box">
                <div class="cell">
                    <h4>' . $header . '</h4>
                </div>';
    }

    while ($auction = $sql_get_voorwerpen->fetch()) {

        $title = (strlen($auction['Titel']) > 38) ? substr($auction['Titel'], 0, 35) . '...' : $auction['Titel'];


        // De tijd word hier berekend-----------------------------
        $datetime1 = strtotime(date("Y/m/d h:i:s", time()));
        $datetime2 = strtotime($auction['EindMoment']);

        $secs = $datetime2 - $datetime1;// seconds between the two times
        $mins = $secs / 60;
        $hours = $mins / 60;
        $days = $hours / 24;

        if ($mins < 60) {
            $time = floor($mins) . "mins " . ($secs - $mins * 60) . " secs";
        } else if ($hours < 24) {
            $time = round($hours) . " Uren";
        } else {
            $time = round($days) . " Dagen";
        }
        //--------------------------------------------------------

        echo '      
                <div class="cell small-6 medium-4 large-3">
                <div class="veiling-sluit-index">
                        <div class="resizeImage">
                            <img src="http://iproject5.icasites.nl/thumbnails/' . $auction['Thumbnail'] . '" alt="Auction Photo">
                        </div>
                        <div class="card-body">
                            <div class="grid-x">
                                <div class="cell">
                                    <div class="grid-x">
                                        <div class="cell">
                                            <h5>' . $title . '</h5>
                                        </div>
                                        <div class="cell timer">
                                            <h5>' . $time . '</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="cell">
                                    <div class="grid-x FullCenter">
                                        <div class="cell large-6">
                                            <p class="noMargins noLineHeight">Prijs: €' . prijs($auction['Voorwerpnummer']) . '</p>
                                        </div>
                                        <div class="cell large-6">
                                            <div class="button-left noMargins">
                                                <a href="detailpagina.php?Voorwerpnummer=' . $auction['Voorwerpnummer'] . '"
                                                   class="button expanded noMargins">Bied nu!</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div></div>';
    }
    echo '  
        </div>';
}

echo '   <div class="grid-container">';
//Neemt de nog niet afgelopen veilingen van de verkoper uit de database
get_from_voorwerp('Mijn laatst toegevoegde veilingen', 'veilinggesloten', '0', 'BeginMoment', 'DESC', 'Voorwerp');

//Neemt de al afgelopen veilingen van de verkoper uit de database
get_from_voorwerp('Mijn afgelopen veilingen', 'veilinggesloten', '1', 'EindMoment', 'DESC', 'Voorwerp');

//Neemt de alle veilingen uit de database waar de gebruiker op heeft geboden
get_from_voorwerp('Mijn geboden veilingen', 'veilinggesloten', '1', 'EindMoment', 'DESC', 'Bod');
echo '   </div>';

?>

<?php include "components/scripts.html"; ?>
</body>
</html>

