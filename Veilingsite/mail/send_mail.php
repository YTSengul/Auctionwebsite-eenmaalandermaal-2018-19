<html>
<head>
</head>
<body>
<form method="post" action="send_mail.php">
    To : <input type="text" name="mail_to"> <br/>
    Subject :   <input type="text" name="mail_sub">
    <br/>
    Message   <input type="text" name="mail_msg">
    <br/>
    <input type="submit" value="Send Email">
</form>
</body>
</html>
<?php

echo '<pre>';
print_r($_POST);
echo '</pre>';

//require("PHPMailer-master/src/PHPMailer.php");
//require("PHPMailer-master/src/SMTP.php");
//
//$mail = new PHPMailer\PHPMailer\PHPMailer();
//$mail->IsSMTP(); // enable SMTP
//
//$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
//$mail->SMTPAuth = true; // authentication enabled
//$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
//$mail->Host = "smtp.gmail.com";
//$mail->Port = 465; // or 587
//$mail->IsHTML(true);
//$mail->Username = "Iprojec04.eenmaalandermaal@gmail.com";
//$mail->Password = "Iproject04";
//$mail->SetFrom("Iprojec04.eenmaalandermaal@gmail.com");
//$mail->Subject = "Test";
//$mail->Body = "hello";
//$mail->AddAddress("y.t.sengul@hotmail.com");
//
//if(!$mail->Send()) {
//    echo "Mailer Error: " . $mail->ErrorInfo;
//} else {
//    echo "Message has been sent";
//}

?>





   

