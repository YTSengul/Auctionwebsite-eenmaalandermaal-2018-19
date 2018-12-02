<?php

session_start();

include_once "components/connect.php";

// Dit is de stuk waar de rubrieknaam veranderd wordt als de formulier is ingediend
if (isset($_GET['rubriek_hernoem'])) {

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
if (isset($_GET["subrubriek_voegtoe"])) {

    $sql_neem_hoogste_rubrieknummer = "select MAX(Rubrieknummer) from Rubriek";
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
    $sql_voeg_nieuwe_subrubriek_toe = "insert into rubriek ([Rubrieknummer], [Rubrieknaam], [Rubriek], [Volgnr]) values (:nieuw_hoogste_rubrieknummer, :nieuw_rubrieknaam, :nieuw_rubriek, :nieuw_volgnummer)";
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
$alle_rubrieken_query = "SELECT * FROM Rubriek WHERE Rubriek = -1";
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
        //echo '<br><br>123<br><br>';
    }

    //$idk = $_GET["'rubriek_zoek_".$x."'"];
    //echo $idk . 'aaaaaaaaaaaaaaaaaaaaaa';
    //echo '<br>-<br>' . $_SESSION['formulier_0_save'] . '<br>-<br>-';
    //echo $_GET['rubriek_zoek_0'];
    //echo '<br>-<br>' . $_SESSION['formulier_0_save'] . '<br>-<br>-';
    if (isset($_GET["rubriek_zoek_$x"])) {
        $_SESSION["formulier_" . $x . "_save"] = $_GET["zoek_$x"];
        //echo '<br><br>456<br><br>';
    }
}

?>

<!doctype html>
<html class="" lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EenmaalAndermaal</title>
    <link rel="stylesheet" href="foundation/css/foundation.css">
    <link rel="stylesheet" href="foundation/css/app.css">
</head>

<body>

<?php include_once 'components/header.php'; ?>

<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <h2 class="Rubriekenbeheren_titel">Rubrieken beheren</h2>
        </div>

        <?PHP
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
            </form>
        </div>';
        ?>

        <?PHP

        for ($loop_teller = 0; $loop_teller <= $_SESSION['formulier_count']; $loop_teller++) {
            $loop_teller_plus = ($loop_teller + 1);
            //echo '<br>a'.$loop_teller;
            //echo '<br>b'.$loop_teller_plus;
            if (isset($_GET["zoek_$loop_teller"]) || $_SESSION["formulier_count"] > $loop_teller) {

                if (isset($_GET["zoek_$loop_teller"])) {
                    $_SESSION['formulier_count'] = ${'formulier_' . $loop_teller_plus . '_actief'};
                    $_SESSION["formulier_" . $loop_teller . "_save"] = $_GET["zoek_" . $loop_teller];
                    ${'zoek_' . $loop_teller} = $_GET["zoek_$loop_teller"];
                } else {
                    ${'zoek_' . $loop_teller} = $_SESSION["formulier_" . $loop_teller . "_save"];
                }

                ${'zoek_' . $loop_teller . '_rubrieken_query'} = "SELECT * FROM Rubriek WHERE Rubrieknummer = '${"zoek_$loop_teller"}'";
                ${'sql_zoek_' . $loop_teller_plus . '_rubrieken'} = $dbh->prepare(${"zoek_" . $loop_teller . "_rubrieken_query"});

                ${'sql_zoek_' . $loop_teller_plus . '_rubrieken'}->execute();
                ${'zoek_' . $loop_teller_plus . '_rubrieken_data'} = ${'sql_zoek_' . $loop_teller_plus . '_rubrieken'}->fetchAll(PDO::FETCH_NUM);
                ${'zoek_' . $loop_teller_plus . '_nummer'} = ${'zoek_' . $loop_teller_plus . '_rubrieken_data'}[0][0];
                $help_de_variabele = ${'zoek_' . $loop_teller_plus . '_nummer'};

                ${'zoek_' . $loop_teller_plus . '_rubrieken_query'} = "SELECT * FROM Rubriek WHERE Rubriek = '$help_de_variabele'";
                ${'sql_zoek_' . $loop_teller_plus . '_rubrieken'} = $dbh->prepare(${"zoek_" . $loop_teller_plus . "_rubrieken_query"});
                ${'sql_zoek_' . $loop_teller_plus . '_rubrieken'}->execute();
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
            </form>
        </div>';
            }


        }

        //echo '<pre>';
        //print_r(get_defined_vars());
        //echo '</pre>';

        ?>

    </div>

    <?php
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

    ?>

</body>
</html>