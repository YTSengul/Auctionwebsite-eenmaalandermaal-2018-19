<?php

include_once "components/connect.php";

include_once "components/meta.php";

// Check of de gebruiker is ingelogd
// Hier moe took komen te staan of de gebruiker een verkoper is
// later wordt de gebruiker naar zijn eigen pagina gestuurd
if (!isset($_SESSION['ingelogde_gebruiker'])){
    header('location:pre-registreer.php');
}

// De boolean die uit gaat als de formulier niet klopt
$formulier_check = true;

// de boolean voor de afbeelding. gaat aan als er een onjuist document word geladen
$afbeelding_onjuist = false;

function image_processor()
{
    global $nieuw_voorwerpnummer;
    global $afbeelding_onjuist;
    global $formulier_check;
    global $image_names;
    $imagecounter = 1;
    foreach ($_FILES as $image) {
        // Er wordt eerst een check gedaan of er wel iets is geüpload
        if ($image['type'] == 'image/jpeg' || $image['type'] == 'image/png') {
            // Hier word de afbeelding in de database geplaatst
            // vorens wordt er gecheckt of de bestand een afbeelding is
            $info = pathinfo($image['name']);
            // De type afbeelding wordt opgenomen in een variabele
            $ext = $info['extension'];
            // De nieuwe naam wordt toegekend aan de foto
            $newname = "dt_" . $imagecounter . "_" . $nieuw_voorwerpnummer . "." . $ext;
            array_push($image_names, $newname);
            $target = 'img/' . $newname;
            move_uploaded_file($image['tmp_name'], $target);

            // De imagecounter gaat hier pas ++ omdat anders de volgorde van de afbeeldingen niet klopt
            $imagecounter++;
        } // Als de bestand geen geen PNG of JPG is.
        elseif ($image["error"] != 4) {
            $formulier_check = false;
            $afbeelding_onjuist = true;
        }
    }
}

