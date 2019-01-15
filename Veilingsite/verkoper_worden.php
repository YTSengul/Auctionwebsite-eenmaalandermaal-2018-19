<?php
    include_once "components/connect.php";
    include_once "components/meta.php";

    $Verification_Option1 = "Creditcard";
    $Verification_Option2 = "Post";

    $creditcard = "Creditcard";
    $bank = "Bank";
    $bankrekening = "Bankrekening";

    $bankJuist = false;
    $bankFilledIn = false;
    $bankrekeningJuist = false;
    $bankrekeningFilledIn = false;
    $creditcardJuist = false;
    $creditcardFilledIn = false;

    $errorMessageBank = "";
    $errorMessageBankrekening = "";
    $errorMessageCreditcard = "";

    $step1 = false;
    $step2InProgress = false;

    if (!isset($_SESSION['ingelogde_gebruiker'])) {
        header('location: index.php');
    }

    if(isset($_POST['Verkoper_aanvraag'])){
        if(isset($_POST['Controle_optie'])){
            if ($_POST['Controle_optie'] == $Verification_Option1 || $_POST['Controle_optie'] == $Verification_Option2) {

                $step2InProgress = true;

                //Als creditcard is geselecteerd is het optioneel om deze in te vullen.
                if(!isRequired($_POST['Controle_optie'], $bank)){
                    $bankJuist = true;
                }
                //Als creditcard is geselecteerd is het optioneel om deze in te vullen.
                if(!isRequired($_POST['Controle_optie'], $bankrekening)){
                    $bankrekeningJuist = true;
                }
                //Als Post is geselecteerd is het optioneel om deze in te vullen.
                if(!isRequired($_POST['Controle_optie'], $creditcard)){
                    $creditcardJuist = true;
                }

                //Als creditcard is geselecteerd is het optioneel om deze in te vullen.
                if(isRequired($_POST['Controle_optie'], $bank) && $_POST[$bank] == NULL){
                    $errorMessageBank = "Verplicht veld";
                }
                //Als creditcard is geselecteerd is het optioneel om deze in te vullen.
                if(isRequired($_POST['Controle_optie'], $bankrekening) && $_POST[$bankrekening] == NULL){
                    $errorMessageBankrekening = "Verplicht veld";
                }
                //Als Post is geselecteerd is het optioneel om deze in te vullen.
                if(isRequired($_POST['Controle_optie'], $creditcard) && $_POST[$creditcard] == NULL){
                    $errorMessageCreditcard = "Verplicht veld";
                }

                if(isset($_POST[$bank]) && $_POST[$bank] != NUll){
                    $bankFilledIn = true;
                    $gebruikerbank = $_POST[$bank];
                    //alleen kleine letters en hoofdletters.
                    // Langer dan 2 en korter dan 21.
                    if (preg_match("/^[a-zA-z\s]{3,20}+$/", $gebruikerbank)) {
                        $bankJuist = true;
                    }
                    else{
                        $bankJuist = false;
                        $errorMessageBank = "Moet minimaal 3 karaters hebben en minder dan 20. Alleen kleine letters en hoofdletters.";
                    }
                }

                if(isset($_POST[$bankrekening]) && $_POST[$bankrekening] != NUll){
                    $bankrekeningFilledIn = true;
                    $gebruikerBankrekening = $_POST[$bankrekening];
                    //Alleen cijfers en hoofdletters
                    //Moet beginnen met een land (NL, BE, DE, etc).
                    //Mauritius Iban nummer heeft 30 karakters, Noorwegen Iban nummer heeft 15 karakters.
                    if (preg_match("/^[A-Z]{2}+[A-Z0-9]{13,28}+$/", $gebruikerBankrekening)) {
                        $bankrekeningJuist = true;
                    }
                    else{
                        $bankrekeningJuist = false;
                        $errorMessageBankrekening = "Moet minimaal 15 karakters hebben en minder dan 30. Alleen Hoofdletters en cijfers.";
                    }
                }

                if(isset($_POST[$creditcard]) && $_POST[$creditcard] != NUll){
                    $creditcardFilledIn = true;
                    $gebruikerCreditcard = $_POST[$creditcard];
                    //Alleen cijfers
                    //Switch en Solo zijn 2 credit cards van de UK die 19 cijfers hebben.
                    //Visa heeft als laagste 13 cijfers.
                    if (preg_match("/^[0-9]{13,19}+$/", $gebruikerCreditcard)) {
                        $creditcardJuist = true;
                    }
                    else {
                        $creditcardJuist = false;
                        $errorMessageCreditcard = "Moet minimaal 13 cijfers hebben en maximaal 19. Alleen cijfers.";
                    }
                }

                //Creditcard
                if($bankJuist && $bankrekeningJuist && $creditcardJuist){
                    if($_POST['Controle_optie'] == $Verification_Option1){
                        if($bankFilledIn || $bankrekeningFilledIn){
                            if($bankFilledIn && $bankrekeningFilledIn){
                                $verkoper_Worden_Query = "INSERT INTO Verkoper (Gebruikersnaam, Banknaam, Rekeningnummer, Controleoptienaam, Creditcardnummer) VALUES(?, ?, ?, ?, ?)";
                                $verkoper_Worden = $dbh->prepare($verkoper_Worden_Query);
                                $verkoper_Worden->bindParam(1, $_SESSION['ingelogde_gebruiker']);
                                $verkoper_Worden->bindParam(2, $gebruikerbank);
                                $verkoper_Worden->bindParam(3, $gebruikerBankrekening);
                                $verkoper_Worden->bindParam(4, $_POST['Controle_optie']);
                                $verkoper_Worden->bindParam(5, $gebruikerCreditcard);
                                $verkoper_Worden->execute();

                                maakVerkoper();
                            }
                            else{
                                if(!$bankFilledIn){
                                    $errorMessageBank = "Dit veld is nu ook verplicht";
                                }
                                else{
                                    $errorMessageBankrekening = "Dit veld is nu ook verplicht";
                                }
                            }
                        }
                        else{
                            $verkoper_Worden_Query = "INSERT INTO Verkoper (Gebruikersnaam, Controleoptienaam, Creditcardnummer) VALUES(?, ?, ?)";
                            $verkoper_Worden = $dbh->prepare($verkoper_Worden_Query);
                            $verkoper_Worden->bindParam(1, $_SESSION['ingelogde_gebruiker']);
                            $verkoper_Worden->bindParam(2, $_POST['Controle_optie']);
                            $verkoper_Worden->bindParam(3, $gebruikerCreditcard);
                            $verkoper_Worden->execute();

                            maakVerkoper();
                        }
                    }
                    //Post
                    else{
                        $verificatie_code = chr(64 + rand(0, 26)) . chr(64 + rand(0, 26)) . chr(64 + rand(0, 26)) . chr(64 + rand(0, 26)) . chr(64 + rand(0, 26));
                        $hash = md5($_SESSION['ingelogde_gebruiker'] . $verificatie_code . 'sadvbsydbfdsbm');

                        $post_Verificatie_Query = "INSERT INTO PostVerificatie (Gebruikersnaam, VerificatieCode, VerificatieCodeUnhased) VALUES (?, ?, ?)";
                        $post_Verificatie = $dbh->prepare($post_Verificatie_Query);
                        $post_Verificatie->bindParam(1, $_SESSION['ingelogde_gebruiker'], PDO::PARAM_STR);
                        $post_Verificatie->bindParam(2, $hash, PDO::PARAM_STR);
                        $post_Verificatie->bindParam(3, $verificatie_code, PDO::PARAM_STR);
                        $post_Verificatie->execute();

                        if($creditcardFilledIn){
                            $verkoper_Worden_Query = "INSERT INTO Verkoper (Gebruikersnaam, Banknaam, Rekeningnummer, Controleoptienaam, Creditcardnummer) VALUES (?, ?, ?, ?, ?)";
                            $verkoper_Worden = $dbh->prepare($verkoper_Worden_Query);
                            $verkoper_Worden->bindParam(1, $_SESSION['ingelogde_gebruiker'], PDO::PARAM_STR);
                            $verkoper_Worden->bindParam(2, $gebruikerbank, PDO::PARAM_STR);
                            $verkoper_Worden->bindParam(3, $gebruikerBankrekening, PDO::PARAM_STR);
                            $verkoper_Worden->bindParam(4, $_POST['Controle_optie'], PDO::PARAM_STR);
                            $verkoper_Worden->bindParam(5, $gebruikerCreditcard, PDO::PARAM_STR);
                            $verkoper_Worden->execute();
                        }
                        else{
                            $verkoper_Worden_Query = "INSERT INTO Verkoper (Gebruikersnaam, Banknaam, Rekeningnummer, Controleoptienaam) VALUES (?, ?, ?, ?)";
                            $verkoper_Worden = $dbh->prepare($verkoper_Worden_Query);
                            $verkoper_Worden->bindParam(1, $_SESSION['ingelogde_gebruiker'], PDO::PARAM_STR);
                            $verkoper_Worden->bindParam(2, $gebruikerbank, PDO::PARAM_STR);
                            $verkoper_Worden->bindParam(3, $gebruikerBankrekening, PDO::PARAM_STR);
                            $verkoper_Worden->bindParam(4, $_POST['Controle_optie'], PDO::PARAM_STR);
                            $verkoper_Worden->execute();
                        }
                    }
                }
            }
        }
    }
    else if(isset($_POST['optionSelected'])){
        $step1 = true;
    }

    function maakVerkoper(){
        global $dbh;

        $update_Gebruiker_Naar_Verkoper_Query = "UPDATE Gebruiker SET Verkoper = 1 WHERE Gebruikersnaam = :gebruiker";
        $update_Gebruiker_Naar_Verkoper = $dbh->prepare($update_Gebruiker_Naar_Verkoper_Query);
        $update_Gebruiker_Naar_Verkoper->bindParam(":gebruiker", $_SESSION['ingelogde_gebruiker'], PDO::PARAM_STR);
        $update_Gebruiker_Naar_Verkoper->execute();
    }

    function isRequired($Controle_optie, $inputField){
        global $Verification_Option1;
        global $Verification_Option2;
        global $creditcard;
        global $bank;
        global $bankrekening;

        switch($Controle_optie){
            case $Verification_Option1:
                if($inputField == $creditcard){
                    return true;
                }
                break;

            case $Verification_Option2:
                if($inputField == $bank || $inputField == $bankrekening){
                    return true;
                }
                break;

            default:
                return false;
                break;
        }
    }

    function placeStar($Controle_optie, $inputField){
        if(isRequired($Controle_optie, $inputField)){
            return " *";
        }
    }

    function form2(){
        global $creditcard;
        global $bank;
        global $bankrekening;

        global $errorMessageBank;
        global $errorMessageBankrekening;
        global $errorMessageCreditcard;

        $bankValue = "";
        $bankrekeningValue = "";
        $creditcardValue = "";

        if(isset($_POST[$bank]) && $_POST[$bank] != NUll){
            $bankValue = $_POST[$bank];
        }
        if(isset($_POST[$bankrekening]) && $_POST[$bankrekening] != NUll){
            $bankrekeningValue = $_POST[$bankrekening];
        }
        if(isset($_POST[$creditcard]) && $_POST[$creditcard] != NUll){
            $creditcardValue = $_POST[$creditcard];
        }

        echo '
            <h4>' . $_POST['Controle_optie'] . '</h4>
            <form action="#" method="POST">
                <label>' . $bank . placeStar($_POST['Controle_optie'], $bank) . '</label>
                <p>' . $errorMessageBank . '</p>
                <input type="text" placeholder="Allen kleine letters en hoofdletters. Min 3, Max 20" name="' . $bank . '" value="' . $bankValue . '">
                <label>' . $bankrekening . placeStar($_POST['Controle_optie'], $bankrekening) . '</label>
                <p>' . $errorMessageBankrekening . '</p>
                <input type="text" placeholder="Alleen Hoofdletters en Cijfers. Min 15, Max 30" name="' . $bankrekening . '" value="' . $bankrekeningValue . '">
                <label>' . $creditcard . placeStar($_POST['Controle_optie'], $creditcard) . '</label>
                <p>' . $errorMessageCreditcard . '</p>
                <input type="text" placeholder="Alleen Cijfers. Min 13, Max 19" name="' . $creditcard . '" value="' . $creditcardValue . '">
                <p>* verplichte velden</p>
                <p>** Als bank ingevuld is, dan is bankrekening niet meer optioneel en andersom (Alleen bij creditcard controle.)</p>
                <input type="hidden" value="' . $_POST['Controle_optie'] . '" name="Controle_optie">
                <input type="submit" value="Verstuur aanvraag" name="Verkoper_aanvraag" class="button expanded float-right">
            </form>
            ';
    }

    function form1(){
        global $Verification_Option1;
        global $Verification_Option2;
        echo '
            <form action="#" method="POST">
                <label>Controle optie</label>
                <select name="Controle_optie">
                    <option value="' . $Verification_Option1 . '">' . $Verification_Option1 . '</option>
                    <option value="' . $Verification_Option2 . '">' . $Verification_Option2 . '</option>
                </select>
                <input type="submit" value="Next" name="optionSelected" class="button expanded float-right">
            </form>
            ';
    }
?>

<body>

    <?php include_once "components/header.php"; ?>

    <div class="grid-container">
        <div class="grid-x">
            <div class="cell large-5 float-center">
                <?php
                    if($step1 || $step2InProgress){
                        form2();
                    }
                    else{
                        form1();
                    }
                ?>
            </div>
        </div>
    </div>

    <?php include "components/scripts.html"; ?>

</body>

</html>
