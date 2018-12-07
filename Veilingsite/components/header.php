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
                                <li><a class="blackHover" href="registreren.php"> Registreren</a></li>
                                </div>
                            ';
                        }
                        else {
                            echo '<li><a class="blackHover" href="logout.php"> uitloggen</a></li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include_once "rubriekenBoom.php"; ?>

<div class="off-canvas-content data-off-canvas-content">
    <div class="ecommerce-header-mobile hide-for-medium">
        <div class="ecommerce-header-mobile-left spreadContents">
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
            }
            else {
                echo '<li><a href="logout.php"> uitloggen</a></li>';
            }
        ?>
        <li><a href="#">Rubriekenboom</a></li>
    </ul>

</div>