if (isset($_POST['plaats_veiling'])) {

    //Het opnemen van een nieuwe rubrieknummer voor de voorwerp
    $sql_voorwerpnummer_query = "select top 1 Voorwerpnummer from voorwerp order by 1 desc";
    $sql_voorwerpnummer = $dbh->prepare($sql_voorwerpnummer_query);
    $sql_voorwerpnummer->execute();
    $sql_voorwerpnummer_data = $sql_voorwerpnummer->fetchAll(PDO::FETCH_NUM);

    //De nieuwe voorwerpnummer toekennen aan een variabele
    $nieuw_voorwerpnummer = $sql_voorwerpnummer_data[0][0] + 1;

    //De informatie uit de formulier wordt toegekend aan de variabelen
    $titel = $_POST['titel'];
    $beschrijving = $_POST['beschrijving'];
    $startprijs = $_POST['startprijs'];
    $betalingswijze = $_POST['betalingswijze'];
    $verzendkosten = (int)$_POST['verzendkosten'];
    $looptijd = $_POST['looptijd'];
    $plaatsnaam = $_POST['plaatsnaam'];
    $land = $_POST['land'];
    $betalingsinstructie = $_POST['betalingsinstructie'];
    $verzendinstructies = $_POST['verzendinstructies'];
    $beginmoment = date('Y-m-d H:i:s');
    $rubriekoplaagsteniveau = $_SESSION['gekozen_rubrieknummer'];
    $verkoper = $_SESSION['ingelogde_gebruiker'];

    // Hier wordt gecheckt of de titel minimaal 4 characters bevat
    if (strlen($titel) < 4) {
        $formulier_check = false;
        $titel_onjuist = true;
    }

    // Er wordt een check gevoerd op de startprijs of hij hoger dan €1.00 is
    if ($startprijs < 1) {
        $formulier_check = false;
        $startprijs_onjuist = true;
    }

    // Er wordt gecheckt of de beschrijving niet langer is dan 5000 tekens.
    if (strlen($beschrijving) > 5000) {
        $formulier_check = false;
        $beschrijving_onjuist = true;
    }

    // Hier wordt gecheckt of de betalingsinstructie niet langer is dan 400 tekens.
    if (strlen($betalingsinstructie) > 400) {
        $formulier_check = false;
        $betalingsinstructie_onjuist = true;
    }

    // Hier wordt gecheckt of de verzendinstructies niet langer is dan 400 tekens.
    if (strlen($verzendinstructies) > 400) {
        $formulier_check = false;
        $verzendinstructies_onjuist = true;
    }

    // Hier wordt gecheckt of de verzendinstructies niet langer is dan 400 tekens.
    if (strlen($plaatsnaam) > 85) {
        $formulier_check = false;
        $plaatsnaam_onjuist = true;
    }

    // Hier worden de namen van de toegevoegde afbeeldingen opgeslagen
    $image_names = [];
    // De Geüploadde bestanden worden gecheckt
    image_processor();

    if ($formulier_check == true) {
        // De thumbail wordt hier als laatst nog gegeven aan een variabele om hem in de 'voorwerp' tabel toe te kunnen toevoegen
        $thumbnail = (string)$image_names[0];

        // Met deze statement word de veiling geplaatst. De identity wordt voor de query aan en na de query uit gezet.
        $sql_plaats_voorwerp_query = "SET IDENTITY_INSERT voorwerp on; insert into voorwerp (Voorwerpnummer, Titel, Beschrijving, Startprijs, Betalingswijze, Betalingsinstructie, Plaatsnaam, Land, Looptijd, BeginMoment,  Verzendkosten, Verzendinstructies, Verkoper, Thumbnail) values (:voorwerpnummer, :titel, :beschrijving, :startprijs, :betalingswijze, :betalingsinstructie, :plaatsnaam, :land, :looptijd, :beginmoment, :verzendkosten, :verzendinstructies, :verkoper, :thumbnail); SET IDENTITY_INSERT voorwerp off";
        $sql_plaats_voorwerp = $dbh->prepare($sql_plaats_voorwerp_query);
        $sql_plaats_voorwerp->bindParam(":voorwerpnummer", $nieuw_voorwerpnummer);
        $sql_plaats_voorwerp->bindParam(":titel", $titel);
        $sql_plaats_voorwerp->bindParam(":beschrijving", $beschrijving);
        $sql_plaats_voorwerp->bindParam(":startprijs", $startprijs);
        $sql_plaats_voorwerp->bindParam(":betalingswijze", $betalingswijze);
        $sql_plaats_voorwerp->bindParam(":betalingsinstructie", $betalingsinstructie);
        $sql_plaats_voorwerp->bindParam(":plaatsnaam", $plaatsnaam);
        $sql_plaats_voorwerp->bindParam(":land", $land);
        $sql_plaats_voorwerp->bindParam(":looptijd", $looptijd);
        $sql_plaats_voorwerp->bindParam(":beginmoment", $beginmoment);
        $sql_plaats_voorwerp->bindParam(":verzendkosten", $verzendkosten);
        $sql_plaats_voorwerp->bindParam(":verkoper", $verkoper);
        $sql_plaats_voorwerp->bindParam(":verzendinstructies", $verzendinstructies);
        $sql_plaats_voorwerp->bindParam(":thumbnail", $thumbnail);
        $sql_plaats_voorwerp->execute();

        // Met deze statement word de veiling in de rubriekoplaagsteniveau tabel geplaatst
        $sql_plaats_rubriekoplaagsteniveau_query = 'insert into VoorwerpInRubriek (Voorwerp, RubriekOpLaagsteNiveau) values (:voorwerpnummer, :rubrieknummer)';
        $sql_plaats_rubriekoplaagsteniveau = $dbh->prepare($sql_plaats_rubriekoplaagsteniveau_query);
        $sql_plaats_rubriekoplaagsteniveau->bindParam(':voorwerpnummer', $nieuw_voorwerpnummer);
        $sql_plaats_rubriekoplaagsteniveau->bindParam(':rubrieknummer', $rubriekoplaagsteniveau);
        $sql_plaats_rubriekoplaagsteniveau->execute();

        // De afbeeldingen worden toegevoegd aan de database
        foreach ($image_names as $name){
        $sql_add_image_query = "insert into Bestand (Filenaam, Voorwerp) values (:filenaam, :voorwerp)";
        $sql_add_image = $dbh->prepare($sql_add_image_query);
        $sql_add_image->bindParam(':filenaam', $name);
        $sql_add_image->bindParam(':voorwerp', $nieuw_voorwerpnummer);
        $sql_add_image->execute();
        }

        header('location:mijn_profiel.php');

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

function titel_check()
{
    global $titel_onjuist;
    global $titel;

    if (isset($titel_onjuist)) {
        echo '<label>Titel*
            <input name="titel" class="is-invalid-input" type="text" placeholder="Vul hier de titel van de veiling in." value="' . $titel . '" required>
            <span class="form-error is-visible" id="exemple2Error">Gebruikersnaam moet minimaal 4 tekens bevatten.</span></label>';
    } else {
        echo '<label>Titel*
            <input name="titel" type="text" placeholder="Vul hier de titel van de veiling in." value="' . $titel . '" required></label>';
    }
}

function startprijs_check()
{
    global $startprijs_onjuist;
    global $startprijs;

    if (isset($startprijs_onjuist)) {
        echo "<div class='medium-6 cell'>
                <label>Startprijs*
                    <input type='number' class='is-invalid-input' name='startprijs' placeholder='Vul een startprijs in'  value='$startprijs' required>
                    <span class='form-error is-visible' id='exemple2Error'>Startprijs moet minimaal €1 zijn.</span>
                </label>
              </div>";
    } else {
        echo "<div class='medium-6 cell'>
                <label>Startprijs*
                    <input type='number' name='startprijs' placeholder='Vul een startprijs in' value='$startprijs' required>
                </label>
              </div>";
    }
}

function beschrijving_check()
{
    global $beschrijving_onjuist;
    global $beschrijving;
    if (isset($beschrijving_onjuist)) {
        echo "<label> beschrijving
                        <textarea rows='8' class='is-invalid-input' name='beschrijving' type='' placeholder='. . .'>$beschrijving</textarea>
                    <span class='form-error is-visible' id='exemple2Error'>De beschrijving mag maar 5000 tekens lang zijn.</span>
                    </label>
               </label>";
    } else {
        echo "<label> beschrijving
                        <textarea rows='8' name='beschrijving' type='' placeholder='. . .'>$beschrijving</textarea>
                    </label>
              </label>";
    }
}

function afbeeldingen()
{
    global $afbeelding_onjuist;

    echo "<div class='medium-3 cell'>
            <input type='file' name='foto_0' ><br>
                <input type='file' name='foto_1' >
                    <label class='hide-for-small-only'>*Foto 1 wordt gekozen als primaire foto</label>
            </div>
                <div class='medium-3 cell'>
                    <input type='file' name='foto_2' >
                    <input type='file' name='foto_3' >";
    if ($afbeelding_onjuist == true) {
        echo "<span class='form-error is-visible' id='exemple2Error'>De beschrijving mag maar 5000 tekens lang zijn.</span>";
    }
    echo "</div>";
}

function betalingswijze()
{

    global $dbh;

    $sql_betalingswijze_query = "select * from Betalingswijzen";
    $sql_betalingswijze = $dbh->prepare($sql_betalingswijze_query);
    $sql_betalingswijze->execute();
    $sql_betalingswijze_data = $sql_betalingswijze->fetchAll(PDO::FETCH_NUM);

    global $betalingswijze;

    if (isset($betalingswijze)) {
        $primaire_betalingswijze = $betalingswijze;
    } else {
        $primaire_betalingswijze = 'Creditcard';
    }

    echo "<div class='medium-6 cell'>
          <label>Betalingswijze*
          <select name='betalingswijze'>";

    foreach ($sql_betalingswijze_data as $betalingswijze) {
        if ($betalingswijze[0] == $primaire_betalingswijze) {
            echo "<option value='" . $betalingswijze[0] . "' selected='selected' >" . $betalingswijze[0] . "</option>";
        } else {
            echo "<option value='" . $betalingswijze[0] . "'>" . $betalingswijze[0] . "</option>";
        }
    }

    echo "</select>
          </label>
          </div>";
}

function looptijd()
{
    global $looptijd;
    $looptijd_dagen = [1, 3, 5, 7, 10];

    if (isset($looptijd)) {
        $selected_day = $looptijd;
    } else {
        $selected_day = 7;
    }

    echo "<div class='medium-6 cell'>
              <label>Looptijd*
              <select name='looptijd'>";
    foreach ($looptijd_dagen as $dag) {
        if ($dag == $selected_day) {
            echo "<option selected >$dag</option>";
        } else {
            echo "<option>$dag</option>";
        }
    }
    echo "    </select>
          </label>
          </div>";
}

function betalingsinstructie_check()
{
    global $betalingsinstructie_onjuist;
    global $betalingsinstructie;

    if (isset($betalingsinstructie_onjuist)) {
        echo "<label> Betalingsinstructie
                        <textarea rows='2' class='is-invalid-input' name='betalingsinstructie' type='' placeholder='. . .'>" . $betalingsinstructie . "</textarea>
                        <span class='form-error is-visible' id='exemple2Error'>De betalingsinstructie mag maar 400 tekens lang zijn.</span>
                        ";
    } else {
        echo "<label> Betalingsinstructie
                        <textarea rows='2' name='betalingsinstructie' type='' placeholder='. . .'>" . $betalingsinstructie . "</textarea>";
    }
}

function verzendkosten()
{
    global $verzendkosten;

    echo "<label>Verzendkosten
        <input type='number' name='verzendkosten' placeholder='0.00' value='" . $verzendkosten . "' >
      </label>";
}

function verzendinstructies_check()
{
    global $verzendinstructies_onjuist;
    global $verzendinstructies;

    if (isset($verzendinstructies_onjuist)) {
        echo "<label> Verzendinstructies
                     <textarea rows='2' name='verzendinstructies' class='is-invalid-input' type='' placeholder='. . .'>" . $verzendinstructies . "</textarea>
                     <span class='form-error is-visible' id='exemple2Error'>De verzendinstructie mag maar 400 tekens lang zijn.</span>
              </label>
                        ";
    } else {
        echo "<label> Verzendinstructies
                     <textarea rows='2' name='verzendinstructies' type='' placeholder='. . .'>" . $verzendinstructies . "</textarea>
              </label>";
    }
}

function plaatsnaam_check()
{
    global $plaatsnaam_onjuist;
    global $plaatsnaam;

    if (isset($plaatsnaam_onjuist)) {
        echo "<div class='medium-6 large-6 cell'>
                <label>Plaatsnaam*
                    <input name='plaatsnaam' class='is-invalid-input' type='text' placeholder='Amsterdam, Ro...' value='" . $plaatsnaam . "' required>
                    <span class='form-error is-visible' id='exemple2Error'>De plaatsnaam mag maar 85 tekens lang zijn.</span>
                </label>
              </div>";
    } else {
        echo "<div class='medium-6 large-6 cell'>
                <label>Plaatsnaam*
                    <input name='plaatsnaam' type='text' placeholder='Amsterdam, Ro...' value='" . $plaatsnaam . "' required>
                </label>
              </div>";
    }
}

function land_check()
{
    global $dbh;
    $sql_landen_query = "select * from Land";
    $sql_landen = $dbh->prepare($sql_landen_query);
    $sql_landen->execute();
    $sql_landen_data = $sql_landen->fetchAll(PDO::FETCH_NUM);

    global $land;
    $selected_country = 'Nederland';

    if (isset($land)) {
        $selected_country = $land;
    }

    echo "<div class='medium-6 large-6 cell'>
            <label>Land*
                <select name='land'>";

    foreach ($sql_landen_data as $landen) {
        if ($landen[1] == $selected_country) {
            echo "<option value='" . $landen[1] . "' selected='selected' >" . $landen[1] . "</option>";
        } else {
            echo "<option value='" . $landen[1] . "'>" . $landen[1] . "</option>";
        }
    }

    echo "        </select>
            </label>
          </div>";
}

function voorwerp_en_levering()
{
    // check of er een rubriek is gekozen op laagste niveau
    echo "<form method='POST' action='verkopen_object.php' enctype='multipart/form-data'>
                <div class='medium-12 large-12 float-center cell verkopen-object-box'>
                    <h4>Voorwerp en levering</h4>";

    titel_check();
    beschrijving_check();

    // openen van een nieuwe dic omdat er meerdere labels zijn op een lijn
    echo "<div class='grid-x grid-padding-x'>";

    afbeeldingen();
    startprijs_check();
    betalingswijze();
    looptijd();

    // het sluiten van de grid-x grid-padding-x
    echo "</div>";

    betalingsinstructie_check();

    //het sluiten van de "verkopen-object-box"
    echo "</div>";
}

function zending()
{
    echo "<div class='medium-12 large-12 float-center cell verkopen-object-box'>
                    <h4>Zending</h4>
                    <div class='grid-x grid-padding-x'>
                        <div class='medium-12 large-12 cell'>";

    verzendkosten();
    verzendinstructies_check();

    echo "              </div>
                    </div>
          </div>";
}

function contactgegevens()
{

    echo "    <div class='medium-12 large-12 float-center cell verkopen-object-box'>
                <h4>Contactgegevens</h4>
                    <div class='grid-x grid-padding-x'>";

    plaatsnaam_check();
    land_check();

    echo "           </div>
               <input type='submit' name='plaats_veiling' value='Plaats voorwerp ' class='button expanded'>
               </div>
          </form>";
}

?>

<?php include_once 'components/header.php'; ?>

<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <h2 class="registreren_titel">Plaats voorwerp</h2>
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
            if (isset($_SESSION['gekozen_rubrieknummer']) && isset($_SESSION['gekozen_rubrieknummer'])) {
                voorwerp_en_levering();
                zending();
                contactgegevens();
            }
            ?>
        </div>
    </div>
</div>

<?php include "components/scripts.html"; ?>

</body>
</html>