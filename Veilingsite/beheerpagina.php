<?php

include_once "components/connect.php";

include_once "components/meta.php";

include_once 'components/header.php';

if (isset($_SESSION['ingelogde_gebruiker'])) {
    if ($_SESSION['ingelogde_gebruiker'] != 'tigersclaw4') {
        header('Location:index.php');
    }
} else {
    var_dump($_SESSION['ingelogde_gebruiker']);
    header('Location:index.php');
}


// Op deze plaats worden de nieuwe volgnummers naar de database gestuurd indie die zij uitgevoerd
if (isset($_POST['rubriek_sorteer'])) {

    $sorteer_melding = false;

    $sql_wijzig_volgnummer_query = 'update rubriek set Volgnummer = :volgnummer where Rubrieknummer = :rubrieknummer ';
    $sql_wijzig_volgnummer = $dbh->prepare($sql_wijzig_volgnummer_query);

    foreach ($_POST as $Rubrieknummer => $Volgnummer) {
        if ($Rubrieknummer != 'rubriek_sorteer') {
            $sql_wijzig_volgnummer->bindParam(":volgnummer", $Volgnummer);
            $sql_wijzig_volgnummer->bindParam(":rubrieknummer", $Rubrieknummer);

            try {
                $stuur_nieuwe_volgnummers = $sql_wijzig_volgnummer->execute();
            } catch (PDOException $e) {
                $sorteer_melding = true;
            }
        }
    }
} // Dit is de stuk waar de rubrieknaam veranderd wordt als de formulier is ingediend
else if (isset($_POST['rubriek_hernoem_stuur'])) {

    $nieuwe_rubriek_naam = $_POST['hernoem_rubriek'];
    $nummer_van_hernoem_rubriek = $_POST['nummer_van_hernoem_rubriek'];

    $hernoem_rubriek_melding = false;

    if (strlen($nieuwe_rubriek_naam) <= 40) {
        $sql_hernoem_rubriek_query = 'update Rubriek set Rubrieknaam = :rubrieknaam where Rubrieknummer = :rubrieknummer';
        $sql_nieuwe_rubrieknaam = $dbh->prepare($sql_hernoem_rubriek_query);

        $sql_nieuwe_rubrieknaam->bindParam(":rubrieknaam", $nieuwe_rubriek_naam);
        $sql_nieuwe_rubrieknaam->bindParam(":rubrieknummer", $nummer_van_hernoem_rubriek);

        try {
            $stuur_nieuwe_rubrieknaam = $sql_nieuwe_rubrieknaam->execute();
        } catch (PDOException $e) {

        }
    } else {
        $hernoem_rubriek_melding = true;
    }
} // Het versturen van de nieuwe Subrubriek naar de database
else if (isset($_POST["subrubriek_voegtoe"])) {

    $sql_neem_hoogste_rubrieknummer = "select MAX(RubriekNummer) from Rubriek";
    $sql_hoogste_rubrieknummer = $dbh->prepare($sql_neem_hoogste_rubrieknummer);
    $sql_hoogste_rubrieknummer->execute();
    $hoogste_rubrieknummer_data = $sql_hoogste_rubrieknummer->fetchAll(PDO::FETCH_NUM);
    $hoogste_rubrieknummer = $hoogste_rubrieknummer_data[0][0];
    $nieuw_hoogste_hoofdrubrieknummer = $hoogste_rubrieknummer += 1;

    //Hoofdrubriek van de subrubriek
    $nummer_van_hoofdrubriek = $_POST['nummer_van_hoofdrubriek'];

    // Naam van de nieuwe rubriek
    $nieuw_subrubrieknaam = $_POST['nieuwe_subrubriek'];

    // VOlgnummer toevoegen
    $nieuw_volgnummer = 0;

    $subrubriek_melding = false;

    if (strlen($nieuw_subrubrieknaam) <= 40) {
        /* De nieuwe subrubriek wordt met een query in de database opgeslagen */
        $sql_voeg_nieuwe_subrubriek_toe = "insert into Rubriek ([RubriekNummer], [RubriekNaam], [VorigeRubriek], [Volgnummer]) values (:nieuw_hoogste_rubrieknummer, :nieuw_rubrieknaam, :nieuw_rubriek, :nieuw_volgnummer)";
        $voeg_nieuwe_subrubriek_toe = $dbh->prepare($sql_voeg_nieuwe_subrubriek_toe);


        if ($voeg_nieuwe_subrubriek_toe) {
            $voeg_nieuwe_subrubriek_toe->bindParam(":nieuw_hoogste_rubrieknummer", $nieuw_hoogste_hoofdrubrieknummer, PDO::PARAM_STR);
            $voeg_nieuwe_subrubriek_toe->bindParam(":nieuw_rubrieknaam", $nieuw_subrubrieknaam, PDO::PARAM_STR);
            $voeg_nieuwe_subrubriek_toe->bindParam(":nieuw_rubriek", $nummer_van_hoofdrubriek, PDO::PARAM_STR);
            $voeg_nieuwe_subrubriek_toe->bindParam(":nieuw_volgnummer", $nieuw_volgnummer, PDO::PARAM_STR);


            try {
                $voeg_nieuwe_subrubriek_toe->execute();
                echo "<br>Succesvol toegevoegd aan database</br>";
            } catch (PDOException $e) {
                echo $e->getMessage();
                echo "<br>Fout met toevoegen aan database</br>";
            }
        }
    } else {
        $subrubriek_melding = true;
    }
}

