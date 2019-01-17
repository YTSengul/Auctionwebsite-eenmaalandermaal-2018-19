<?php
include_once "components/connect.php";
include_once "components/meta.php";

$errorMessage = "";

function insertBid($bid, $voorwerpNummer, $userName, $time)
{
    global $dbh;

    $gebruikersnaam = $_SESSION['ingelogde_gebruiker'];
    $verstuur_bod_query = "INSERT INTO bod (Bodbedrag, Voorwerp, Gebruikersnaam, Tijd) VALUES(?, ?, ?, ?)";
    $verstuur_bod = $dbh->prepare($verstuur_bod_query);
    $verstuur_bod->bindParam(1, $bid);
    $verstuur_bod->bindParam(2, $voorwerpNummer);
    $verstuur_bod->bindParam(3, $userName);
    $verstuur_bod->bindParam(4, $time);

    $verstuur_bod->execute();
}

function minimaleVerhoging($price)
{
    switch ($price) {
        case $price < 50:
            return 0.5;
            break;
        case $price >= 50 && $price < 500:
            return 1;
            break;
        case $price >= 500 && $price < 1000:
            return 5;
            break;
        case $price >= 1000 && $price < 5000:
            return 10;
            break;
        case $price >= 5000:
            return 50;
            break;
    }
}

function wrongBiddingMessage()
{
    global $errorMessage;
    if ($errorMessage != null) {
        echo "<div class='errorMessage'>" . $errorMessage . "</div>";
    }
}

function minimumPrice($price)
{
    return $price + minimaleVerhoging($price);
}

function getMinimumPrice()
{
    if(auctionBiddingDetails($_GET['Voorwerpnummer'])[0] == "a_Bidder"){
        return minimumPrice(auctionBiddingDetails($_GET['Voorwerpnummer'])[2]);
    }
    else{
        return auctionBiddingDetails($_GET['Voorwerpnummer'])[2];
    }
}

function auctionBiddingDetails($voorwerpNummer)
{
    global $dbh;

    $auctionDetails_query = "SELECT Startprijs, Verkoper FROM Voorwerp WHERE Voorwerpnummer = :voorwerpNummer";
    $auctionDetails = $dbh->prepare($auctionDetails_query);
    $auctionDetails->bindParam(":voorwerpNummer", $voorwerpNummer, PDO::PARAM_INT);
    $auctionDetails->execute();

    $startPriceAndSeller = $auctionDetails->fetch(PDO::FETCH_OBJ);
    $startPrice = $startPriceAndSeller->Startprijs;
    $seller = $startPriceAndSeller->Verkoper;

    $biddingDetails_query = "SELECT TOP (:top) Bodbedrag, Gebruikersnaam FROM bod WHERE voorwerp = :voorwerpNummer ORDER BY Bodbedrag DESC";
    $biddingDetails = $dbh->prepare($biddingDetails_query);
    $biddingDetails->bindValue(":top", 1, PDO::PARAM_INT);
    $biddingDetails->bindParam(":voorwerpNummer", $voorwerpNummer, PDO::PARAM_INT);
    $biddingDetails->execute();

    if ($biddingDetails->rowCount() != 0) {
        $highestBidAndNameHighestBidder = $biddingDetails->fetch(PDO::FETCH_OBJ);
        $highestBid = $highestBidAndNameHighestBidder->Bodbedrag;
        $highestBidder = $highestBidAndNameHighestBidder->Gebruikersnaam;

        //Double check that the bidding is higher than the starting price.
        //If this were to be the case it will be treated as if there were no bidding yet.
        //The only case that a person can bid twice in a row. (But impossible without messing with the database).
        if ($startPrice > $highestBid) {
            return array("no_Biddings", $seller, $startPrice);
        } else {
            return array("a_Bidder", $seller, $highestBid, $highestBidder);
        }

    } else {
        return array("no_Biddings", $seller, $startPrice);
    }
}

