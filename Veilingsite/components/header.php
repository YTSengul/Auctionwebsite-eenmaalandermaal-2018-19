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

<div class="grid-x grid-padding-x">
    <div class="medium-12 cell top-nav">
        <div class="medium-12 cell">
            <ul class="dropdown menu" data-dropdown-menu>
                <li><a href="#">Rubriek 1</a></li>
                <li><a href="#">Rubriek 2</a></li>
                <li><a href="#">Rubriek 3</a></li>
                <li><a href="#">Rubriek 4</a></li>
                <li><a href="#">Rubriek 5</a></li>
                <li><a href="#">Rubriek 6</a></li>
                <li><a href="#">Rubriek 7</a></li>
                <li><a href="#">Meer</a>
                    <ul class="menu vertical">
                        <li><a href="#">One</a></li>
                        <li><a href="#">Two</a></li>
                        <li><a href="#">Three</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
