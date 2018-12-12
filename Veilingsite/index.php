<?php
include_once "components/connect.php";
include_once "components/meta.php";

Function auctionBoxes($size, $amountOfActions, $amountHidden, $sortByThis = "Startprijs", $upOrDown = "DESC")
{
    global $dbh;

    $i = 1;

    $querySelectionAuctions = "SELECT TOP $amountOfActions Titel, Startprijs, EindMoment FROM Voorwerp WHERE VeilingGesloten = 0 ORDER BY $sortByThis $upOrDown";

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
                        <div>
                            // <img class="thumbnail" src="img/' . $auction['AfbeeldingNaam'] . '" alt="Foto van een veiling">
                        </div>
                        <div class="card-body">
                            <div class="grid-x ">
                                <div class="cell small-6">
                                    <div class="grid-x grid-padding-x">
                                        <div class="cell">
                                            <h5>' . $auction['Titel'] . '</h5>
                                        </div>
                                        <div class="cell timer">
                                            <h5>' . $time . '</h5>
                                        </div>
                                        <div class="cell">
                                        <p>Startprijs: €' . $auction['Startprijs'] . ',-</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="cell small-6">
                                    <div class="button-left">
                                     <a href="#" class="button expanded">Bied nu!</a>
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
        <div class="small-12 medium-12 cell">
            <h4>Veilingen aan het sluiten</h4>
        </div>

        <?php //auctionBoxes("Big", 4, 1, "EindMoment"); ?>
    </div>

    <div class="grid-x grid-padding-x home-veilingen-box">
        <div class="medium-12 large-12 cell">
            <h4>Exclusief</h4>
        </div>

        <?php //auctionBoxes("Small", 8, 2, "Startprijs"); ?>
    </div>

    <div class="grid-x grid-padding-x home-veilingen-box">
        <div class="medium-12 large-12 cell">
            <h4>Koopjes</h4>
        </div>

        <?php //auctionBoxes("Small", 8, 2, "Startprijs", "ASC"); ?>
    </div>

</div>

<?php include "components/scripts.html"; ?>

</body>

</html>
