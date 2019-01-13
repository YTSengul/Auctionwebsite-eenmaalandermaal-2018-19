<?php
/* Zonder dit werkt het doorsturen naar de login pagina niet */
ob_start();
/*-----------------------------------------------------------*/
include_once "components/connect.php";

// Variabelen of de formulier klopt of niet
$valid = 0;
$invalid = 1;
// In eerste stantie is de formulier altijd kloppend, samen met de gebruikersnaam en wachtwoord
$formulier_validation = $valid;
$gebruikersnaam_validation = $valid;
$wachtwoord_validation = $valid;

// Hier wordt de hash uit de link gehaald
if (isset($_GET['hash'])) {
    $hash = $_GET['hash'];
}

/* Mailbox definieren voor de check */
if (!isset($_POST["registreer"])) {
    if (isset($_GET['emailadres'])) {
        $emailadres = $_GET['emailadres'];
    } else {
        $emailadres = '';
    }
} // Als de formulier wordt opgestuurd worden hier de gegevens in variabelen opgeslagen
else {
    $hash = $_POST['hash'];
    $emailadres = $_POST["emailadres"];
    $voornaam = $_POST["voornaam"];
    $achternaam = $_POST["achternaam"];
    $adresregel1 = $_POST["adresregel1"];
    if (!$_POST["adresregel2"]) {
        $adresregel2 = '';
    } else {
        $adresregel2 = $_POST["adresregel2"];
    }
    $postcode = $_POST["postcode"];
    $plaatsnaam = $_POST["plaatsnaam"];
    $land = $_POST["land"];
    $geboortedatum = $_POST["geboortedatum"];
    $wachtwoord1 = $_POST["wachtwoord1"];
    $veiligheidsvraag = $_POST["veiligheidsvraag"];
    $antwoord_op_veiligheidsvraag = $_POST["antwoord_op_veiligheidsvraag"];


    function check_length($label) {
        $variable_length = ['emailadres'=>50,'voornaam'=>30,'achternaam'=>30,'adresregel1'=>50,'adresregel2'=>50,
            'postcode'=>7, 'plaatsnaam'=>85, 'land'=>40, 'wachtwoord1'=>255, 'antwoord_op_veiligheidsvraag' => 255];
        if($label>$variable_length[$label]) {
            return false;
        } else {
            return true;
        }
    }

    // Hier worden checks uitgevoerd op de ingevulde gegevens
//    if (strlen($emailadres) > 50){
//        $formulier_validation = $invalid;
//        $emailadres_check_length = $invalid;
//    }
//    if (strlen($voornaam) > 30){
//        $formulier_validation = $invalid;
//        $voornaam_check_length = $invalid;
//    }
//    if (strlen($achternaam) > 30){
//        $formulier_validation = $invalid;
//        $achternaam_check_length = $invalid;
//    }
//    if (strlen($adresregel1) > 50){
//        $formulier_validation = $invalid;
//        $adresregel1_check_length = $invalid;
//    }
//    if (isset($adresregel2)){
//        if (strlen($adresregel2) > 50){
//            $formulier_validation = $invalid;
//            $adresregel2_check_length = $invalid;
//        }
//    }
//    if (strlen($postcode) > 7){
//        $formulier_validation = $invalid;
//        $postcode_check_length = $invalid;
//    }
//    if (strlen($plaatsnaam) > 85){
//        $formulier_validation = $invalid;
//        $plaatsnaam_check_length = $invalid;
//    }
//    if (strlen($land) > 40){
//        $formulier_validation = $invalid;
//        $land_check_length = $invalid;
//    }
//    if (strlen($wachtwoord1) > 255){
//        $formulier_validation = $invalid;
//        $wachtwoord1_check_length = $invalid;
//    }
//    if (strlen($antwoord_op_veiligheidsvraag) > 255){
//        $formulier_validation = $invalid;
//        $antwoord_op_veiligheidsvraag_check_length = $invalid;
//    }

    // Check of de hash klopt met de gestuurde hash in de header
    if (md5($emailadres . 'sadvbsydbfdsbm') != $hash) {
        $formulier_validation = $invalid;
        //header('Location:pre-registreer.php?mailadres=leeg');
    }

    /* Gebruikersnaam definieren voor de check */
    $gebruikersnaam = $_POST["gebruikersnaam"];
    $sql_gebruikersnaam_check_query = "select * from gebruiker where gebruikersnaam = '$gebruikersnaam'";
    $sql_gebruikersnaam_check = $dbh->prepare($sql_gebruikersnaam_check_query);
    $sql_gebruikersnaam_check->execute();
    $sql_gebruikersnaam_check->fetchAll(PDO::FETCH_NUM);

    // Check of er al een andere gebruikersnaam is met hetzelfde gebruikersnaam
    $rowcount = $sql_gebruikersnaam_check->rowCount();

    // De gebruikersnaam wordt op onjuist gezet indien de gebruikersnaam al bestaal
    if ($sql_gebruikersnaam_check->rowCount() > 0) {
        $gebruikersnaam_validation = $invalid;
        $formulier_validation = $invalid;
    }

    // Hier worden de twee maal ingevoerde wachtwoorden gecheckt of ze met elkaar overeen komen
    $wachtwoordcheck1 = $_POST["wachtwoord1"];
    $wachtwoordcheck2 = $_POST["wachtwoord2"];

    // Indien de wachtwoorden niet overeen komen worden worden ze op onjuist gezet
    if ($wachtwoordcheck1 != $wachtwoordcheck2) {
        $wachtwoord_validation = $invalid;
        $formulier_validation = $invalid;
    }
}

