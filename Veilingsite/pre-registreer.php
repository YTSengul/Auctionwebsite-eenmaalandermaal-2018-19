<?php
include_once "components/connect.php";

$verificatie_incorrect = false;

if (isset($_POST['stuur_verificatiecode_op'])) {
    $pre_emailadres = $_POST["pre-emailadres"];
    $verificatiecode = $_POST["verificatiecode"];


    $sql_email_pre_registreer_valideer_query = "select * from emailconfiguratie where mailbox = '$pre_emailadres'";
    $sql_email_pre_registreer_valideer_query = $dbh->prepare($sql_email_pre_registreer_valideer_query);
    $sql_email_pre_registreer_valideer_query->execute();
    $verificatiecode_controle = $sql_email_pre_registreer_valideer_query->fetchAll(PDO::FETCH_NUM);

    $verificatiemail_database = $verificatiecode_controle[0][0];
    $verificatiecode_database = $verificatiecode_controle[0][1];

    if ($sql_email_pre_registreer_valideer_query->rowCount() > 0 && $verificatiemail_database == $pre_emailadres && $verificatiecode_database == $verificatiecode) {
        $sql_email_pre_registreer_valideer_query = "update emailconfiguratie set geverifieerd = '1' where mailbox = '$pre_emailadres'";
        $sql_email_pre_registreer_valideer_query = $dbh->prepare($sql_email_pre_registreer_valideer_query);
        $sql_email_pre_registreer_valideer_query->execute();
        header("location:registreren.php?emailadres=$pre_emailadres");
    } else {
        $verificatie_incorrect = true;
    }
} else if (isset($_POST['vraag_verificatiecode_op'])) {

    $verificatie_code = chr(64 + rand(0, 26)) . chr(64 + rand(0, 26)) . chr(64 + rand(0, 26)) . chr(64 + rand(0, 26)) . chr(64 + rand(0, 26));
    $pre_emailadres = $_POST["pre-emailadres"];

    $sql_email_pre_registreer_query = "insert into emailconfiguratie values ('$pre_emailadres', '$verificatie_code','0')";
    $sql_email_pre_registreer_query = $dbh->prepare($sql_email_pre_registreer_query);
    $sql_email_pre_registreer_query->execute();

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
    $mail->Body = "Uw verificatiecode = $verificatie_code, gelieve deze in de website in te voeren.";
    $mail->AddAddress($pre_emailadres);

    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        header('location:?verificatie_code_invoeren=actief');
    }

}
include_once "components/meta.php"
?>

<body>
<?php include_once "components/header.php"; ?>
<div class="grid-container">

    <?PHP if (isset($_GET['verificatie_code_invoeren']) || $verificatie_incorrect == true) {
        echo '<div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <h2 class="registreren_titel">Pre-registratie</h2>
            <form action="#" method="POST">
                <label>Vul hier uw verificatiecode in</label>
                <input';
        if ($verificatie_incorrect == true) {
            echo ' class="is-invalid-input"';
        }
        echo " name='pre-emailadres' type='email' placeholder='Uw Emailadres'>
                <input";
        if ($verificatie_incorrect == true) {
            echo ' class="is-invalid-input"';
        }
        echo ' name="verificatiecode" type="text" placeholder="Uw verificatiecode">
                <a class="float-right" href="pre-registreer.php?verificatie_code_invoeren=actief" >Al een verificatiecode? Klik hier!</a> ';
        if ($verificatie_incorrect == true) {
            echo '<span class="form-error is-visible" id="exemple2Error">Mailadres/verificatiecode is onjuist.</span>';
        }
        echo '<input type="submit" value="Verifiëer uw account" name="stuur_verificatiecode_op"
                       class="button expanded ">
            </form>
        </div>
    </div>';
    } else if (!isset($_POST['verificatie_code_invoeren']) && !isset($_POST['vraag_verificatiecode_op'])) {
        echo '<div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <h2 class="registreren_titel">Pre-registratie</h2>
            <form action="#" method="POST">
                <label>Vul uw emailadres in om een verificatiecode te ontvangen</label>
                <input name="pre-emailadres" type="email" placeholder="Uw Emailadres">';
        if (isset($_GET['mailadres'])) {
            if ($_GET['mailadres'] = 'leeg') {
                echo '<span class="form-error is-visible" id="exemple2Error">Dit emailadres is nog niet geverifieerd.</span>';
            }
        }
        echo '<a class="float-right" href="pre-registreer.php?verificatie_code_invoeren=actief" >Al een verificatiecode? Klik hier!</a>
                <input type="submit" value="Vraag verificatiecode op" name="vraag_verificatiecode_op"
                       class="button expanded ">
            </form>
        </div>
    </div>';

    }

    include "components/scripts.html";

    ?>
</div>

</body>
</html>

