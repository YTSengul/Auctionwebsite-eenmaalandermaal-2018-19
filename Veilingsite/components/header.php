
<div class="grid-x">
    <div class="medium-12 cell header">
        <h1>Eenmaal Andermaal</h1>
        <ul class="menu align-right">
            <?php if (!isset($_SESSION['ingelogde_gebruiker'])) {
                echo '<li ><a href = "login.php" > Inloggen</a ></li >
        <li ><p >|</p ></li >
        <li ><a href = "registreren.php" > Registreren</a ></li >';
            } else {
                echo '<li ><a href = "logout.php" > uitloggen</a ></li >';
            }
            ?>
        </ul>
    </div>
</div>

<!-- NOTE: This is the off-canvas menu that appears when you click on the hamburger menu on smaller screens. Everything in the `.off-canvas` div belongs in `src/layouts/default.html`. Copy this section into the `default.html` file and remove it from this file.  -->
<div class="off-canvas ecommerce-header-off-canvas position-left" id="ecommerce-header" data-off-canvas>

    <!-- Close button -->
    <button class="menu-icon float-right opened-icon" type="button" data-toggle="ecommerce-header"></button>


    <ul class="vertical menu">
        <li class="main-nav-link"><a href="#">Category 1</a></li>
    </ul>

    <hr>

    <!-- Menu -->
    <ul class="menu vertical">
        <li><a href="#">Help</a></li>
        <li><a href="#">Order Status</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="#">My Account</a></li>
    </ul>

</div>

<!-- NOTE: This is the header menu that appears at the top of your site. -->
<div class="off-canvas-content data-off-canvas-content">

    <div class="ecommerce-header show-for-large">
        <div class="row align-justify align-middle">
            <div class="shrink column">
                <ul class="vertical medium-horizontal menu">
                    <li class="main-nav-link"><a href="categories.html">Category 1</a></li>
                    <li class="main-nav-link"><a href="#">Category 2</a></li>
                    <li class="main-nav-link"><a href="why.html">Category 3</a></li>
                    <li class="main-nav-link"><a href="build.html">Category 4</a></li>
                    <li class="main-nav-link"><a href="#">Category 5</a></li>
                    <li class="main-nav-link"><a href="#">Category 6</a></li>
                    <li class="main-nav-link"><a href="#">Category 7</a></li>
                    <li class="main-nav-link"><a href="#">Category 8</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="ecommerce-header-mobile hide-for-large">
        <div class="ecommerce-header-mobile-left">
            <button class="menu-icon" type="button" data-toggle="ecommerce-header"></button>
        </div>
    </div>
</div>



<?php

?>
