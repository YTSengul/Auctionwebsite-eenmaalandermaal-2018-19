<?php
    include_once "components/connect.php";
    include_once "components/meta.php";

    $Verification_Option1 = "Creditcard";
    $Verification_Option2 = "Post";

    $creditcard = "Creditcard";
    $bank = "Bank";
    $bankrekening = "Bankrekening";

    $bankJuist = false;
    $bankrekeningJuist = false;
    $creditcardJuist = false;

    if (!isset($_SESSION['ingelogde_gebruiker'])) {
        header('location: index.php');
    }

    $step1 = false;


    if(isset($_POST['Verkoper_aanvraag'])){
        if(isset($_POST['Controle_optie'])){
            if ($_POST['Controle_optie'] == $Verification_Option1 || $_POST['Controle_optie'] == $Verification_Option2) {

                if(isset($_POST[$bank])){
                    $gebruikerbank = $_POST[$bank];
                    //alleen kleine letters en hoofdletters.
                    // Langer dan 2 en korter dan 21.
                    if (preg_match("/^[a-zA-z]{3-20}+$/", $gebruikerbank)) {
                        $bankJuist = true;
                    }
                }

                if(isset($_POST[$bankrekening])){
                    $gebruikerBankrekening = $_POST[$bankrekening];
                    //Alleen cijfers en hoofdletters
                    //Moet beginnen met een land (NL, BE, DE, etc).
                    //Mauritius Iban nummer heeft 30 karakters, Noorwegen Iban nummer heeft 15 karakters.
                    if (preg_match("/^[A-Z]{2}+[A-Z0-9]{13,28}+$/", $gebruikerBankrekening)) {
                        $bankrekeningJuist = true;
                    }
                }

                if(isset($_POST[$creditcard])){
                    $gebruikerCreditcard = $_POST[$creditcard];
                    //Alleen cijfers
                    //Switch en Solo zijn 2 credit cards van de UK die 19 cijfers hebben.
                    //Visa heeft als laagste 13 cijfers.
                    if (preg_match("/^[0-9]{13,19}+$/", $gebruikerCreditcard)) {
                        $creditcardJuist = true;
                    }
                }

                if(!isRequired($_POST['Controle_optie'], $bank)){
                    echo "Bank not needed!";
                }

                if(!isRequired($_POST['Controle_optie'], $bankrekening)){
                    echo "Bankaccount not needed!";
                }

                if(!isRequired($_POST['Controle_optie'], $creditcard)){
                    echo "creditcard not needed!";
                }

            }
        }
    }
    else if(isset($_POST['optionSelected'])){
        $step1 = true;
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

        echo '
            <form action="#" method="POST">
                <label>' . $bank . placeStar($_POST['Controle_optie'], $bank) . '</label>
                <input type="text" placeholder="' . $bank . '" name="' . $bank . '">
                <label>' . $bankrekening . placeStar($_POST['Controle_optie'], $bankrekening) . '</label>
                <input type="text" placeholder="' . $bankrekening . '" name="' . $bankrekening . '">
                <label>' . $creditcard . placeStar($_POST['Controle_optie'], $creditcard) . '</label>
                <input type="text" placeholder="' . $creditcard . '" name="' . $creditcard . '">
                <p>* verplichte velden</p>
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
                    if($step1){
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
