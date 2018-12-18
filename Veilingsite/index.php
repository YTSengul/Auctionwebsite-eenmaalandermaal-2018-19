<?php
    include_once "components/connect.php";
    include_once "components/meta.php";

    Function auctionBoxes($size, $auctionClass, $amountOfActions, $amountHidden, $sortFilter = "Startprijs", $upOrDown = "DESC")
    {
        global $dbh;

        $i = 1;

        $whereFilter;

        // Selects auctions that end within the next 12 hours.
        if($auctionClass === "Sluitende"){
            $whereFilter = "AND DATEDIFF(HOUR, CURRENT_TIMESTAMP, EindMoment) < 12";
        }
        // Selects auctions that have a starting price over 100 euro.
        else if($auctionClass === "Exclusief"){
            $whereFilter = "AND Startprijs > 100";
        }
        // Selects auctions that have a starting price under 10 euro.
        else if($auctionClass === "Goedkoop"){
            $whereFilter = "AND Startprijs < 10";
        }
        // Not a special where needed.
        else if($auctionClass === "Populair"){
            $whereFilter = "AND 1 = 1";
        }
        // If none of the above where selected, this function won't be activated.
        else{
            return;
        }

        // Selects auctions based on the where statement. After that picks the auctions based on the most biddings, and sorts by the most biddings, price.
        $querySelectionAuctions = "SELECT TOP $amountOfActions V.Voorwerpnummer, V.Titel, V.Startprijs, V.EindMoment, V.Thumbnail, COUNT(B.Voorwerp) AS Aantalboden
                                   FROM Bod B right join Voorwerp V ON B.Voorwerp = V.Voorwerpnummer
                                   WHERE VeilingGesloten = 0 $whereFilter
                                   GROUP BY V.Voorwerpnummer, B.Voorwerp, V.Titel, V.Startprijs, V.EindMoment, V.Thumbnail
                                   ORDER BY COUNT(B.Voorwerp) $upOrDown, $sortFilter $upOrDown";

        $auctions = $dbh->prepare($querySelectionAuctions);
        $auctions->execute();
        while ($auction = $auctions->fetch()) {

            // Echo $auction['Aantalboden'];

            // _____________________________ Time _____________________________

            $datetime1 = strtotime(date("Y/m/d h:i:s", time()));
            $datetime2 = strtotime($auction['EindMoment']);

            $secs = $datetime2 - $datetime1;// == <seconds between the two times>
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

            if ($size === "Big") {
                if ($i <= $amountOfActions - $amountHidden) {
                    echo '<div class="small-12 medium-6 large-4 cell">';
                } else {
                    echo '<div class="small-12 medium-6 cell hide-for-large">';
                }
            } else if ($size === "Small") {
                if ($i <= $amountOfActions - $amountHidden * 2) {
                    echo '<div class="cell small-6 medium-4 large-3">';
                } else if ($i <= $amountOfActions - $amountHidden) {
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
                                                <p class="noMargins noLineHeight">Startprijs: €' . $auction['Startprijs'] . ',-</p>
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
    }
?>

<body>

    <?php include_once 'components/header.php'; ?>

    <div class="grid-container">

        <div class="grid-x grid-padding-x home-veilingen-box">
            <div class="cell">
                <h4>Sluitende veilingen</h4>
            </div>

            <?php auctionBoxes("Big", "Sluitende", 4, 1); ?>
        </div>

        <div class="grid-x grid-padding-x home-veilingen-box">
            <div class="cell">
                <h4>Populair</h4>
            </div>

            <?php auctionBoxes("Small", "Populair", 8, 2); ?>
        </div>

        <div class="grid-x grid-padding-x home-veilingen-box">
            <div class="cell">
                <h4>Exclusief</h4>
            </div>

            <?php auctionBoxes("Small", "Exclusief", 8, 2); ?>
        </div>

        <div class="grid-x grid-padding-x home-veilingen-box">
            <div class="cell">
                <h4>Koopjes</h4>
            </div>

            <?php auctionBoxes("Small", "Goedkoop", 8, 2); ?>
        </div>

    </div>

    <?php include "components/scripts.html"; ?>

</body>

</html>
