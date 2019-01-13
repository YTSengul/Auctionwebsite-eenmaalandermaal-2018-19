<?php
include_once "components/connect.php";

$verificatie_incorrect = false;

if (isset($_POST['vraag_verificatiecode_op'])) {

    $verificatie_code = chr(64 + rand(0, 26)) . chr(64 + rand(0, 26)) . chr(64 + rand(0, 26)) . chr(64 + rand(0, 26)) . chr(64 + rand(0, 26));

    $pre_emailadres = $_POST["pre-emailadres"];

    $hash = md5($pre_emailadres . 'sadvbsydbfdsbm');

    $to = $pre_emailadres;
    $subject = 'Activeringscode eenmaalandermaal';
    $message = $message = "<html>
<head>
   <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
   <title>Activeringsmail eenmaalandermaal</title>
</head>
<body>
<a href='iproject4.icasites.nl/registreren.php?emailadres=" . $pre_emailadres . "&hash=" . $hash . "' >klik hier</a>
</body>
</html>";
    $headers = 'From: noreply@eenmaalandermaal.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    if (mail($to, $subject, $message, $headers)) {
        echo 'Mail is succesfull sended';
    } else {
        echo 'not working';
    }

}
include_once "components/meta.php"
?>

<body>
<?php include_once "components/header.php"; ?>
<div class="grid-container">

    <?PHP

    echo '<div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <h2 class="registreren_titel">Pre-registratie</h2>
            <form action="#" method="POST">
                <label>Vul uw emailadres in om een verificatiecode te ontvangen</label>
                <input name="pre-emailadres" type="email" placeholder="Uw Emailadres">';

    echo '<input type="submit" value="Vraag verificatiecode op" name="vraag_verificatiecode_op"
                       class="button expanded ">
            </form>
        </div>
    </div>';

    include "components/scripts.html";

    ?>
</div>

</body>
</html>

