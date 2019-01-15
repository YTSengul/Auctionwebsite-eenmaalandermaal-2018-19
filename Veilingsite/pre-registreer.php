<?php
include_once "components/connect.php";

$verificatie_incorrect = false;
$verificatie = true;
if (isset($_GET['verificatie'])) {
    if ($_GET['verificatie'] == 'onjuist') {
        $verificatie = false;
    }
}

$tijd_verlopen = false;
if (isset($_GET['tijd'])) {
    if ($_GET['tijd'] == 'verlopen') {
        $tijd_verlopen = true;
    }
}

if (isset($_POST['vraag_verificatiecode_op'])) {

    $pre_emailadres = $_POST["pre-emailadres"];

    $sql_check_mail_query = "select * from Gebruiker where Mailbox = '" . $pre_emailadres . "'";
    $sql_check_mail = $dbh->prepare($sql_check_mail_query);
    $sql_check_mail->execute();
    $sql_check_mail_data = $sql_check_mail->fetchAll(PDO::FETCH_NUM);

    if (sizeof($sql_check_mail_data) > 0) {
        header('Location:login.php?mailadres=in_gebruik&gebruikersnaam='.$pre_emailadres);
    } else {

        $verify_until = date('Y-m-d H:i:s', strtotime('4 hour'));
        $hash = md5($pre_emailadres . $verify_until . 'sadvbsydbfdsbm');

        $to = $pre_emailadres;
        $subject = 'Activeringscode eenmaalandermaal';
        $message = $message = '<html>
   <head>
      <style>
         .banner-color {
         background-color: #f2552c;
         }
         .title-color {
         color: #0066cc;
         }
         .button-color {
         background-color: #0066cc;
         }
         @media screen and (min-width: 500px) {
         .banner-color {
         background-color: #0066cc;
         }
         .title-color {
         color: #f2552c;
         }
         .button-color {
         background-color: #f2552c;
         }
         }
      </style>
   </head>
   <body>
      <div style="background-color:#ececec;padding:0;margin:0 auto;font-weight:200;width:100%!important">
         <table align="center" border="0" cellspacing="0" cellpadding="0" style="table-layout:fixed;font-weight:200;font-family:Helvetica,Arial,sans-serif" width="100%">
            <tbody>
               <tr>
                  <td align="center">
                     <center style="width:100%">
                        <table bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;max-width:512px;font-weight:200;width:inherit;font-family:Helvetica,Arial,sans-serif" width="512">
                           <tbody>
                              <tr>
                                 <td bgcolor="#F3F3F3" width="100%" style="background-color:#f3f3f3;padding:12px;border-bottom:1px solid #ececec">
                                    <table border="0" cellspacing="0" cellpadding="0" style="font-weight:200;width:100%!important;font-family:Helvetica,Arial,sans-serif;min-width:100%!important" width="100%">
                                       <tbody>
                                          <tr>
                                             <td align="left" valign="middle" width="50%"><span style="margin:0;color:#4c4c4c;white-space:normal;display:inline-block;text-decoration:none;font-size:12px;line-height:20px">EenmaalAndermaal</span></td>
                                             <td valign="middle" width="50%" align="right" style="padding:0 0 0 10px"><span style="margin:0;color:#4c4c4c;white-space:normal;display:inline-block;text-decoration:none;font-size:12px;line-height:20px">' . date("d-m-Y") . '</span></td>
                                             <td width="1">&nbsp;</td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                              <tr>
                                 <td align="left">
                                    <table border="0" cellspacing="0" cellpadding="0" style="font-weight:200;font-family:Helvetica,Arial,sans-serif" width="100%">
                                       <tbody>
                                          <tr>
                                             <td width="100%">
                                                <table border="0" cellspacing="0" cellpadding="0" style="font-weight:200;font-family:Helvetica,Arial,sans-serif" width="100%">
                                                   <tbody>
                                                      <tr>
                                                         <td align="center" bgcolor="#8BC34A" style="padding:20px 48px;color:#ffffff" class="banner-color">
                                                            <table border="0" cellspacing="0" cellpadding="0" style="font-weight:200;font-family:Helvetica,Arial,sans-serif" width="100%">
                                                               <tbody>
                                                                  <tr>
                                                                     <td align="center" width="100%">
                                                                        <h1 style="padding:0;margin:0;color:#ffffff;font-weight:500;font-size:20px;line-height:24px">Registratiemail</h1>
                                                                     </td>
                                                                  </tr>
                                                               </tbody>
                                                            </table>
                                                         </td>
                                                      </tr>
                                                      <tr>
                                                         <td align="center" style="padding:20px 0 10px 0">
                                                            <table border="0" cellspacing="0" cellpadding="0" style="font-weight:200;font-family:Helvetica,Arial,sans-serif" width="100%">
                                                               <tbody>
                                                                  <tr>
                                                                     <td align="center" width="100%" style="padding: 0 15px;text-align: justify;color: rgb(76, 76, 76);font-size: 12px;line-height: 18px;">
                                                                        <h3 style="font-weight: 600; padding: 0px; margin: 0px; font-size: 16px; line-height: 24px; text-align: center;" class="title-color">Beste,</h3>
                                                                        <p style="margin: 20px 0 30px 0;font-size: 15px;text-align: center;">Gelieve op de link <a style="background-color: #f2552c; color: white; padding: 5px 10px;" href="http://iproject4.icasites.nl/registreren.php?emailadres=' . $pre_emailadres . '&hash=' . $hash . '&verify_until=' . $verify_until . '" >hier</a> te klikken om uw registratie verder voort te zetten.</p>
                                                                     </td>
                                                                  </tr>
                                                               </tbody>
                                                            </table>
                                                         </td>
                                                      </tr>
                                                      <tr>
                                                      </tr>
                                                      <tr>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                              <tr>
                                 <td align="left">
                                    <table bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="0" style="padding:0 24px;color:#999999;font-weight:200;font-family:Helvetica,Arial,sans-serif" width="100%">
                                       <tbody>
                                          <tr>
                                             <td align="center" width="100%">
                                                <table border="0" cellspacing="0" cellpadding="0" style="font-weight:200;font-family:Helvetica,Arial,sans-serif" width="100%">
                                                   <tbody>
                                                      <tr>
                                                         <td align="center" valign="middle" width="100%" style="border-top:1px solid #d9d9d9;padding:12px 0px 20px 0px;text-align:center;color:#4c4c4c;font-weight:200;font-size:12px;line-height:18px">Met vriendelijke groet,
                                                            <br><b>EenmaalAndermaal</b>
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td align="center" width="100%">
                                                <table border="0" cellspacing="0" cellpadding="0" style="font-weight:200;font-family:Helvetica,Arial,sans-serif" width="100%">
                                                   <tbody>
                                                      <tr>
                                                         <td align="center" style="padding:0 0 8px 0" width="100%"></td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </center>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </body>
</html>';
        $headers = 'From: noreply@eenmaalandermaal.com' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-Type: text/html; charset=UTF-8' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        if (mail($to, $subject, $message, $headers)) {
            $mail_sended = true;
        } else {
            $mail_sended = false;
        }
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
                <input name="pre-emailadres" type="email" placeholder="Uw Emailadres">
                <input type="submit" value="Vraag verificatiecode op" name="vraag_verificatiecode_op"
                       class="button expanded ">
            </form>';
    if (isset($mail_sended)) {
        if ($mail_sended == true) {
            echo 'Er is een activeringsmail naar u toe gestuurd. Gelieve op de link in uw mail te klikken.';
        }
        if ($mail_sended == false) {
            echo 'Er is een fout opgetreden bij het opsturen van de mail, gelieve het later op een ander moment opnieuw te proberen.';
        }
    }

    if ($verificatie == false) {
        echo 'U moet de link uit uw mailbox aanklikken om uzelf te registreren. heeft u nog geen verificatiecode? Vraag die dan nu hier op.';
    }

    if ($tijd_verlopen == true) {
        echo 'De tijd van uw verificatie is verlopen. U kunt hier om een nieuwe verificatiecode vragen.';
    }

    echo '</div>
    </div>';

    include "components/scripts.html";

    ?>
</div>

</body>
</html>

