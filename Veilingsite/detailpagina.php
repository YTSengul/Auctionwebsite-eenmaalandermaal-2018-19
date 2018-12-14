<?php
include_once "components/connect.php";
include_once "components/meta.php";

//Als er geen Voorwerpnummer wordt meegegeven in de header (wat meestal betekent dat de user zelf probeert om via de URL de pagina te bereiken) kan de pagina niet correct geladen worden en wordt de user teruggestuurd naar de homepage.
if (!isset($_GET['Voorwerpnummer'])) {
    header('location: index.php');
}

//Prepared statement voor de productinformatie
$details_veiling = $dbh->prepare("SELECT Titel, Beschrijving FROM Voorwerp WHERE Voorwerpnummer = ?");
$details_veiling->execute([$_GET['Voorwerpnummer']]);
$detail_veiling = $details_veiling->fetch();

$titel = $detail_veiling['Titel'];
$beschrijving = $detail_veiling['Beschrijving'];

//Prepared statement voor de images
$foto_veiling = $dbh->prepare("SELECT Filenaam FROM Bestand WHERE Voorwerp = ?");
$foto_veiling->execute([$_GET['Voorwerpnummer']]);
$veilingfoto = $foto_veiling->fetchAll(); //PDO::FETCH_NUM

//echo '<pre>', var_dump($veilingfoto), '</pre>';


//Functie die de hoofdfoto toont die bij een veiling hoort
function echoMainpicture($veilingfoto)
{
    $hoofdfotoArray = $veilingfoto[0];
    $hoofdfoto = $hoofdfotoArray[0];
    echo "<img class='detailfoto' src='http://iproject4.icasites.nl/pics/$hoofdfoto' alt='Foto van een product'>";
}

//Functie die de subfoto's toont die bij een veiling horen
function echoSubpictures($veilingfoto)
{
    foreach ($veilingfoto as $foto) {
        echo "<img class='detailsubfoto' src='http://iproject4.icasites.nl/pics/$foto[0]' alt='Subfoto van een product'>";
    }
}

?>

<body>
<?php include_once "components/header.php"; ?>
<div class="grid-container">
    <div class="grid-x grid-margin-x detailpagina">
        <div class="cell">
            <h2><?php echo $titel ?></h2>
        </div>
        <div class="cell large-7 productdetails flexColumn">
            <!--Note to self: Inladen foto testen op de server: replacement inladen bij error-->
            <?php echoMainpicture($veilingfoto) ?>
            <div class="spaceAround marginTopAuto">
                <?php echoSubpictures($veilingfoto) ?>
            </div>
        </div>
        <div class="cell large-5 detail-biedingen">
            <div class="spaceBetween">
                <h3>Doe een bod</h3>
                <h3>00:00</h3>
            </div>
            <hr>
            <div>
                <p>Hier kunt u bieden. Denk goed na over uw bod. Eenmaal geboden kunt u uw bod niet meer intrekken en
                    bent u verplicht te betalen als u het product wint.</p>
            </div>
            <div>
                <form class="spaceBetween">
                    <input type="number" placeholder="Vul bedrag in...">
                    <input class="button" type="submit" value="Bieden">
                </form>
            </div>
            <div class="detail-bedragen">
                <div class="spaceBetween">
                    <h4>&euro; 0,-</h4>
                    <h5>Username1</h5>
                </div>
                <hr>
                <div class="spaceBetween">
                    <h4>&euro; 0,-</h4>
                    <h5>Username2</h5>
                </div>
                <hr>
                <div class="spaceBetween">
                    <h4>&euro; 0,-</h4>
                    <h5>Username3</h5>
                </div>
                <hr>
                <div class="spaceBetween">
                    <h4>&euro; 0,-</h4>
                    <h5>Username4</h5>
                </div>
                <hr>
                <div class="spaceBetween">
                    <h4>&euro; 0,-</h4>
                    <h5>Username5</h5>
                </div>
                <hr>
            </div>
            <div class="detail-aantal">
                <h4>Aantal biedingen: 0</h4>
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
<!--                    <iframe id="contact" allowtransparency="true" frameborder="1" scrolling="yes" width="100%" height="900px">-->
            <div class="tabs-panel" id="panel2"> <!--Note: Iemand moet dit nog werkend maken-->
                <p>Yes, sir. I think those new droids are going to work out fine. In fact, I, uh, was also thinking
                    about
                    our agreement about my staying on another season. And if these new droids do work out, I want to
                    transmit my application to the Academy this year. You mean the next semester before harvest? Sure,
                    there're more than enough droids. Harvest is when I need you the most. Only one more season. This
                    year
                    we'll make enough on the harvest so I'll be able to hire some more hands. And then you can go to the
                    Academy next year. You must understand I need you here, Luke. But it's a whole 'nother year. Look,
                    it's
                    only one more season. Yeah, that's what you said last year when Biggs and Tank left. Where are you
                    going? It looks like I'm going nowhere. I have to finish cleaning those droids.
                </p>
            </div>
        </div>
        <?php include "components/scripts.html"; ?>
        <!--    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>-->
        <!--    <script src="https://dhbhdrzi4tiry.cloudfront.net/cdn/sites/foundation.js"></script>-->
        <!--    <script>-->
        <!--        $(document).foundation();-->
        <!--    </script>-->
    </div>
</div>
</body>

</html>