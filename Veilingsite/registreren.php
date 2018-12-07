<?php
/* Zonder dit werkt het doorsturen naar de login pagina niet */
ob_start();
/*-----------------------------------------------------------*/
include_once "components/connect.php";

$valid = 0;
$invalid = 1;
$formulier_validation = $valid;
$gebruikersnaam_validation = $valid;
$wachtwoord_validation = $valid;
// $dbh->setAttribute(constant('PDO::SQLSRV_ATTR_DIRECT_QUERY'), true);

/* Mailbox definieren voor de check */
if (!isset($_POST["registreer"])) {
    if (isset($_GET['emailadres'])) {
        $emailadres = $_GET['emailadres'];
    } else {
        $emailadres = '';
    }

} else {
    $emailadres = $_POST["emailadres"];
}

if (isset($_POST["registreer"])) {

    $sql_emailadres_check_query = "select * from emailconfiguratie where mailbox = '$emailadres' and geverifieerd = '1'";
    $sql_emailadres_check = $dbh->prepare($sql_emailadres_check_query);
    $sql_emailadres_check->execute();
    $sql_emailadres_check->fetchAll(PDO::FETCH_NUM);

    $rowcount = $sql_emailadres_check->rowCount();

    if ($sql_emailadres_check->rowCount() == 0) {
        $formulier_validation = $invalid;
        header('Location:pre-registreer.php?mailadres=leeg');
    }

    /* Gebruikersnaam definieren voor de check */
    $gebruikersnaam = $_POST["gebruikersnaam"];
    $sql_gebruikersnaam_check_query = "select * from gebruiker where gebruikersnaam = '$gebruikersnaam'";
    $sql_gebruikersnaam_check = $dbh->prepare($sql_gebruikersnaam_check_query);
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

$sql_landen_query = "select * from landen";
$sql_landen = $dbh->prepare($sql_landen_query);
$sql_landen->execute();
$sql_landen_data = $sql_landen->fetchAll(PDO::FETCH_NUM);

$aantal_landen = count($sql_landen_data);

if (isset($_POST["registreer"]) && $formulier_validation == $valid) {

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


        try {
            $gebruiker_registreren = $stmt->execute();

            require("mail/PHPMailer-master/src/PHPMailer.php");
            require("mail/PHPMailer-master/src/SMTP.php");

            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->IsSMTP(); // enable SMTP

            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465; // or 587
            $mail->IsHTML(true);
            $mail->Username = "Iprojec04.eenmaalandermaal@gmail.com";
            $mail->Password = "Iproject04";
            $mail->SetFrom("Iprojec04.eenmaalandermaal@gmail.com");
            $mail->Subject = "Test";
            $mail->Body = "Welkom bij eenmaalandermaal!";
            $mail->AddAddress($emailadres);
            $mail->Send();
            header('location:login.php?registratie=true');
        } catch (PDOException $e) {
            echo "Controleer uw ingevulde gegevens";
            //echo $e->getMessage();
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
                </div>
                <hr class="registreer-hr">
                <div class="medium-12 large-12 float-center cell registreer-box">
                    <div class="medium-12 large-12 cell">
                        <h4>Persoonsgegevens</h4>
                    </div>
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
                    <label>Selecteer je land
                        <select name="land">
                            <?php
                            foreach ($sql_landen_data as $landen_head) {
                                foreach ($landen_head as $land) {
                                    if ($land == 'Nederland') {
                                        echo '<option value="' . $land . '" selected="selected" >' . $land . '</option>';
                                    } else {
                                        echo '<option value="' . $land . '">' . $land . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                    </label>
                    <label>Geboortedatum
                        <input name="geboortedatum" type="date" required>
                    </label>
                    <label>E-mailadres
                        <input name="emailadres" value="<?PHP echo $emailadres; ?>" type="email"
                               placeholder="Uw E-mailadres" required>
                    </label>
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
                </div>
                <input type="submit" value="Registreer" name="registreer" class="registreer-button button expanded ">
            </form>
        </div>
    </div>
</div>

<?php include "components/scripts.html"; ?>

</body>
</html>