//User pressed the bid button.
if (isset($_POST['verstuur_bod'])) {
    //User is logged in.
    if (isset($_SESSION['ingelogde_gebruiker'])) {

        //Amount that the user bid for the auction.
        $bod = $_POST['bod'];

        //Must be a number.
        if (is_numeric($bod)) {

            //Rounded down to 2 decimals. For some reason the function number_format makes the float an string again.
            $bod = (float)number_format($bod, 2);

            $auctionAndBiddingDetails = auctionBiddingDetails($_GET['Voorwerpnummer']);
            $seller = $auctionAndBiddingDetails[1];
            $price = $auctionAndBiddingDetails[2];
            $minimumBidPrice;

            //Er is een verhoging nodig.
            if($auctionAndBiddingDetails[0] == "a_Bidder"){
                echo "hit";
                $minimumBidPrice = minimumPrice($price);
            }
            //Eerste bod dus startprijs = minimale prijs.
            else{
                echo "miss";
                $minimumBidPrice = $price;
            }

            //Seller tries to bid on his own auction.
            if ($seller == $_SESSION['ingelogde_gebruiker']) {
                $errorMessage = "U mag niet op uw eigen veilingen bieden.";
            } else {
                //Impossible username.
                $highest_Bid_Username = "a";
                //There is at least 1 bid that has been placed.
                if ($auctionAndBiddingDetails[0] == "a_Bidder") {
                    $highest_Bid_Username = $auctionAndBiddingDetails[3];
                }
                //This user has the last (and highest) bid already on him.
                if ($highest_Bid_Username == $_SESSION['ingelogde_gebruiker']) {
                    $errorMessage = "U heeft al geboden.";
                } else {
                    if ($minimumBidPrice <= $bod) {
                        insertBid($bod, $_GET['Voorwerpnummer'], $_SESSION['ingelogde_gebruiker'], date('Y-m-d H:s:i'));
                    } else {
                        $errorMessage = "U moet hoger bieden.";
                    }
                }
            }
        } else {
            $errorMessage = "U moet een juist bedrag invoeren.";
        }

    } else if (!isset($_SESSION['ingelogde_gebruiker'])) {
        $errorMessage = "Je moet eerst inloggen voordat je kan bieden.";
    }
}

//Als er geen Voorwerpnummer wordt meegegeven in de header (wat meestal betekent dat de user zelf probeert om via de URL de pagina te bereiken) kan de pagina niet correct geladen worden en wordt de user teruggestuurd naar de homepage.
if (!isset($_GET['Voorwerpnummer'])) {
    header('location: index.php');
}

//Prepared statement voor de productinformatie
$detailsAuction = $dbh->prepare("SELECT Titel, Beschrijving, EindMoment, Startprijs FROM Voorwerp WHERE Voorwerpnummer = ?");
$detailsAuction->execute([$_GET['Voorwerpnummer']]);
$resultAuction = $detailsAuction->fetch();

$title = $resultAuction['Titel'];
$description = $resultAuction['Beschrijving'];
$endTime = $resultAuction['EindMoment'];
$startprijs = $resultAuction['Startprijs'];

//Geeft variabele door aan productomschrijving in iFrame
$_SESSION['beschrijving'] = $description;

//Prepared statement voor de images
$pictureAuction = $dbh->prepare("SELECT Filenaam FROM Bestand WHERE Voorwerp = ?");
$pictureAuction->execute([$_GET['Voorwerpnummer']]);
$pictureAuctionResult = $pictureAuction->fetchAll();

//Prepared statement voor de biedingen
$topFiveBids = $dbh->prepare("SELECT TOP 4 Bodbedrag, Gebruikersnaam FROM Bod WHERE Voorwerp = ? ORDER BY Bodbedrag DESC");
$topFiveBids->execute([$_GET['Voorwerpnummer']]);
$resultTopFiveBids = $topFiveBids->fetchAll();

// Hier wordt de eerste bieding op de veiling uit de database genomen
$eerstebieding_query = "SELECT Bodbedrag, Gebruikersnaam FROM Bod WHERE Voorwerp = ? ORDER BY Bodbedrag ASC";
$eerstebieding_data = $dbh->prepare($eerstebieding_query);
$eerstebieding_data->execute([$_GET['Voorwerpnummer']]);
$eerstebieding = $eerstebieding_data->fetchAll();

//Prepared statement voor het aantal biedingen: Later samenvoegen met statement hierboven?
$amountBidsAuction = $dbh->prepare("SELECT COUNT(Voorwerp) FROM Bod WHERE Voorwerp = ? GROUP BY Voorwerp");
$amountBidsAuction->execute([$_GET['Voorwerpnummer']]);
$resultAmountBidsAuction = $amountBidsAuction->fetch();

$finalAmountBidsAuction = $resultAmountBidsAuction[0];

//Functie die de top 5 biedingen toont met username en bedrag
$first = true;
function echoBedragen($resultTopFiveBids, $eerstebieding, $startprijs)
{
    global $first;
    foreach ($resultTopFiveBids as $bidData) {
        if ($first == true) {
            echo '<div class="spaceBetween"><h4><b>&euro;' . $bidData[0] . '</h4><h5>' . $bidData[1] . '</b></h5></div><hr>';
            $first = false;
        } else {
            echo '<div class="spaceBetween"><h4>&euro;' . $bidData[0] . '</h4><h5>' . $bidData[1] . '</h5></div><hr>';
        }
    }
    if ($eerstebieding != null & sizeof($eerstebieding) > 4) {
        echo '<div class="spaceBetween"><h5>Startprijs: €' . $startprijs . '</h5><h5>Eerste bod: €' . $eerstebieding[0][0] . '</h5></div><hr>';
    }
}

