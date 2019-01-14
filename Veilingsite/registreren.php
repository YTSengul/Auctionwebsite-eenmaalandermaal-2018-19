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
    $antwoord = $_POST["antwoord"];

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

// Check of de hash klopt met de gestuurde hash in de header
if (md5($emailadres . 'sadvbsydbfdsbm') != $hash) {
    $formulier_validation = $invalid;
    //header('Location:pre-registreer.php?mailadres=leeg');
}

function check_length($label, $input)
{
    $variable_length = ['gebruikersnaam' => 40, 'emailadres' => 50, 'voornaam' => 30, 'achternaam' => 30, 'adresregel1' => 50, 'adresregel2' => 50,
        'postcode' => 7, 'plaatsnaam' => 85, 'land' => 40, 'wachtwoord1' => 255, 'antwoord' => 255];
    if (strlen($input) > $variable_length[$label]) {
        return array(true,$variable_length[$label]);
    } else {
        return false;
    }
}

function create_label($name, $type, $placeholder, $required)
{
    global ${$name};
    $posted = ${$name};
    global $invalid;

    $variables = check_length($name, $posted);

    if($name == 'gebruikersnaam') {
        global $gebruikersnaam_validation;
    }

    echo '<input name='.$name.' type="' . $type . '" placeholder="' . $placeholder . '" ';
    if (isset($posted)) {
        // Als de variabele veel langer is dan toegestaan
        if ($variables[0]) {
            echo 'class="is-invalid-input"';
        }
        // Als de gebruikersnaam al in gebruik is
        if($name == 'gebruikersnaam' && $gebruikersnaam_validation == $invalid) {
            echo 'class="is-invalid-input"';
        }
        echo "value='$posted'";
    }
    if ($required) {
        echo 'required';
    }

    echo ' >';
    if (isset($posted)) {
        //echo "<script type='text/javascript'>alert('aaa');</script>";
        if ($variables[0]) {
            echo '<span class="form-error is-visible" id="exemple2Error">Uw '.$name.' mag maar '.$variables[1].' karakters bevatten.</span>';
        }
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

    $sql_registreer = "insert into gebruiker ([gebruikersnaam], [voornaam], [achternaam], [adresregel1], [adresregel2], [postcode], [plaatsnaam], [land], [datum], [mailbox], [wachtwoord], [vraagnummer], [antwoordtekst]) 
    values (:gebruikersnaam, :voornaam, :achternaam, :adresregel1, :adresregel2, :postcode, :plaatsnaam, :land, :geboortedatum, :emailadres, :wachtwoord1, :veiligheidsvraag, :antwoord_op_veiligheidsvraag)";

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
        $stmt->bindParam(":antwoord_op_veiligheidsvraag", $antwoord, PDO::PARAM_STR);

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
                        <?PHP create_label('gebruikersnaam', 'text', 'Uw gebruikersnaam', '*'); ?>
                    </label>
                    <?php if ($gebruikersnaam_validation == $invalid) {
                        echo '<span class="form-error is-visible" id="exemple2Error">Deze gebruikersnaam is al in gebruik.</span>';
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
                        <?PHP create_label('voornaam', 'text', 'Uw voornaam', '*'); ?>
                    </label>

                    <label>Achternaam*
                        <?PHP create_label('achternaam', 'text', 'Uw achternaam', '*'); ?>
                    </label>

                    <label>Adresregel1*
                        <?PHP create_label('adresregel1', 'text', 'Uw adresregel1', '*'); ?>
                    </label>
                    <label>Adresregel2
                        <?PHP create_label('adresregel2', 'text', 'Uw adresregel2', ''); ?>
                    </label>
                    <div class="grid-x grid-padding-x">
                        <div class="medium-6 cell">
                            <label>Plaatsnaam*
                                <?PHP create_label('plaatsnaam', 'text', 'Uw plaatsnaam', '*'); ?>
                            </label>
                        </div>
                        <div class="medium-6 small-6 cell">
                            <label>Postcode*
                                <?PHP create_label('postcode', 'text', 'Uw postcode', '*'); ?>
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
                        <?PHP create_label('emailadres', 'text', 'Uw E-mailadres', '*'); ?>
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
                        <?PHP create_label('antwoord', 'text', 'Uw antwoord op de veiligheidsvraag', '*'); ?>
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
