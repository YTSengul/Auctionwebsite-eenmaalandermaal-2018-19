<?php
    include_once "components/connect.php";
    include_once "components/meta.php";

    Function Prijs($Voorwerpnummer){
        global $dbh;

        // Selects the top most Startprijs and Highest Bodbedrag based on the Voorwerpnummer.
        $queryPrice = "SELECT TOP (:top) V.Startprijs, B.Bodbedrag
                       FROM Bod B FULL OUTER JOIN Voorwerp V ON B.Voorwerp = V.Voorwerpnummer
                       WHERE Voorwerpnummer = :Voorwerpnummer
                       ORDER BY B.Bodbedrag DESC, V.Startprijs DESC";

        $Prices = $dbh->prepare($queryPrice);
        $Prices->bindValue(":top", 1, PDO::PARAM_INT);
        $Prices->bindParam(":Voorwerpnummer", $Voorwerpnummer, PDO::PARAM_INT);
        $Prices->execute();
        while($Price = $Prices->fetch()){
            //Er is minimaal 1 keer geboden (daarvan is automatisch het hoogste bedrag al gepakt).
            if($Price['Bodbedrag'] != null){
                return $Price['Bodbedrag'];
            }
            else{
                return $Price['Startprijs'];
            }
        }
    }

    Function auctionBoxes($boxTitle, $size, $auctionClass, $amountOfAuctions, $amountHidden, $sortFilter = "Startprijs", $upOrDown = "DESC")
    {
        global $dbh;

        $i = 1;

        $whereFilter;

        // Selects auctions that end within the next 12 hours.
        if($auctionClass === "Sluitende"){
            $whereFilter = "DATEDIFF(HOUR, CURRENT_TIMESTAMP, EindMoment) < 12";
        }
        // Selects auctions that have a starting price over 100 euro.
        else if($auctionClass === "Exclusief"){
            $whereFilter = "Startprijs > 100";
        }
        // Selects auctions that have a starting price under 10 euro.
        else if($auctionClass === "Goedkoop"){
            $whereFilter = "Startprijs < 10";
        }
        // Not a special where needed.
        else if($auctionClass === "Populair"){
            $whereFilter = "1 = 1";
        }
        // If none of the above where selected, this function won't be activated.
        else{
            return;
        }

        // Selects auctions based on the where statement. After that picks the auctions based on the most biddings, and sorts by the most biddings, price.
        $querySelectionAuctions = "SELECT TOP (:top) V.Voorwerpnummer, V.Titel, V.Startprijs, V.EindMoment, V.Thumbnail, COUNT(B.Voorwerp) AS Aantalboden
                                   FROM Bod B right join Voorwerp V ON B.Voorwerp = V.Voorwerpnummer
                                   WHERE VeilingGesloten = :veilingGesloten AND $whereFilter
                                   GROUP BY V.Voorwerpnummer, B.Voorwerp, V.Titel, V.Startprijs, V.EindMoment, V.Thumbnail
                                   ORDER BY COUNT(B.Voorwerp) $upOrDown, $sortFilter $upOrDown";

        $auctions = $dbh->prepare($querySelectionAuctions);
        $auctions->bindValue(":veilingGesloten", 0, PDO::PARAM_INT);
        $auctions->bindParam(":top", $amountOfAuctions, PDO::PARAM_INT);
        $auctions->execute();

        if($auctions->rowCount() == 0){
            //Geen veilingen gevonden, dus er hoeft geen veiling box gemaakt te worden.
            return;
        }
        else{
            echo '<div class="grid-x grid-padding-x home-veilingen-box">
                    <div class="cell">
                        <h4>' . $boxTitle . '</h4>
                    </div>
            ';
        }

        while ($auction = $auctions->fetch()) {

            // _____________________________ Time _____________________________

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

            // _____________________________ Titel _____________________________

            $titel = (strlen($auction['Titel']) > 38) ? substr($auction['Titel'],0,35).'...' : $auction['Titel'];

            // _____________________________ Size of Box _____________________________

            //If the screen reaches medium size, there will appear $amountHidden auctions.
            if ($size === "Big") {
                if ($i <= $amountOfAuctions - $amountHidden) {
                    echo '<div class="small-12 medium-6 large-4 cell">';
                } else {
                    echo '<div class="small-12 medium-6 cell hide-for-large">';
                }
            }
            //Small auctions have a different way of hiding. $amountHidden determens how many auctions get hidden after the screen reaches medium and small.
            //For every time the screen gets smaller, $amountHidden hides that many auctions.
            else if ($size === "Small") {
                if ($i <= $amountOfAuctions - $amountHidden * 2) {
                    echo '<div class="cell small-6 medium-4 large-3">';
                } else if ($i <= $amountOfAuctions - $amountHidden) {
                    echo '<div class="cell small-6 medium-4 large-3 hide-for-medium-only hide-for-small-only">';
                } else {
                    echo '<div class="cell small-6 medium-4 large-3 hide-for-small-only">';
                }
            }

            echo '
                        <div class="veiling-sluit-index">
                            <div class="resizeImage">
                                <img src="http://iproject5.icasites.nl/thumbnails/'. $auction['Thumbnail'] .'" alt="Auction Photo">
                            </div>
                            <div class="card-body">
                                <div class="grid-x">
                                    <div class="cell">
                                        <div class="grid-x">
                                            <div class="cell">
                                                <h5>' . $titel . '</h5>
                                            </div>
                                            <div class="cell timer">
                                                <h5>' . $time . '</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="grid-x FullCenter">
                                            <div class="cell large-6">
                                                <p class="noMargins noLineHeight">Prijs: €' . Prijs($auction['Voorwerpnummer']) . ',-</p>
                                            </div>
                                            <div class="cell large-6">
                                                <div class="button-left noMargins">
                                                    <a href="detailpagina.php?Voorwerpnummer=' . $auction['Voorwerpnummer'] . '" class="button expanded noMargins">Bied nu!</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            $i++;
        }
        echo '</div>'; //End of the entire auction box.
    }
?>

<body>

    <?php include_once 'components/header.php'; ?>

    <div class="grid-container">

        <?php auctionBoxes("Sluitende veilingen", "Big", "Sluitende", 4, 1); ?>

        <?php auctionBoxes("Populair", "Small", "Populair", 8, 2); ?>

        <?php auctionBoxes("Exclusief", "Small", "Exclusief", 8, 2); ?>

        <?php auctionBoxes("Koopjes", "Small", "Goedkoop", 8, 2); ?>

    </div>

    <?php include "components/scripts.html"; ?>

</body>

</html>