// Check of er een afbeelding is gevonden of niet.
function c_file_exists($file)
{
    $file_headers = @get_headers($file);
    if (strpos($file_headers[0], '404 Not Found')) {
        return false;
    }
    return true;
}

//Functie die de hoofdfoto toont die bij een veiling hoort
function echoMainpicture($pictureAuctionResult)
{
    echo '<div>';
    foreach ($pictureAuctionResult as $mainPicture) {

        if (c_file_exists('http://iproject4.icasites.nl/pics/' . $mainPicture[0])) {
            echo "<img class='detailfoto mySlides' src='http://iproject4.icasites.nl/pics/$mainPicture[0]' alt='Foto van een product' >";
        } else {
            echo "<img class='detailfoto' src='upload/$mainPicture[0]' alt='Foto van een product' >";
        }
    }
    echo '<button class="detailpagina-button-left" onclick="plusDivs(-1)">&#10094; Vorige foto</button>
          <button class="detailpagina-button-right" onclick="plusDivs(1)">Volgende foto &#10095;</button></div>';
}

//Functie die de subfoto's toont die bij een veiling horen
function echoSubpictures($pictureAuctionResult)
{
    foreach ($pictureAuctionResult as $picture) {
        if (c_file_exists('http://iproject4.icasites.nl/pics/' . $picture[0])) {
            echo "<img class='detailsubfoto' src='http://iproject4.icasites.nl/pics/$picture[0]' alt='Subfoto van een product'>";
        } else {
            echo "<img class='detailsubfoto' src='upload/$picture[0]' alt='Foto van een product'>";
        }
    }
}

?>

<?PHP

// hier zijn de breadcrumbs in te vinden
function create_Breadcrumbs($RubriekNummer)
{
    global $dbh;
    $vind_hoofdrubriek_query = "SELECT * FROM Rubriek WHERE RubriekNummer = $RubriekNummer";
    $vind_hoofdrubriek_data = $dbh->prepare($vind_hoofdrubriek_query);
    $vind_hoofdrubriek_data->execute();
    $vind_hoofdrubriek = $vind_hoofdrubriek_data->fetchAll(PDO::FETCH_NUM);
    $hoofdrubriek = $vind_hoofdrubriek[0];
    return $hoofdrubriek;
}

// hier wordt de array van de breadcrumb gemaakt
$breadcrumbs_namen = array();
$breadcrumbs_nummers = array();
function call_Breadcrumbs($filter_rubriek, &$breadcrumbs_namen, &$breadcrumbs_nummers)
{
    $rubriek_voor_hoofdcategorie = NULL;
    if ($filter_rubriek != -1) {
        for ($x = 0; $rubriek_voor_hoofdcategorie[2] != -1; $x++) {
            if ($rubriek_voor_hoofdcategorie != NULL) {
                $rubriek_voor_hoofdcategorie = create_Breadcrumbs($rubriek_voor_hoofdcategorie[2]);
                array_push($breadcrumbs_namen, "$rubriek_voor_hoofdcategorie[1]");
                array_push($breadcrumbs_nummers, "$rubriek_voor_hoofdcategorie[0]");
            } else {
                $rubriek_voor_hoofdcategorie = create_Breadcrumbs($filter_rubriek);
                array_push($breadcrumbs_namen, "$rubriek_voor_hoofdcategorie[1]");
                array_push($breadcrumbs_nummers, "$rubriek_voor_hoofdcategorie[0]");
            }
        }
    }
    sort_show_breadcrumbs($breadcrumbs_namen, $breadcrumbs_nummers);
}

//hier worden de breadcrumbs op rij gezet en geshowed dit wordt gedaan om het van achter naar voor te sorteren

function sort_show_breadcrumbs($breadcrumbs_namen, $breadcrumbs_nummers)
{
    echo "<li><a href='veilingen.php?huidigepagina=1&filter_rubriek=-1'>Hoofdrubrieken</a></li>";
    for ($x = sizeof($breadcrumbs_namen) - 1; $x >= 0; $x--) {
        echo "<li><a href='veilingen.php?huidigepagina=1&filter_rubriek=" . $breadcrumbs_nummers[$x] . "'>$breadcrumbs_namen[$x]</a></li>";
    }
}

$detailsAuction_bc = $dbh->prepare("SELECT RubriekOpLaagsteNiveau FROM Voorwerpinrubriek WHERE Voorwerp = " . $_GET['Voorwerpnummer']);
$detailsAuction_bc->execute();
$resultAuction_bc = $detailsAuction_bc->fetch();

?>