$sql_landen_query = "select * from Land";
$sql_landen = $dbh->prepare($sql_landen_query);
$sql_landen->execute();
$sql_landen_data = $sql_landen->fetchAll(PDO::FETCH_NUM);

$aantal_landen = count($sql_landen_data);

if (isset($_POST["registreer"]) && $formulier_validation == $valid) {

    // De wachtwoord wod gehashed voor het naar de database gestuurd word
    $wachtwoord1_hashed = password_hash($wachtwoord1, PASSWORD_DEFAULT);

    $sql_registreer = "insert into gebruiker ([gebruikersnaam], [voornaam], [achternaam], [adresregel1], [adresregel2], [postcode], [plaatsnaam], [land], [datum], [mailbox], [wachtwoord], [vraagnummer], [antwoordtekst]) values (:gebruikersnaam, :voornaam, :achternaam, :adresregel1, :adresregel2, :postcode, :plaatsnaam, :land, :geboortedatum, :emailadres, :wachtwoord1, :veiligheidsvraag, :antwoord_op_veiligheidsvraag)";

    $stmt = $dbh->prepare($sql_registreer);

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

        // De gebruiker wordt geprobeerd aan te melden in de website
        try {
            $gebruiker_registreren = $stmt->execute();
            header('location:login.php?registratie=true');
        } catch (PDOException $e) {
            echo "Controleer uw ingevulde gegevens<br>" . $e;
        }
    }
}

include_once "components/meta.php"
?>

<body>
<?php include_once 'components/header.php'; ?>

