<?php

include_once "components/connect.php";

include_once "components/meta.php";


// Op deze plaats worden de nieuwe volgnummers naar de database gestuurd indie die zij uitgevoerd
if (isset($_POST['rubriek_sorteer'])) {
    $sql_wijzig_volgnummer_query = 'update rubriek set Volgnummer = :volgnummer where Rubrieknummer = :rubrieknummer ';
    $sql_wijzig_volgnummer = $dbh->prepare($sql_wijzig_volgnummer_query);

    foreach ($_POST as $Rubrieknummer => $Volgnummer) {
        if ($Rubrieknummer != 'rubriek_sorteer') {
            $sql_wijzig_volgnummer->bindParam(":volgnummer", $Volgnummer);
            $sql_wijzig_volgnummer->bindParam(":rubrieknummer", $Rubrieknummer);
            $stuur_nieuwe_volgnummers = $sql_wijzig_volgnummer->execute();
        }

    }
    $s = $_SERVER['REQUEST_URI'];
    $nieuwe_string_na_sorteren = strstr($s, '?', true);
    header('Location:' . $nieuwe_string_na_sorteren);
}
// Dit is de stuk waar de rubrieknaam veranderd wordt als de formulier is ingediend
else if (isset($_GET['rubriek_hernoem'])) {

    $nieuwe_rubriek_naam = $_GET['hernoem_rubriek'];
    $nummer_van_hernoem_rubriek = $_GET['nummer_van_hernoem_rubriek'];

    $sql_hernoem_rubriek_query = 'update Rubriek set Rubrieknaam = :rubrieknaam where Rubrieknummer = :rubrieknummer';
    $sql_nieuwe_rubrieknaam = $dbh->prepare($sql_hernoem_rubriek_query);

    $sql_nieuwe_rubrieknaam->bindParam(":rubrieknaam", $nieuwe_rubriek_naam);
    $sql_nieuwe_rubrieknaam->bindParam(":rubrieknummer", $nummer_van_hernoem_rubriek);

    $stuur_nieuwe_rubrieknaam = $sql_nieuwe_rubrieknaam->execute();

    try {
        if ($stuur_nieuwe_rubrieknaam) {
            echo "<br>succesvol toegevoegd aan database";
        } else {
            echo "<br>niet toegevoegd aan database";
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
// Het versturen van de nieuwe Subrubriek naar de database
else if (isset($_GET["subrubriek_voegtoe"])) {

    $sql_neem_hoogste_rubrieknummer = "select MAX(RubriekNummer) from Rubriek  order by 'Volgnummer' desc";
    $sql_hoogste_rubrieknummer = $dbh->prepare($sql_neem_hoogste_rubrieknummer);
    $sql_hoogste_rubrieknummer->execute();
    $hoogste_rubrieknummer_data = $sql_hoogste_rubrieknummer->fetchAll(PDO::FETCH_NUM);
    $hoogste_rubrieknummer = $hoogste_rubrieknummer_data[0][0];
    $nieuw_hoogste_hoofdrubrieknummer = $hoogste_rubrieknummer += 1;

    //Hoofdrubriek van de subrubriek
    $nummer_van_hoofdrubriek = $_GET['nummer_van_hoofdrubriek'];

    // Naam van de nieuwe rubriek
    $nieuw_subrubrieknaam = $_GET['subrubriek_voeg_toe'];

    // VOlgnummer toevoegen
    $nieuw_volgnummer = 0;

    /* De nieuwe subrubriek wordt met een query in de database opgeslagen */
    $sql_voeg_nieuwe_subrubriek_toe = "insert into Rubriek ([RubriekNummer], [RubriekNaam], [VorigeRubriek], [Volgnummer]) values (:nieuw_hoogste_rubrieknummer, :nieuw_rubrieknaam, :nieuw_rubriek, :nieuw_volgnummer)";
    $voeg_nieuwe_subrubriek_toe = $dbh->prepare($sql_voeg_nieuwe_subrubriek_toe);


    if ($voeg_nieuwe_subrubriek_toe) {
        $voeg_nieuwe_subrubriek_toe->bindParam(":nieuw_hoogste_rubrieknummer", $nieuw_hoogste_hoofdrubrieknummer, PDO::PARAM_STR);
        $voeg_nieuwe_subrubriek_toe->bindParam(":nieuw_rubrieknaam", $nieuw_subrubrieknaam, PDO::PARAM_STR);
        $voeg_nieuwe_subrubriek_toe->bindParam(":nieuw_rubriek", $nummer_van_hoofdrubriek, PDO::PARAM_STR);
        $voeg_nieuwe_subrubriek_toe->bindParam(":nieuw_volgnummer", $nieuw_volgnummer, PDO::PARAM_STR);

        $voeg_nieuwe_subrubriek_toe->execute();

        try {
            if ($voeg_nieuwe_subrubriek_toe) {
                echo "<br>Succesvol toegevoegd aan database</br>";
            } else {
                echo "<br>Fout met toevoegen aan database</br>";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

// De bestaande rubrieken in een array stoppen
$alle_rubrieken_query = "SELECT * FROM Rubriek WHERE VorigeRubriek = '-1' ORDER BY volgnummer DESC";
$sql_alle_rubrieken = $dbh->prepare($alle_rubrieken_query);
$sql_alle_rubrieken->execute();
$alle_hoofdrubrieken_data = $sql_alle_rubrieken->fetchAll(PDO::FETCH_NUM);

// het opslaan van hoe ver de beheerder is met de rubriekenboom
if (!isset($_SESSION['formulier_count'])) {
    $_SESSION['formulier_count'] = 0;
}

for ($x = 1; $x < 11; $x++) {
    ${'formulier_' . $x . '_actief'} = $x;
}

// de rubrieken opslaan die de beheerder gekozen heeft
for ($x = 0; $x < 10; $x++) {
    if (!isset($_SESSION["formulier_" . $x . "_save"])) {
        $_SESSION["formulier_" . $x . "_save"] = '';
    }

    if (isset($_GET["rubriek_zoek_$x"])) {
        $_SESSION["formulier_" . $x . "_save"] = $_GET["zoek_$x"];
    }
}

// Hier worden de hoofdrubrieken aangeroepen met de keuzes
function beheerpagina_hoofdrubriek()
{
    global $alle_hoofdrubrieken_data;

    echo '<div class="medium-3 large-3 cell">
            <form action="#" method="GET">
                <select name="zoek_0">';

    foreach ($alle_hoofdrubrieken_data as $hoofdrubrieken) {
        if ($hoofdrubrieken[0] == $_SESSION['formulier_0_save']) {
            echo '<option selected value="' . $hoofdrubrieken[0] . '">' . $hoofdrubrieken[1] . '</option>';
        } else {
            echo '<option value="' . $hoofdrubrieken[0] . '">' . $hoofdrubrieken[1] . '</option>';
        }
    }

    echo '</select>
            <input type="submit" value="zoek" name="rubriek_zoek_0" class="button expanded">
            <input type="submit" value="Hernoem" name="rubriek_zoek_0" class="button expanded">
            <input type="submit" value="Subrubriek invoegen" name="rubriek_zoek_0" class="button expanded">
            <input type="submit" value="Sorteren" name="rubriek_zoek_0" class="button expanded">
            </form>
        </div>';
}

// Hier worden de subrubrieken aangeroepen met de keuzes
function beheerpagina_subrubriek(){

    global $dbh;

    // for loop om te kijken hoe ver je in de de rubriekenboom bent
    for ($loop_teller = 0; $loop_teller <= $_SESSION['formulier_count']; $loop_teller++) {
        $loop_teller_plus = ($loop_teller + 1);

        global ${'formulier_' . $loop_teller_plus . '_actief'};

        // hier wordt gekeken welke optie je gekozen hebt en wordt gekeken hoe ver er gegaan moet worden in de rubriekenboom
        if (isset($_GET["zoek_$loop_teller"]) || $_SESSION["formulier_count"] > $loop_teller) {
            // Deze if is er voor dar er geen subrubriek wordt geopend wanneer er een rubriek hernoemd wordt, of onder de gekozen rubriek ene subrubriek moet komen
            if (isset($_GET["zoek_$loop_teller"]) && $_GET['rubriek_zoek_' . $loop_teller] != 'zoek') {
                $_SESSION['formulier_count'] = $loop_teller;
                $_SESSION["formulier_" . $loop_teller . "_save"] = $_GET["zoek_" . $loop_teller];
                ${'zoek_' . $loop_teller} = $_GET["zoek_$loop_teller"];
            } // Deze if is ervoor als er wordt gezicht in de rubriek naar subrubrieken, zodat de volgende rubriek opent
            else if (isset($_GET["zoek_$loop_teller"])) {
                $_SESSION['formulier_count'] = ${'formulier_' . $loop_teller_plus . '_actief'};
                $_SESSION["formulier_" . $loop_teller . "_save"] = $_GET["zoek_" . $loop_teller];
                ${'zoek_' . $loop_teller} = $_GET["zoek_$loop_teller"];
            } // Hier wordt gekeken welk rubriek is opgeslagen in de session omdat hij niet gekozen is maar wel de hoofdrubriek is van de subrubriek
            else {
                ${'zoek_' . $loop_teller} = $_SESSION["formulier_" . $loop_teller . "_save"];
            }

            ${'zoek_' . $loop_teller . '_rubrieken_query'} = "SELECT * FROM Rubriek WHERE Rubrieknummer = '${"zoek_$loop_teller"}' ORDER BY volgnummer DESC";
            ${'sql_zoek_' . $loop_teller_plus . '_rubrieken'} = $dbh->prepare(${"zoek_" . $loop_teller . "_rubrieken_query"});

            ${'sql_zoek_' . $loop_teller_plus . '_rubrieken'}->execute();
            ${'zoek_' . $loop_teller_plus . '_rubrieken_data'} = ${'sql_zoek_' . $loop_teller_plus . '_rubrieken'}->fetchAll(PDO::FETCH_NUM);
            ${'zoek_' . $loop_teller_plus . '_nummer'} = ${'zoek_' . $loop_teller_plus . '_rubrieken_data'}[0][0];
            $help_de_variabele = ${'zoek_' . $loop_teller_plus . '_nummer'};

            ${'zoek_' . $loop_teller_plus . '_rubrieken_query'} = "SELECT * FROM Rubriek WHERE VorigeRubriek = '$help_de_variabele' ORDER BY volgnummer DESC";
            ${'sql_zoek_' . $loop_teller_plus . '_rubrieken'} = $dbh->prepare(${"zoek_" . $loop_teller_plus . "_rubrieken_query"});
            ${'sql_zoek_' . $loop_teller_plus . '_rubrieken'}->execute();
            global ${'zoek_' . $loop_teller_plus . '_rubrieken_data'};
            ${'zoek_' . $loop_teller_plus . '_rubrieken_data'} = ${'sql_zoek_' . $loop_teller_plus . '_rubrieken'}->fetchAll(PDO::FETCH_NUM);
        }

        if ($_SESSION['formulier_count'] > $loop_teller && ${'zoek_' . $loop_teller_plus . '_rubrieken_data'} != null) {
            echo '<div class="medium-3 large-3 cell">
            <form action="#" method="GET">
                <select name="zoek_' . $loop_teller_plus . '">';

            foreach (${'zoek_' . $loop_teller_plus . '_rubrieken_data'} as ${'zoek_' . $loop_teller_plus . '_rubrieken'}) {
                if (${'zoek_' . $loop_teller_plus . '_rubrieken'}[0] == $_SESSION["formulier_" . $loop_teller_plus . "_save"]) {
                    echo '<option selected value="' . ${'zoek_' . $loop_teller_plus . '_rubrieken'}[0] . '">' . ${'zoek_' . $loop_teller_plus . '_rubrieken'}[1] . '</option>';
                } else {
                    echo '<option value="' . ${'zoek_' . $loop_teller_plus . '_rubrieken'}[0] . '">' . ${'zoek_' . $loop_teller_plus . '_rubrieken'}[1] . '</option>';
                }
            }

            echo '</select>
                <input type="submit" value="zoek" name="rubriek_zoek_' . $loop_teller_plus . '" class="button expanded">
            <input type="submit" value="Hernoem" name="rubriek_zoek_' . $loop_teller_plus . '" class="button expanded">
            <input type="submit" value="Subrubriek invoegen" name="rubriek_zoek_' . $loop_teller_plus . '" class="button expanded">
            <input type="submit" value="Sorteren" name="rubriek_zoek_' . $loop_teller_plus . '" class="button expanded">
            </form>
        </div>';
        }

    }
}

// De formulier die word aangeroepen als er een keuze gemaakt word om de rubriek te hernoemen
function formulier_hernoem()
{
    // De input voor het hernoemen van de rubrieken
    for ($loop_teller = 0;
         $loop_teller <= $_SESSION['formulier_count'];
         $loop_teller++) {
        if (isset($_GET["rubriek_zoek_$loop_teller"])) {
            if ($_GET["rubriek_zoek_$loop_teller"] == 'Hernoem') {
                echo '<form action="#" method="GET">
                    <input type="text" name="hernoem_rubriek" >
                    <input type="hidden" name="nummer_van_hernoem_rubriek" value="' . $_GET["zoek_$loop_teller"] . '"> 
                    <input type="submit" value="Hernoem rubriek" name="rubriek_hernoem" class="button expanded float-right">
            </form>';
                echo '<pre>';
                print_r($_GET);
                echo '</pre>';
            }
        }
    }
}

// De formulier die word aangeroepen als er een keuze gemaakt word om een sububriek in te voegen
function formulier_subrubriek_voegin()
{
    for ($loop_teller = 0;
         $loop_teller <= $_SESSION['formulier_count'];
         $loop_teller++) {
        if (isset($_GET["rubriek_zoek_$loop_teller"])) {
            if ($_GET["rubriek_zoek_$loop_teller"] == 'Subrubriek invoegen') {
                echo '<form action="#" method="GET">
                    <input type="text" name="subrubriek_voeg_toe" >
                    <input type="hidden" name="nummer_van_hoofdrubriek" value="' . $_GET["zoek_$loop_teller"] . '"> 
                    <input type="submit" value="Voeg subrubriek in" name="subrubriek_voegtoe" class="button expanded float-right">
            </form>';
                echo '<pre>';
                print_r($_GET);
                echo '</pre>';
            }
        }
    }
}

// De formulier die word aangeroepen als er een keuze gemaakt word om de rubrieken te sorteren
function formulier_sorteer()
{
    global ${'alle_hoofdrubrieken_data'};

    for ($loop_teller = 0;
         $loop_teller <= $_SESSION['formulier_count'];
         $loop_teller++) {

        global ${'zoek_' . $loop_teller . '_rubrieken_data'};

        if (isset($_GET['zoek_0'])) {
            if (isset($_GET["rubriek_zoek_$loop_teller"])) {
                if ($_GET["rubriek_zoek_$loop_teller"] == 'Sorteren') {
                    echo '<form action="#" method="POST">';
                    foreach (${'alle_hoofdrubrieken_data'} as ${'zoek_' . $loop_teller . '_rubrieken'}) {
                        echo '<label>' . ${'zoek_' . $loop_teller . '_rubrieken'}[1] . '</label>
<input type="text" name="' . ${'zoek_' . $loop_teller . '_rubrieken'}[0] . '" value="' . ${'zoek_' . $loop_teller . '_rubrieken'}[3] . '" >';
                    }
                    echo '<input type="submit" value="Hersorteer rubrieken" name="rubriek_sorteer" class="button expanded float-right">
                    </form>';
                }
            }
        } else if (isset($_GET["rubriek_zoek_$loop_teller"])) {
            if ($_GET["rubriek_zoek_$loop_teller"] == 'Sorteren') {
                echo '<form action="#" method="POST">';
                foreach (${'zoek_' . $loop_teller . '_rubrieken_data'} as ${'zoek_' . $loop_teller . '_rubrieken'}) {
                    echo '<label>' . ${'zoek_' . $loop_teller . '_rubrieken'}[1] . '</label>
<input type="text" name="' . ${'zoek_' . $loop_teller . '_rubrieken'}[0] . '" value="' . ${'zoek_' . $loop_teller . '_rubrieken'}[3] . '" >';
                }
                echo '<input type="submit" value="Hersorteer rubrieken" name="rubriek_sorteer" class="button expanded float-right">
                    </form>';
            }
        }
    }
}

?>

<body>

<?php include_once 'components/header.php'; ?>

<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <h2 class="Rubriekenbeheren_titel">Rubrieken beheren</h2>
        </div>

        <?PHP

        beheerpagina_hoofdrubriek();

        beheerpagina_subrubriek();

        ?>

    </div>

    <?php

    formulier_hernoem();

    formulier_subrubriek_voegin();

    formulier_sorteer();

    ?>

</div>

<?php include "components/scripts.html"; ?>

</body>
</html>