<body>
<?php include_once "components/header.php"; ?>
<div class="grid-container">
    <div class="grid-x grid-margin-x detailpagina">
        <div class="medium-12 large-12 float-center cell">
            <!--- Breadcrumbs -->
            <nav aria-label="You are here:" role="navigation" class="veilingen-breadcrumbs">
                <ul class="breadcrumbs">
                    <?php call_Breadcrumbs($resultAuction_bc[0], $breadcrumbs_namen, $breadcrumbs_nummers); ?>
                    <!---<li>
                    <span class="show-for-sr">Current: </span> Huidige cat.
                </li>-->
                </ul>
            </nav>

            <!---<select class="float-right veilingen-filter-hoofd ">
            <option>Optie 1</option>
            <option>Optie 2</option>
            <option>Optie 3</option>
            <option>Optie 4</option>
            <option>Optie 5</option>
        </select>-->

        </div>
        <div clas
        <div class="cell">
            <h2>
                <?php echo $title ?>
            </h2>
        </div>
        <div class="cell large-7 productdetails flexColumn">
            <!--Note to self: Inladen foto testen op de server: replacement inladen bij error-->
            <!--Note to self: Nog implementeren dat bij klik op subfoto dat de hoofdfoto wordt-->
            <?php echoMainpicture($pictureAuctionResult) ?>
            <div class="spaceAround marginTopAuto">
                <?php echoSubpictures($pictureAuctionResult) ?>
            </div>
        </div>
        <div class="cell large-5 detail-biedingen">
            <div class="spaceBetween">
                <h3>Doe een bod</h3>
                <h4 class="detail-timer" id="timer"></h4>
            </div>
            <hr>
            <div>
                <p>Hier kunt u bieden. Denk goed na over uw bod. Eenmaal geboden kunt u uw bod niet meer intrekken en
                    bent u verplicht te betalen als u het product wint.</p>
            </div>
            <?php wrongBiddingMessage(); ?>
            <div>
                <form class="spaceBetween" method="POST">
                    <input type="text" placeholder="Vul bedrag in..." name="bod"
                           value="<?php echo getMinimumPrice(); ?>">
                    <input class="button" type="submit" value="Bieden" name="verstuur_bod">
                    <!--Note to self: Op mobielschermpjes loopt knop het scherm nog uit-->
                </form>
            </div>
            <div class="detail-bedragen">
                <?php echoBedragen($resultTopFiveBids, $eerstebieding, $startprijs) ?>
            </div>
            <div class="detail-aantal">
                <h4>Aantal
                    biedingen:
                    <?php echo $finalAmountBidsAuction > 0 || $finalAmountBidsAuction !== null ? $finalAmountBidsAuction : "0" ?>
                </h4>
            </div>
        </div>
        <div class="cell detailpagina-omschrijving">
            <ul class="tabs" data-tabs id="example-tabs">
                <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Omschrijving</a></li>
                <li class="tabs-title"><a href="#panel2">Feedback</a></li>
            </ul>
            <hr>
            <div class="tabs-content" data-tabs-content="example-tabs">
                <div class="tabs-panel is-active" id="panel1">
                    <iframe src="components/productomschrijving.php" class="detailpagina_iframe"></iframe>
                </div>
                <div class="tabs-panel" id="panel2">
                    <!--Note: Iemand moet dit nog werkend maken-->
                    <p>

                    </p>
                </div>
                <?php include "components/scripts.html"; ?>
                <!--    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>-->
                <!--    <script src="https://dhbhdrzi4tiry.cloudfront.net/cdn/sites/foundation.js"></script>-->
                <!--    <script>-->
                <!--        $(document).foundation();-->
                <!--    </script>-->
                <script>
                    var countdownDate = new Date("<?php echo $endTime; ?>").getTime();
                    var interval = setInterval(function () {
                        var now = new Date().getTime();
                        var distance = countdownDate - now;
                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        document.getElementById("timer").innerHTML = days + "d " + hours + "h " +
                            minutes + "m " + seconds + "s ";
                        if (distance < 0) {
                            clearInterval(interval);
                            document.getElementById("timer").innerHTML = "Veiling beëindigd";
                        }
                    }, 1000);
                </script>
                <script>
                    var slideIndex = 1;
                    showDivs(slideIndex);

                    function plusDivs(n) {
                        showDivs(slideIndex += n);
                    }

                    function showDivs(n) {
                        var i;
                        var x = document.getElementsByClassName("mySlides");
                        if (n > x.length) {slideIndex = 1}
                        if (n < 1) {slideIndex = x.length}
                        for (i = 0; i < x.length; i++) {
                            x[i].style.display = "none";
                        }
                        x[slideIndex-1].style.display = "block";
                    }
                </script>
            </div>
        </div>
    </div>
</div>
</body>

</html>