function rubrieken()
{
    // Benoemen van de globale variabelen
    global $dbh;

    if (!isset($_SESSION['formulier_teller'])) {
        $_SESSION['formulier_teller'] = 0;
        $_SESSION['hoofdrubriek'] = array();
    } else {
        // Check of er een rubriek gekozen is of of er verdere formuliergegevens worden ingevuld
        if (isset($_POST['rubriek_zoek_getal'])) {
            $hoofdrubriek = $_POST["zoek_" . $_POST['rubriek_zoek_getal'] . ""];
            $_SESSION['formulier_teller'] = $_POST['rubriek_zoek_getal'] += 1;
            $_SESSION['hoofdrubriek'][$_POST['rubriek_zoek_getal']] = $hoofdrubriek;
        }
    }

    if (isset($_POST['rubriek_hernoem']) || isset($_POST["rubriek_subrubriek_invoegen"]) || isset($_POST["rubriek_sorteren"])) {
        $_SESSION['formulier_teller'] = ($_SESSION['formulier_teller'] - 1);
    }


    for ($x = 0; $x <= $_SESSION['formulier_teller']; $x++) {
        $eenmeerdanforloopvariabele = 1 + $x;
        if ($x == 0) {
            $rubriek_zoek = $_SESSION['hoofdrubriek'][0] = -1;
        } else {
            $rubriek_zoek = $_SESSION['hoofdrubriek'][$x];
        }

        global $neem_rubrieken_data;
        // Het ophalen van de rubrieken
        $neem_rubrieken_query = "select * from rubriek where vorigeRubriek = '" . $rubriek_zoek . "' ORDER BY 'Volgnummer' DESC ";
        $neem_rubrieken = $dbh->prepare($neem_rubrieken_query);
        $neem_rubrieken->execute();
        $neem_rubrieken_data = $neem_rubrieken->fetchAll(PDO::FETCH_NUM);

        // Als de klant van iConcepts nog niet op de laagste niveau van de rubrieken is
        if (sizeof($neem_rubrieken_data) != 0) {
            unset($_SESSION['gekozen_rubrieknummer']);
            // Het laten zien van de rubriek formulier
            echo "<div class='medium-3 large-3 cell'>
            <form action='#' method='POST'>
              <select name='zoek_" . $x . "'>";
            foreach ($neem_rubrieken_data as $rubriek) {
                if ($rubriek[0] == $_SESSION["hoofdrubriek"][$eenmeerdanforloopvariabele]) {
                    echo "<option selected value='" . $rubriek[0] . "'>" . $rubriek[1] . "</option>";
                } else {
                    echo "<option value='" . $rubriek[0] . "'>" . $rubriek[1] . "</option>";
                }
            }
            echo "    </select>
             <input type='hidden' name='rubriek_zoek_getal' value='" . $x . "' >
            <input type='submit' value='zoek' name='rubriek_zoek' class='button expanded'>
            <input type='submit' value='hernoem' name='rubriek_hernoem' class='button expanded'>
            <input type='submit' value='subrubriek invoegen' name='rubriek_subrubriek_invoegen' class='button expanded'>
            <input type='submit' value='sorteren' name='rubriek_sorteren' class='button expanded'>
            </form>
          </div>";
        } // Als de klant van iConcepts op de laagste niveau is
        else if (sizeof($neem_rubrieken_data) == 0) {
            $rubriek_zoek = $_SESSION['hoofdrubriek'][$x];
            $neem_rubrieken_query = "select * from rubriek where Rubrieknummer = '" . $rubriek_zoek . "'";
            $neem_rubrieken = $dbh->prepare($neem_rubrieken_query);
            $neem_rubrieken->execute();
            $neem_rubrieken_data = $neem_rubrieken->fetchAll(PDO::FETCH_NUM);
            $_SESSION['gekozen_rubrieknaam'] = $neem_rubrieken_data[0][1];
            $_SESSION['gekozen_rubrieknummer'] = $neem_rubrieken_data[0][0];
        }
    }
}

