<?php
    include_once "components/connect.php";
    include_once "components/meta.php";

    Function auctionBoxes($size, $amountOfActions, $amountHidden, $sortByThis = "Startprijs", $upOrDown = "DESC")
    {
        global $dbh;

        $i = 1;

        $querySelectionAuctions = "SELECT TOP $amountOfActions Titel, Startprijs, EindMoment, Thumbnail FROM Voorwerp WHERE VeilingGesloten = 0 ORDER BY $sortByThis $upOrDown";

        $auctions = $dbh->prepare($querySelectionAuctions);
        $auctions->execute();
        while ($auction = $auctions->fetch()) {

            $datetime1 = strtotime(date("Y/m/d h:i:s", time()));
            $datetime2 = strtotime($auction['EindMoment']);

            $secs = $datetime2 - $datetime1;// == <seconds between the two times>
            $mins = $secs / 60;
            $hours = $mins / 60;
            $days = $hours / 24;

            if ($mins < 60) {
                $time = round($mins) . ":" . (($secs / 60) - 60) * 60 . " Min";
            } else if ($hours < 24) {
                $time = round($hours) . " Uren";
            } else {
                $time = round($days) . " Dagen";
            }

            if ($size === "Big") {
                if ($i <= $amountOfActions - $amountHidden) {
                    echo '<div class="small-12 medium-6 large-4 cell dbc">';
                } else {
                    echo '<div class="small-12 medium-6 cell hide-for-large dbc">';
                }
            } else if ($size === "Small") {
                if ($i <= $amountOfActions - $amountHidden * 2) {
                    echo '<div class="cell small-6 medium-4 large-3 dbc">';
                } else if ($i <= $amountOfActions - $amountHidden) {
                    echo '<div class="cell small-6 medium-4 large-3 hide-for-medium-only hide-for-small-only dbc">';
                } else {
                    echo '<div class="cell small-6 medium-4 large-3 hide-for-small-only dbc">';
                }
            }

            echo '
                        <div class="veiling-sluit-index">
                            <div class="abc">
                                <img src="http://iproject5.icasites.nl/thumbnails/'. $auction['Thumbnail'] .'" alt="">
                            </div>
                            <div class="card-body">
                                <div class="grid-x">
                                    <div class="cell">
                                        <div class="grid-x">
                                            <div class="cell">
                                                <h5>' . $auction['Titel'] . '</h5>
                                            </div>
                                            <div class="cell timer">
                                                <h5>' . $time . '</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cell">
                                        <div class="grid-x ddd">
                                            <div class="cell large-6">
                                                <p class="noMargins noLineHeight">Startprijs: €' . $auction['Startprijs'] . ',-</p>
                                            </div>
                                            <div class="cell large-6">
                                                <div class="button-left noMargins">
                                                    <a href="#" class="button expanded noMargins">Bied nu!</a>
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

            <?php auctionBoxes("Big", 4, 1, "EindMoment", "ASC"); ?>
        </div>

        <div class="grid-x grid-padding-x home-veilingen-box">
            <div class="cell">
                <h4>Exclusief</h4>
            </div>

            <?php auctionBoxes("Small", 8, 2, "Startprijs"); ?>
        </div>

        <div class="grid-x grid-padding-x home-veilingen-box">
            <div class="cell">
                <h4>Koopjes</h4>
            </div>

            <?php auctionBoxes("Small", 8, 2, "Startprijs", "ASC"); ?>
        </div>

    </div>

    <?php include "components/scripts.html"; ?>

</body>

</html>
