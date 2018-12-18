<?php
    Function rubriekenBoom(){
        global $dbh;
        /** Show all Main categories. */
        $previousCategory = -1;
        /** Show amount of sub categories under main category. */
        $amountOfSubcategoriesShown = 3;
        /** Query to select all main categories from the database. Ordered by alphabet. */
        $queryMainCategories = "SELECT Rubrieknaam, Rubrieknummer FROM Rubriek WHERE VorigeRubriek = $previousCategory ORDER BY Rubrieknaam ASC";

        $hoofdRubrieken = $dbh->prepare($queryMainCategories);
        $hoofdRubrieken->execute();
        while($hoofdRubriek = $hoofdRubrieken->fetch()){

            echo '
                <div class="cell medium-6 large-3 categoryListSpacing">
                <li><a class="redHover" href="#"><b>'.$hoofdRubriek['Rubrieknaam'].'</b></a></li>
                <hr>
            ';

            /** Query to select the first 3 sub categories from the main categories. Ordered by popularity and then alphabet. */
            $querySubCategories = "SELECT TOP $amountOfSubcategoriesShown Rubrieknaam, Rubrieknummer FROM Rubriek WHERE VorigeRubriek = '$hoofdRubriek[Rubrieknummer]' ORDER BY Volgnummer, Rubrieknaam ASC";

            $subRubrieken = $dbh->prepare($querySubCategories);
            $subRubrieken->execute();
            while($subRubriek = $subRubrieken->fetch()){

                echo '<li><a class="redHover" href="veilingen.php?filter_rubriek='.$subRubriek['Rubrieknummer'].'">'.$subRubriek['Rubrieknaam'].'</a></li>';

            }
            echo '</div>';
        }
    }

    Function rubriekenHeader(){
        global $dbh;

        /** Show all Main categories. */
        $previousCategory = -1;
        /** Amounf of main categories to be shown on the website */
        $amountOfMainCategoriesShown = 7;
        /** Query to select all main categories from the database. Ordered by popularity and then alphabet. */
        $queryMainCategories = "SELECT top $amountOfMainCategoriesShown Rubrieknaam, Rubrieknummer FROM Rubriek WHERE VorigeRubriek = $previousCategory ORDER BY Volgnummer, Rubrieknaam ASC";

        $hoofdRubrieken = $dbh->prepare($queryMainCategories);
        $hoofdRubrieken->execute();
        while($hoofdRubriek = $hoofdRubrieken->fetch()){

            echo '<li class="noPadding noMargins"><a class="HeaderASpacings" href="veilingen.php?filter_rubriek='.$hoofdRubriek['Rubrieknummer'].'">'.$hoofdRubriek['Rubrieknaam'].'</a></li>';

        }
    }
?>
<div class="grid-container noPadding secondHeaderColour">
    <div class="grid-x hide-for-small-only">
        <div class="medium-12 cell">
            <ul class="dropdown menu spaceAround" data-dropdown-menu>
                <?php rubriekenHeader(); ?>
                <li class="noPadding noMargins makeStatic"><a class="HeaderASpacings" href="#">Meer</a>
                    <ul class="menu megaMenuSize noBorder">
                        <div class="grid-x grid-margin-x megaMenuColourandBox">
                            <?php rubriekenBoom(); ?>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
