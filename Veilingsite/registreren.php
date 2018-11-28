<?php
/* Zonder dit werkt het doorsturen naar de login pagina niet */
ob_start();
/*-----------------------------------------------------------*/
include_once "components/connect.php";

$valid = 0;
$invalid = 1;
$formulier_validation = $valid;
$gebruikersnaam_validation = $valid;
$emailadres_validation = $valid;
$wachtwoord_validation = $valid;
$db->setAttribute(constant('PDO::SQLSRV_ATTR_DIRECT_QUERY'), true);


if (isset($_POST["registreer"])) {

    /* Mailbox definieren voor de check */
    $emailadres = $_POST["emailadres"];
    $sql_emailadres_check_query = "select * from gebruiker where mailbox = '$emailadres'";
    $sql_emailadres_check = $db->prepare($sql_emailadres_check_query);
    $sql_emailadres_check->execute();
    $sql_emailadres_check->fetchAll(PDO::FETCH_NUM);

    $rowcount = $sql_emailadres_check->rowCount();

    if ($sql_emailadres_check->rowCount() > 0) {
        $emailadres_validation = $invalid;
        $formulier_validation = $invalid;
    }

    /* Gebruikersnaam definieren voor de check */
    $gebruikersnaam = $_POST["gebruikersnaam"];
    $sql_gebruikersnaam_check_query = "select * from gebruiker where gebruikersnaam = '$gebruikersnaam'";
    $sql_gebruikersnaam_check = $db->prepare($sql_gebruikersnaam_check_query);
    $sql_gebruikersnaam_check->execute();
    $sql_gebruikersnaam_check->fetchAll(PDO::FETCH_NUM);

    $rowcount = $sql_gebruikersnaam_check->rowCount();

    if ($sql_gebruikersnaam_check->rowCount() > 0) {
        $gebruikersnaam_validation = $invalid;
        $formulier_validation = $invalid;
    }


    $wachtwoordcheck1 = $_POST["wachtwoord1"];
    $wachtwoordcheck2 = $_POST["wachtwoord2"];


    if ($wachtwoordcheck1 != $wachtwoordcheck2) {
        $wachtwoord_validation = $invalid;
        $formulier_validation = $invalid;
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
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
</head>

<body>

<!-- Include the header into the page -->
<?php include_once 'components/header.php'; ?>

<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <h2 class="registreren_titel" >Registreren</h2>
        </div>
    </div>
    <form action="registreren.php" method="POST">
        <div class="grid-x grid-padding-x">
            <div class="medium-12 large-12 float-center cell">
                <label>Gebruikersnaam
                    <input <?php if ($gebruikersnaam_validation == $invalid) {
                        echo 'class="is-invalid-input"';
                    } ?> name="gebruikersnaam" type="text" placeholder="Uw gebruikersnaam" required>
                </label>
                <?php if ($gebruikersnaam_validation == $invalid) {
                    echo '<span class="form-error is-visible" id="exemple2Error">Deze gebruikersnaam bestaat al.</span>';
                } ?>
                <label>Wachtwoord
                    <input <?php if ($wachtwoord_validation == $invalid) {
                        echo 'class="is-invalid-input"';
                    } ?> name="wachtwoord1" type="password" placeholder="Wachtwoord" required>
                </label>
                <label>Herhaal wachtwoord
                    <input <?php if ($wachtwoord_validation == $invalid) {
                        echo 'class="is-invalid-input"';
                    } ?> name="wachtwoord2" type="password" placeholder="Herhaal wachtwoord" required>
                    <?php if ($wachtwoord_validation == $invalid) {
                        echo '<span class="form-error is-visible" id="exemple2Error">Wachtwoorden komen niet overeen.</span>';
                    } ?>
                </label>
                <label>Voornaam
                    <input name="voornaam" type="text" placeholder="Uw voornaam" required>
                </label>
                <label>Achternaam
                    <input name="achternaam" type="text" placeholder="Uw achternaam" required>
                </label>
                <label>Adresregel1
                    <input name="adresregel1" type="text" placeholder="Uw adresregel1" required>
                </label>
                <label>Adresregel2
                    <input name="adresregel2" type="text" placeholder="Uw adresregel2">
                </label>
            </div>
            <div class="medium-6 small-6 cell">
                <label>Plaatsnaam
                    <input name="plaatsnaam" type="text" placeholder="Uw plaatsnaam" required>
                </label>
            </div>
            <div class="medium-6 small-6 cell">
                <label>Postcode
                    <input name="postcode" type="text" placeholder="Uw postcode" required>
                </label>
            </div>
            <div class="medium-12 cell">
                <label>Selecteer je land
                    <select name="land">
                        <option value="Afghanistan">Afghanistan</option>
                        <option value="Albanië">Albanië</option>
                        <option value="Algerije">Algerije</option>
                        <option value="Andorra">Andorra</option>
                        <option value="Angola">Angola</option>
                        <option value="Antigua-Barbuda">Antigua-Barbuda</option>
                        <option value="Argentinië">Argentinië</option>
                        <option value="Armenië">Armenië</option>
                        <option value="Aruba">Aruba</option>
                        <option value="Australië">Australië</option>
                        <option value="Azerbaijan">Azerbaijan</option>
                        <option value="Bahamas">Bahamas</option>
                        <option value="Bahrein">Bahrein</option>
                        <option value="Belize">Belize</option>
                        <option value="België">België</option>
                        <option value="Bermuda">Bermuda</option>
                        <option value="Bolivia">Bolivia</option>
                        <option value="Bosnië-Herzegovina">Bosnië-Herzegovina</option>
                        <option value="Botswana">Botswana</option>
                        <option value="Brazilië">Brazilië</option>
                        <option value="Brunei Darussalam">Brunei Darussalam</option>
                        <option value="Bulgarije">Bulgarije</option>
                        <option value="Burundi">Burundi</option>
                        <option value="Cambodja">Cambodja</option>
                        <option value="Cameroen">Cameroen</option>
                        <option value="Canada">Canada</option>
                        <option value="Cayman Eilanden">Cayman Eilanden</option>
                        <option value="Centraal-Afrikaanse Republiek">Centraal-Afrikaanse Republiek</option>
                        <option value="Chili">Chili</option>
                        <option value="China">China</option>
                        <option value="Ciprus">Ciprus</option>
                        <option value="Colombia">Colombia</option>
                        <option value="Congo">Congo</option>
                        <option value="Cook Eilanden">Cook Eilanden</option>
                        <option value="Costa Rica">Costa Rica</option>
                        <option value="Groatië">Groatië</option>
                        <option value="Cuba">Cuba</option>
                        <option value="Cyprus">Cyprus</option>
                        <option value="Denemarken">Denemarken</option>
                        <option value="Dominica">Dominica</option>
                        <option value="Dominicaanse Republiek">Dominicaanse Republiek</option>
                        <option value="DR Congo">DR Congo</option>
                        <option value="Duitsland">Duitsland</option>
                        <option value="Ecuador">Ecuador</option>
                        <option value="Egypte">Egypte</option>
                        <option value="El Salvador">El Salvador</option>
                        <option value="Eritrea">Eritrea</option>
                        <option value="Estland">Estland</option>
                        <option value="Ethiopië">Ethiopië</option>
                        <option value="Fiji">Fiji</option>
                        <option value="Filipijnen">Filipijnen</option>
                        <option value="Finland">Finland</option>
                        <option value="Frankrijk">Frankrijk</option>
                        <option value="Frans Polynesië">Frans Polynesië</option>
                        <option value="Gabon">Gabon</option>
                        <option value="Gambia">Gambia</option>
                        <option value="Georgië">Georgië</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Griekenland">Griekenland</option>
                        <option value="Groenland">Groenland</option>
                        <option value="Guam">Guam</option>
                        <option value="Guatemala">Guatemala</option>
                        <option value="Guinee-Bissau">Guinee-Bissau</option>
                        <option value="Guyana">Guyana</option>
                        <option value="Haïti">Haïti</option>
                        <option value="Honduras">Honduras</option>
                        <option value="Hongarije">Hongarije</option>
                        <option value="Ierland">Ierland</option>
                        <option value="IJsland">IJsland</option>
                        <option value="India">India</option>
                        <option value="Indonesië">Indonesië</option>
                        <option value="Irak">Irak</option>
                        <option value="Iran">Iran</option>
                        <option value="Israël">Israël</option>
                        <option value="Italië">Italië</option>
                        <option value="Ivoorkust">Ivoorkust</option>
                        <option value="Jamaica">Jamaica</option>
                        <option value="Japan">Japan</option>
                        <option value="Jemen">Jemen</option>
                        <option value="Joegoslavië">Joegoslavië</option>
                        <option value="Jordanië">Jordanië</option>
                        <option value="Kameroen">Kameroen</option>
                        <option value="Kazachstan">Kazachstan</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Kirgizstan">Kirgizstan</option>
                        <option value="Koeweit">Koeweit</option>
                        <option value="Korea">Korea</option>
                        <option value="Kroatië">Kroatië</option>
                        <option value="Laos">Laos</option>
                        <option value="Lesotho">Lesotho</option>
                        <option value="Letland">Letland</option>
                        <option value="Libanon">Libanon</option>
                        <option value="Liberia">Liberia</option>
                        <option value="Libië">Libië</option>
                        <option value="Liechtenstein">Liechtenstein</option>
                        <option value="Litouwen">Litouwen</option>
                        <option value="Luxemburg">Luxemburg</option>
                        <option value="Macedonië">Macedonië</option>
                        <option value="Maleisië">Maleisië</option>
                        <option value="Mali">Mali</option>
                        <option value="Malta">Malta</option>
                        <option value="Marokko">Marokko</option>
                        <option value="Mauritanië">Mauritanië</option>
                        <option value="Mauritius">Mauritius</option>
                        <option value="Mexico">Mexico</option>
                        <option value="Moldova">Moldova</option>
                        <option value="Monaco">Monaco</option>
                        <option value="Mozambique">Mozambique</option>
                        <option value="Namibië">Namibië</option>
                        <option value="Nederland" SELECTED>Nederland</option>
                        <option value="Nepal">Nepal</option>
                        <option value="Nicaragua">Nicaragua</option>
                        <option value="Nieuw Zeeland">Nieuw Zeeland</option>
                        <option value="Niger">Niger</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Noorwegen">Noorwegen</option>
                        <option value="Oezbekistan">Oezbekistan</option>
                        <option value="Oman">Oman</option>
                        <option value="Oostenrijk">Oostenrijk</option>
                        <option value="Pakistan">Pakistan</option>
                        <option value="Papoea-Nieuw-Guinea">Papoea-Nieuw-Guinea</option>
                        <option value="Paraguay">Paraguay</option>
                        <option value="Peru">Peru</option>
                        <option value="Polen">Polen</option>
                        <option value="Portugal">Portugal</option>
                        <option value="Puerto Rico">Puerto Rico</option>
                        <option value="Quatar">Quatar</option>
                        <option value="Roemenië">Roemenië</option>
                        <option value="Rusland">Rusland</option>
                        <option value="Rwanda">Rwanda</option>
                        <option value="Saint Lucia">Saint Lucia</option>
                        <option value="Salomonseilanden">Salomonseilanden</option>
                        <option value="San Marino">San Marino</option>
                        <option value="Saudi-Arabië">Saudi-Arabië</option>
                        <option value="Schotland">Schotland</option>
                        <option value="Senegal">Senegal</option>
                        <option value="Sierra Leone">Sierra Leone</option>
                        <option value="Singapore">Singapore</option>
                        <option value="Slovenië">Slovenië</option>
                        <option value="Slowakije">Slowakije</option>
                        <option value="Somalië">Somalië</option>
                        <option value="Spanje">Spanje</option>
                        <option value="Sri Lanka">Sri Lanka</option>
                        <option value="Sudan">Sudan</option>
                        <option value="Syrie">Syrie</option>
                        <option value="Tadzjikistan">Tadzjikistan</option>
                        <option value="Taiwan">Taiwan</option>
                        <option value="Thailand">Thailand</option>
                        <option value="Tobago">Tobago</option>
                        <option value="Tsjechië">Tsjechië</option>
                        <option value="Tsjaad">Tsjaad</option>
                        <option value="Tunesië">Tunesië</option>
                        <option value="Turkije">Turkije</option>
                        <option value="Turkmenistan">Turkmenistan</option>
                        <option value="Trinidad">Trinidad</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Ukraine">Ukraine</option>
                        <option value="Uruguay">Uruguay</option>
                        <option value="Venezuela">Venezuela</option>
                        <option value="Verenigd Koninkrijk">Verenigd Koninkrijk</option>
                        <option value="Verenigde Staten">Verenigde Staten</option>
                        <option value="Vietnam">Vietnam</option>
                        <option value="Zaïre">Zaïre</option>
                        <option value="Zambia">Zambia</option>
                        <option value="Zimbabwe">Zimbabwe</option>
                        <option value="Zuid-Afrika">Zuid-Afrika</option>
                        <option value="Zweden">Zweden</option>
                        <option value="Zwitserland">Zwitserland</option>
                    </select>
                </label>
                <label>Geboortedatum
                    <input name="geboortedatum" type="date" required>
                </label>
                <label>E-mailadres
                    <input <?php if ($emailadres_validation == $invalid) {
                        echo 'class="is-invalid-input"';
                    } ?> name="emailadres" type="email" placeholder="Uw E-mailadres" required>
                </label>
                <?php if ($emailadres_validation == $invalid) {
                    echo '<span class="form-error is-visible" id="exemple2Error">Er bestaat al een account met deze emailadres.</span>';
                } ?>
                <label>Veiligheidsvraag
                    <select name="veiligheidsvraag" required>
                        <option value="1">Wat is de naam van je eerste huisdier?</option>
                        <option value="2">Op welk basisschool heb je gezeten?</option>
                        <option value="3">Wat is de meisjesnaam van je moeder?</option>
                    </select>
                </label>
                <label>Antwoord
                    <input name="antwoord_op_veiligheidsvraag" type="text"
                           placeholder="Uw antwoord op de veiligheidsvraag" required>
                </label>
                <input type="submit" value="Registreer" name="registreer" class="button expanded float-right">
            </div>
        </div>
    </form>

</div>

</body>
<?php

if (isset($_POST["registreer"]) && $formulier_validation == $valid) {

    $voornaam = $_POST["voornaam"];
    $achternaam = $_POST["achternaam"];
    $adresregel1 = $_POST["adresregel1"];
    $adresregel2 = $_POST["adresregel2"];
    $postcode = $_POST["postcode"];
    $plaatsnaam = $_POST["plaatsnaam"];
    $land = $_POST["land"];
    $geboortedatum = $_POST["geboortedatum"];
    $emailadres = $_POST["emailadres"];
    $wachtwoord1 = $_POST["wachtwoord1"];
    $veiligheidsvraag = $_POST["veiligheidsvraag"];
    $antwoord_op_veiligheidsvraag = $_POST["antwoord_op_veiligheidsvraag"];

    $wachtwoord1_hashed = password_hash($wachtwoord1, PASSWORD_DEFAULT);

    /*
    print($gebruikersnaam) . "<br>";
    print($voornaam) . "<br>";
    print($achternaam) . "<br>";
    print($adresregel1) . "<br>";
    print($adresregel2) . "<br>";
    print($postcode) . "<br>";
    print($plaatsnaam) . "<br>";
    print($land) . "<br>";
    print($geboortedatum) . "<br>";
    print($emailadres) . "<br>";
    print($wachtwoord1) . "<br>";
    print($veiligheidsvraag) . "<br>";
    print($antwoord_op_veiligheidsvraag) . "<br>";
    */

    $sql_registreer = "insert into gebruiker ([gebruikersnaam], [voornaam], [achternaam], [adresregel1], [adresregel2], [postcode], [plaatsnaam], [land], [datum], [mailbox], [wachtwoord], [vraagnummer], [antwoordtekst]) values (:gebruikersnaam, :voornaam, :achternaam, :adresregel1, :adresregel2, :postcode, :plaatsnaam, :land, :geboortedatum, :emailadres, :wachtwoord1, :veiligheidsvraag, :antwoord_op_veiligheidsvraag)";

    $stmt = $db->prepare($sql_registreer);

    if ($stmt) {
        $stmt->bindParam(":gebruikersnaam", $gebruikersnaam, PDO::PARAM_STR);
        $stmt->bindParam(":voornaam", $voornaam, PDO::PARAM_STR);
        $stmt->bindParam(":achternaam", $achternaam, PDO::PARAM_STR);
        $stmt->bindParam(":adresregel1", $adresregel1, PDO::PARAM_STR);
        $stmt->bindParam(":adresregel2", $adresregel2, PDO::PARAM_STR);
        $stmt->bindParam(":postcode", $postcode, PDO::PARAM_STR);
        $stmt->bindParam(":plaatsnaam", $plaatsnaam, PDO::PARAM_STR);
        $stmt->bindParam(":land", $land, PDO::PARAM_STR);
        $stmt->bindParam(":geboortedatum", $geboortedatum, PDO::PARAM_STR);
        $stmt->bindParam(":emailadres", $emailadres, PDO::PARAM_STR);
        $stmt->bindParam(":wachtwoord1", $wachtwoord1_hashed, PDO::PARAM_STR);
        $stmt->bindParam(":veiligheidsvraag", $veiligheidsvraag, PDO::PARAM_STR);
        $stmt->bindParam(":antwoord_op_veiligheidsvraag", $antwoord_op_veiligheidsvraag, PDO::PARAM_STR);
        $gebruiker_registreren = $stmt->execute();

        try {
            if ($gebruiker_registreren) {
                /*echo "<br>succesvol toegevoegd aan database";*/
                header('location:login.php?registratie=true');
            } else {
                /*echo "<br>niet toegevoegd aan database";*/
            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}


?>

<?php include "components/scripts.html"; ?>




