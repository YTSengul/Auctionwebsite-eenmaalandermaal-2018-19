<?php

include_once "components/connect.php";

include_once "components/meta.php";

if (isset($_POST['plaats_veiling'])) {
    var_dump($_POST);
    $titel = $_POST['titel'];
    $omschrijving = $_POST['omschrijving'];
    $betalingswijzen = $_POST['betalingswijzen'];
    $verzendkosten = $_POST['verzendkosten'];
    $looptijd = $_POST['looptijd'];
    $plaatsnaam = $_POST['plaatsnaam'];
    $land = $_POST['land'];

    $sql_plaats_voorwerp_query = "";


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
        } else {
            $hoofdrubriek = $_SESSION['gekozen_rubrieknummer'];
        }
    };

    for ($x = 0; $x <= $_SESSION['formulier_teller']; $x++) {
        $eenmeerdanforloopvariabele = 1 + $x;
        if ($x == 0) {
            $rubriek_zoek = $_SESSION['hoofdrubriek'][0] = -1;
        } else {
            $rubriek_zoek = $_SESSION['hoofdrubriek'][$x];
        }

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
                }
                echo "<option value='" . $rubriek[0] . "'>" . $rubriek[1] . "</option>";
            }
            echo "    </select>
             <input type='hidden' name='rubriek_zoek_getal' value='" . $x . "' >
            <input type='submit' value='zoek' name='rubriek_zoek' class='button expanded'>
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

function voorwerp_en_levering()
{
    global $dbh;
    $sql_betalingswijzen_query = "select * from Betalingswijzen";
    $sql_betalingswijzen = $dbh->prepare($sql_betalingswijzen_query);
    $sql_betalingswijzen->execute();
    $sql_betalingswijzen_data = $sql_betalingswijzen->fetchAll(PDO::FETCH_NUM);

    // check of er een rubriek is gekozen op laagste niveau
        echo "<form method='POST' action='verkopen_object.php' enctype='multipart/form-data'>
                <div class='medium-12 large-12 float-center cell verkopen-object-box'>
                    <h4>Voorwerp en levering</h4>
                    <label>Titel*
                        <input name='titel' type='text' placeholder='Vul hier de titel van de veiling in.' required>
                    </label>
                    <label> Omschrijving
                        <textarea rows='8' name='omschrijving' type='' placeholder='. . .'></textarea>
                    </label>
                    <div class='grid-x grid-padding-x'>
                        <div class='medium-3 cell'>
                            <input type='file' name='foto_1'><br>
                            <input type='file' name='foto_2'>
                            <label class='hide-for-small-only'>*Foto 1 wordt gekozen als primaire foto</label>
                        </div>
                        <div class='medium-3 cell'>
                            <input type='file' name='foto_3'>
                            <input type='file' name='foto_4'>
                        </div>
                        <div class='medium-6 cell'>
                            <label>Betalingswijzen*
                                <select name='betalingswijzen'>";
                                foreach ($sql_betalingswijzen_data as $betalingswijzen) {
                                if ($betalingswijzen[0] == 'Creditcard') {
                                    echo "<option value='" . $betalingswijzen[0] . "' selected='selected' >" . $betalingswijzen[0] . "</option>";
                                } else {
                                    echo "<option value='" . $betalingswijzen[0] . "'>" . $betalingswijzen[0] . "</option>";
                                }
                            }
                        echo"    </select>
                            </label>
                            <label>Verzendkosten
                                <input type='number' name='verzendkosten' placeholder='0.00'>
                            </label>
                        </div>
                        <div class='medium-6 cell'>
                            <label>Startprijs*
                            <input type='number' placeholder='Vul een startprijs in' required>
                            </label>
                        </div>
                        <div class='medium-6 cell'>
                            <label>Looptijd*
                                <select name='looptijd'>
                                    <option>1</option>
                                    <option>3</option>
                                    <option>5</option>
                                    <option selected >7</option>
                                    <option>10</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
                ";
}

function contactgegevens()
{
    global $dbh;
    $sql_landen_query = "select * from Land";
    $sql_landen = $dbh->prepare($sql_landen_query);
    $sql_landen->execute();
    $sql_landen_data = $sql_landen->fetchAll(PDO::FETCH_NUM);

    echo "
                <div class='medium-12 large-12 float-center cell verkopen-object-box'>
                    <h4>Contactgegevens</h4>
                    <div class='grid-x grid-padding-x'>
                        <div class='medium-6 large-6 cell'>
                            <label>Plaatsnaam*
                                <input name='plaatsnaam' type='text' placeholder='Amsterdam, Ro...' required>
                            </label>
                        </div>
                        <div class='medium-6 large-6 cell'>
                            <label>Land*
                                <select name='land'>";
                                foreach ($sql_landen_data as $landen) {
                                if ($landen[1] == 'Nederland') {
                                    echo "<option value='" . $landen[0] . "' selected='selected' >" . $landen[1] . "</option>";
                                } else {
                                    echo "<option value='" . $landen[0] . "'>" . $landen[1] . "</option>";
                                }
                            }
                                echo"</select>
                            </label>
                        </div>
                    </div>
                    <input type='submit' name='plaats_veiling' value='Veiling plaatsen' class='button expanded'>
                </div>
            </form>";
}

?>

<?php include_once 'components/header.php'; ?>

<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <h2 class="registreren_titel">Plaats veiling</h2>
        </div>
    </div>
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
                <div class='medium-12 large-12 cell verkopen-object-box'>
                    <h4>Rubriek</h4>
                    <div class='grid-x grid-padding-x'>
                        <?PHP rubrieken(); ?>
                    </div>
                </div>
            <?PHP

            // vorens wordt er gecheckt of de bestand een afbeelding is
                    if ($_FILES['foto_1']['type'] == 'image/jpeg' || $_FILES['foto_1']['type'] == 'image/png') {
                        $info = pathinfo($_FILES['foto_1']['name']);
                        $ext = $info['extension']; // get the extension of the file
                        $newname = "newname1." . $ext;
                        $target = 'images/' . $newname;
                        move_uploaded_file($_FILES['foto_1']['tmp_name'], $target);
                    } // Als de bestand geen geen PNG of JPG is.
                    else {
                        echo 'is geen afbeelding!!!';
                    }

            if (isset($_SESSION['gekozen_rubrieknummer']) && isset($_SESSION['gekozen_rubrieknummer'])) {
                voorwerp_en_levering();
                contactgegevens();
            }

            ?>
        </div>
    </div>
</div>

<?php include "components/scripts.html"; ?>

</body>
</html>