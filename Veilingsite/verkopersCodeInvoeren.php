<?php
    include_once "components/connect.php";
    include_once "components/meta.php";

    $errorMessage = "";

    if (!isset($_SESSION['ingelogde_gebruiker'])) {
        header('location: index.php');
    }

    if(isset($_POST['codeSend'])){
        if(isset($_POST['verkopersCode']) && $_POST['verkopersCode'] != NULL){
            $submittedCode = $_POST['verkopersCode'];

            $code = md5($_SESSION['ingelogde_gebruiker'] . $submittedCode . 'sadvbsydbfdsbm');

            $correctCode = getHash($_SESSION['ingelogde_gebruiker']);

            echo "mycode: " . $code . " correct: " . $correctCode;

            //Gebruiker zou hier eigenlijk niet moeten zijn.
            if($correctCode == "Nothing_Found"){
                header('location: index.php');
            }
            else if($code == $correctCode){
                maakVerkoper();
                deleteCode();
                // echo '<script type="text/javascript">throwAlert();</script>';
                header('location: index.php');
            }
            else{
                $errorMessage = "Code incorrect.";
            }
        }
    }

    if(isset($_POST['resendCode'])){

        //Gebruiker zou hier eigenlijk niet moeten zijn.
        $oldCode = getHash($_SESSION['ingelogde_gebruiker']);
        if($oldCode == "Nothing_Found"){
            header('location: index.php');
        }
        else{
            deleteCode();

            $verificatie_code = chr(64 + rand(0, 26)) . chr(64 + rand(0, 26)) . chr(64 + rand(0, 26)) . chr(64 + rand(0, 26)) . chr(64 + rand(0, 26));
            $hash = md5($_SESSION['ingelogde_gebruiker'] . $verificatie_code . 'sadvbsydbfdsbm');

            $post_Verificatie_Query = "INSERT INTO PostVerificatie (Gebruikersnaam, VerificatieCode, VerificatieCodeUnhased) VALUES (?, ?, ?)";
            $post_Verificatie = $dbh->prepare($post_Verificatie_Query);
            $post_Verificatie->bindParam(1, $_SESSION['ingelogde_gebruiker'], PDO::PARAM_STR);
            $post_Verificatie->bindParam(2, $hash, PDO::PARAM_STR);
            $post_Verificatie->bindParam(3, $verificatie_code, PDO::PARAM_STR);
            $post_Verificatie->execute();

            unset($_POST['resendCode']);
        }

    }

    function deleteCode(){
        global $dbh;
        $delete_Old_Code_Query = "DELETE FROM PostVerificatie WHERE Gebruikersnaam = :gebruikersnaam";
        $delete_Old_Code = $dbh->prepare($delete_Old_Code_Query);
        $delete_Old_Code->bindParam(":gebruikersnaam", $_SESSION['ingelogde_gebruiker'], PDO::PARAM_STR);
        $delete_Old_Code->execute();
    }

    function maakVerkoper(){
        global $dbh;

        $update_Gebruiker_Naar_Verkoper_Query = "UPDATE Gebruiker SET Verkoper = 1 WHERE Gebruikersnaam = :gebruiker";
        $update_Gebruiker_Naar_Verkoper = $dbh->prepare($update_Gebruiker_Naar_Verkoper_Query);
        $update_Gebruiker_Naar_Verkoper->bindParam(":gebruiker", $_SESSION['ingelogde_gebruiker'], PDO::PARAM_STR);
        $update_Gebruiker_Naar_Verkoper->execute();
    }

    function getHash($Gebruiker){
        global $dbh;

        $get_Hash_query = "SELECT VerificatieCode FROM PostVerificatie WHERE Gebruikersnaam = :gebruikersnaam AND Geldig = :nogsteedsGeldig";
        $get_Hash = $dbh->prepare($get_Hash_query);
        $get_Hash->bindParam(":gebruikersnaam", $Gebruiker, PDO::PARAM_STR);
        $get_Hash->bindValue(":nogsteedsGeldig", 0, PDO::PARAM_INT);
        $get_Hash->execute();


        if($get_Hash->rowCount() != 0){
            $hash = $get_Hash->fetch(PDO::FETCH_OBJ)->VerificatieCode;
            return $hash;
        }
        else{
            return "Nothing_Found";
        }
    }

    function form(){
        global $errorMessage;
        echo '
            <form action="#" method="POST">
                <label>Verkopers Code</label>
                <p>' . $errorMessage . '</p>
                <input type="text" placeholder="Voer hier uw code in" name="verkopersCode">
                <input type="submit" value="submit" name="codeSend" class="button expanded float-right">
            </form>
            </br>
            </br>
            </br>
            </br>
            </br>
            </br>
            </br>
            </br>
            <form action="#" method="POST">
                <input type="submit" value="Code verlopen of niet ontvangen? klik hier om de code te hersturen." name="resendCode" class="button expanded float-right">
            </form>
        ';
    }
?>

    <body>

        <script>
            function throwAlert() {
                alert("U bent nu een verkoper!");
            }
        </script>

        <?php include_once 'components/header.php'; ?>

        <div class="grid-container">
            <div class="grid-x">
                <div class="cell large-5 float-center">
                    <?php form(); ?>
                </div>
            </div>
        </div>

        <?php include "components/scripts.html"; ?>
    </body>
</html>