<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <h2 class="registreren_titel">Registreren</h2>
        </div>
    </div>
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 float-center cell">
            <form action="registreren.php" method="POST">
                <div class="medium-12 large-12 float-center cell registreer-box">
                    <div class="medium-12 large-12 cell">
                        <h4>Accountsgegevens</h4>
                    </div>

                    <label>Gebruikersnaam*
                        <input <?php if ($gebruikersnaam_validation == $invalid) {
                            echo 'class="is-invalid-input"';
                        }
                        if (isset($gebruikersnaam)) {
                            echo "value='$gebruikersnaam'";
                        } ?> name="gebruikersnaam" type="text" placeholder="Uw gebruikersnaam" required>
                    </label>
                    <?php if ($gebruikersnaam_validation == $invalid) {
                        echo '<span class="form-error is-visible" id="exemple2Error">Deze gebruikersnaam bestaat al.</span>';
                    } ?>
                    <div class="grid-x grid-padding-x">
                        <div class="medium-6 cell">
                            <label>Wachtwoord*
                                <input <?php if ($wachtwoord_validation == $invalid) {
                                    echo 'class="is-invalid-input"';
                                } ?> name="wachtwoord1" type="password" placeholder="Wachtwoord" required>
                            </label>
                        </div>
                        <div class="medium-6 cell">
                            <label>Herhaal wachtwoord*
                                <input <?php if ($wachtwoord_validation == $invalid) {
                                    echo 'class="is-invalid-input"';
                                } ?> name="wachtwoord2" type="password" placeholder="Herhaal wachtwoord" required>
                                <?php if ($wachtwoord_validation == $invalid) {
                                    echo '<span class="form-error is-visible" id="exemple2Error">Wachtwoorden komen niet overeen.</span>';
                                } ?>
                            </label>
                        </div>
                    </div>
                </div>
                <hr class="registreer-hr">
                <div class="medium-12 large-12 float-center cell registreer-box">
                    <div class="medium-12 large-12 cell">
                        <h4>Persoonsgegevens</h4>
                    </div>
                    <label>Voornaam*
                        <input name="voornaam" type="text" <?PHP if (isset($voornaam)) {
                            echo "value='$voornaam'";
                        } ?> placeholder="Uw voornaam" required>
                    </label>
                    <label>Achternaam*<?PHP if(isset($achternaam)){echo check_length($achternaam);}; ?>
                        <input name="achternaam" type="text" <?PHP if (isset($achternaam)) {
                            echo "value='$achternaam'";
                        } ?> placeholder="Uw achternaam" required>
                    </label>
                    <label>Adresregel1*
                        <input name="adresregel1" type="text" <?PHP if (isset($adresregel1)) {
                            echo "value='$adresregel1'";
                        } ?> placeholder="Uw adresregel1" required>
                    </label>
                    <label>Adresregel2
                        <input name="adresregel2" type="text" <?PHP if (isset($adresregel2)) {
                            echo "value='$adresregel2'";
                        } ?> placeholder="Uw adresregel2">
                    </label>
                    <div class="grid-x grid-padding-x">
                        <div class="medium-6 cell">
                            <label>Plaatsnaam*
                                <input name="plaatsnaam" type="text" <?PHP if (isset($plaatsnaam)) {
                                    echo "value='$plaatsnaam'";
                                } ?> placeholder="Uw plaatsnaam" required>
                            </label>
                        </div>
                        <div class="medium-6 small-6 cell">
                            <label>Postcode*
                                <input name="postcode" type="text" <?PHP if (isset($postcode)) {
                                    echo "value='$postcode'";
                                } ?> placeholder="Uw postcode" required>
                            </label>
                        </div>
                    </div>
                    <label>Selecteer je land*
                        <select name="land">
                            <?php
                            foreach ($sql_landen_data as $landen) {
                                if ($landen[1] == 'Nederland') {
                                    echo '<option value="' . $landen[1] . '" selected="selected" >' . $landen[1] . '</option>';
                                } else {
                                    echo '<option value="' . $landen[1] . '">' . $landen[1] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </label>
                    <label>Geboortedatum*
                        <input name="geboortedatum" <?PHP if (isset($geboortedatum)) {
                            echo "value='$geboortedatum'";
                        } ?> type="date" required>
                    </label>
                    <label>E-mailadres*
                        <input name="emailadres" value="<?PHP echo $emailadres; ?>" type="email"
                               placeholder="Uw E-mailadres" required>
                    </label>
                    <label>Veiligheidsvraag*
                        <select name="veiligheidsvraag" required>
                            <option value="1" <?PHP if (isset($veiligheidsvraag) == 1) {
                                if ($veiligheidsvraag == 1) {
                                    echo "selected";
                                }
                            } ?> >In welke straat ben je geboren?
                            </option>
                            <option value="2" <?PHP if (isset($veiligheidsvraag) == 2) {
                                if ($veiligheidsvraag == 2) {
                                    echo "selected";
                                }
                            } ?>>Wat is de meisjesnaam je moeder?
                            </option>
                            <option value="3" <?PHP if (isset($veiligheidsvraag) == 3) {
                                if ($veiligheidsvraag == 3) {
                                    echo "selected";
                                }
                            } ?>>Wat is je lievelingsgerecht?
                            </option>
                            <option value="3" <?PHP if (isset($veiligheidsvraag) == 4) {
                                if ($veiligheidsvraag == 4) {
                                    echo "selected";
                                }
                            } ?>>Hoe heet je oudste zusje?
                            </option>
                            <option value="3" <?PHP if (isset($veiligheidsvraag) == 5) {
                                if ($veiligheidsvraag == 5) {
                                    echo "selected";
                                }
                            } ?>>Hoe heet je huisdier?
                            </option>
                        </select>
                    </label>
                    <label>Antwoord*
                        <input name="antwoord_op_veiligheidsvraag"
                               type="text" <?PHP if (isset($antwoord_op_veiligheidsvraag)) {
                            echo "value='$antwoord_op_veiligheidsvraag'";
                        } ?>
                               placeholder="Uw antwoord op de veiligheidsvraag" required>
                    </label>
                    <input type="hidden" name="hash" value="<?PHP echo $hash; ?>">
                </div>
                <input type="submit" value="Registreer" name="registreer" class="registreer-button button expanded ">
            </form>
        </div>
    </div>
</div>

<?php include "components/scripts.html"; ?>

</body>
</html>
