<?php
    function isVerkoper($Gebruiker){
        global $dbh;
        $check_Is_Verkoper_query = "SELECT Verkoper FROM Gebruiker WHERE Gebruikersnaam = :gebruikersnaam";
        $check_Is_Verkoper = $dbh->prepare($check_Is_Verkoper_query);
        $check_Is_Verkoper->bindParam(":gebruikersnaam", $Gebruiker, PDO::PARAM_STR);
        $check_Is_Verkoper->execute();

        $isVerkoper = $check_Is_Verkoper->fetch(PDO::FETCH_OBJ)->Verkoper;

        if($isVerkoper == 1){
            return true;
        }
        else{
            return false;
        }
    }

    function moetPostverifictatie($Gebruiker){
        global $dbh;
        $check_Moet_Verificatie_query = "SELECT VerificatieCode FROM PostVerificatie WHERE Gebruikersnaam = :gebruikersnaam";
        $check_Moet_Verificatie = $dbh->prepare($check_Moet_Verificatie_query);
        $check_Moet_Verificatie->bindParam(":gebruikersnaam", $Gebruiker, PDO::PARAM_STR);
        $check_Moet_Verificatie->execute();

        if($check_Moet_Verificatie->rowCount() != 0){
            return true;
        }
        else{
            return false;
        }
    }

 ?>



<div class="header hide-for-small-only">
    <div class="grid-container noPadding">
        <div class="grid-x">
            <div class="medium-12 cell header">
                <h1><a href="index.php">Eenmaal Andermaal</a></h1>
                <ul class="menu align-right">
                    <?php
                    if (!isset($_SESSION['ingelogde_gebruiker'])) {
                        echo '
                            <div class="menu menu-account">
                                <li><a class="blackHover" href="login.php"> Inloggen</a></li>
                                <li><p>|</p></li>
                                <li><a class="blackHover" href="pre-registreer.php"> Registreren</a></li>
                            </div>
                        ';
                    } else {
                        echo '
                            <li><a class="blackHover" href="mijn_profiel.php"> Mijn profiel</a></li>
                            <li><a>|</a></li>
                        ';
                        if(isVerkoper($_SESSION['ingelogde_gebruiker'])){
                            echo '
                                <li><a class="blackHover" href="verkopen_object.php"> Verkopen object</a></li>
                                <li><a>|</a></li>
                            ';
                        }
                        else if(moetPostverifictatie($_SESSION['ingelogde_gebruiker'])){
                            echo '
                                <li><a class="blackHover" href="verkopersCodeInvoeren.php"> Verkoper code invoeren</a></li>
                                <li><a>|</a></li>
                            ';
                        }
                        else{
                            echo '
                                <li><a class="blackHover" href="verkoper_worden.php"> Verkoper worden</a></li>
                                <li><a>|</a></li>
                            ';
                        }
                        echo'
                            <li><a class="blackHover" href="logout.php"> Uitloggen</a></li>
                        ';

                        if ($_SESSION['ingelogde_gebruiker'] == 'y.t.sengul') {
                            echo '
                                <li><a>|</a></li>
                                <li><a class="blackHover" href="beheerpagina.php"> beheerpagina</a></li>
                            ';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include_once "rubriekenBoom.php"; ?>

<!-- Header voor telefoon -->

<div class="off-canvas-content data-off-canvas-content">
    <div class="ecommerce-header-mobile hide-for-medium">
        <div class="ecommerce-header-mobile-left spaceAround">
            <h1 class="noPadding HeaderPhoneSpacings">EenmaalAndermaal</h1>
            <button class="menu-icon" type="button" data-toggle="ecommerce-header"></button>
        </div>
    </div>
</div>

<div class="off-canvas ecommerce-header-off-canvas position-left" id="ecommerce-header" data-off-canvas>

    <!-- Close button -->
    <button class="close-button" aria-label="Close menu" type="button" data-close>
        <span aria-hidden="true">&times;</span>
    </button>

    <ul class="vertical menu">
        <li><a href="#">EenmaalAndermaal</a></li>
    </ul>

    <hr>

    <ul class="vertical menu">
        <?php
        if (!isset($_SESSION['ingelogde_gebruiker'])) {
            echo '
                    <li><a href="login.php"> Inloggen</a></li>
                    <li><a href="registreren.php"> Registreren</a></li>
                ';
        } else {
            echo '<li><a href="logout.php"> uitloggen</a></li>';
        }
        ?>
        <li><a href="#">Rubriekenboom</a></li>
    </ul>

</div>