// De formulier die word aangeroepen als er een keuze gemaakt word om de rubriek te hernoemen
function formulier_hernoem()
{
    $marge = 1;
    // De input voor het hernoemen van de rubrieken
    for ($loop_teller = 0;
         $loop_teller <= $_SESSION['formulier_teller'];
         $loop_teller++) {
        if (isset($_POST["rubriek_zoek_getal"])) {
            if ((isset($_POST["rubriek_hernoem"]) == 'hernoem') && $_POST["rubriek_zoek_getal"] == ($loop_teller + $marge)) {
                echo '<form action="#" method="POST"> <label>Nieuwe naam</label>
                    <input type="text" name="hernoem_rubriek" >
                    <input type="hidden" name="nummer_van_hernoem_rubriek" value="' . $_POST["zoek_$loop_teller"] . '"> 
                    <input type="submit" value="Hernoem rubriek" name="rubriek_hernoem_stuur" class="button expanded float-right">
            </form>';
            }
        }
    }
}

// De formulier die word aangeroepen als er een keuze gemaakt word om een sububriek in te voegen
function formulier_subrubriek_voegin()
{
    $marge = 1;
    // De input voor het hernoemen van de rubrieken
    for ($loop_teller = 0;
         $loop_teller <= $_SESSION['formulier_teller'];
         $loop_teller++) {
        if (isset($_POST["rubriek_zoek_getal"])) {
            if ((isset($_POST["rubriek_subrubriek_invoegen"]) == 'subrubriek invoegen') && $_POST["rubriek_zoek_getal"] == ($loop_teller + $marge)) {
                echo '<form action="#" method="POST"> <label>Naam nieuwe subrubriek</label>
                    <input type="text" name="nieuwe_subrubriek" >
                    <input type="hidden" name="nummer_van_hoofdrubriek" value="' . $_POST["zoek_$loop_teller"] . '"> 
                    <input type="submit" value="Voeg rubriek toe" name="subrubriek_voegtoe" class="button expanded float-right">
            </form>';
            }
        }
    }
}

// De formulier die word aangeroepen als er een keuze gemaakt word om de rubrieken te sorteren
function formulier_sorteer()
{
    global ${'alle_hoofdrubrieken_data'};
    global $neem_rubrieken_data;
    $marge = 1;
    for ($loop_teller = 0;
         $loop_teller <= $_SESSION['formulier_teller'];
         $loop_teller++) {

        if (isset($_POST["rubriek_zoek_getal"])) {
            if ((isset($_POST["rubriek_sorteren"]) == 'sorteren') && $_POST["rubriek_zoek_getal"] == ($loop_teller + $marge)) {
                echo '<form action="#" method="POST">';
                foreach ($neem_rubrieken_data as ${'zoek_' . $loop_teller . '_rubrieken'}) {
                    echo '<label>' . ${'zoek_' . $loop_teller . '_rubrieken'}[1] . '</label>
<input type="number" name="' . ${'zoek_' . $loop_teller . '_rubrieken'}[0] . '" value="' . ${'zoek_' . $loop_teller . '_rubrieken'}[3] . '" >';
                }
                echo '<input type="submit" value="Hersorteer rubrieken" name="rubriek_sorteer" class="button expanded float-right">
                    </form>';
            }
        }

    }
}

?>

<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <h2 class="registreren_titel">Beheerpagina</h2>
        </div>
    </div>
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <div class='medium-12 large-12 cell verkopen-object-box'>
                <h4>Rubriek</h4>
                <div class='grid-x grid-padding-x'>
                    <?PHP rubrieken(); ?>
                </div>

                <?php
                formulier_hernoem();

                formulier_subrubriek_voegin();

                formulier_sorteer();

                if (isset($hernoem_rubriek_melding)) {
                    if ($hernoem_rubriek_melding) {
                        echo '<span class="form-error is-visible" id="exemple2Error">De rubrieknaam mag niet meer dan 40 karakters bevatten.</span>';
                    } else {
                        echo '<span class="is-visible" id="exemple2Error">Rubriek succesvol hernoemd.</span>';
                    }
                }
                if (isset($subrubriek_melding)) {
                    if ($subrubriek_melding) {
                        echo '<span class="form-error is-visible" id="exemple2Error">De rubrieknaam mag niet meer dan 40 karakters bevatten.</span>';
                    } else {
                        echo '<span class="is-visible" id="">Subrubriek succesvol toegevoegd.</span>';
                    }
                }
                if (isset($sorteer_melding)) {
                    if($sorteer_melding){
                        echo '<span class="form-error is-visible" id="exemple2Error">Het sorteren van de rubrieken is mislukt.</span>';
                    } else {
                        echo '<span class="is-visible" id="">Rubrieken succesvol gesorteerd.</span>';
                    }
                }

                ?>

            </div>
        </div>
    </div>
</div>
<?php include "components/scripts.html"; ?>
</body>
</html>

