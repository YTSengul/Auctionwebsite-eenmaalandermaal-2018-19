<?php
  Function rubriekenBoom(){
    global $dbh;

    $hoofdRubrieken = $dbh->query("SELECT Rubrieknaam, Rubrieknummer FROM Rubriek WHERE Rubriek = -1 ORDER BY Volgnr, Rubrieknaam ASC");
    while($hoofdRubriek = $hoofdRubrieken->fetch()){

      echo "<div class='cell medium-6 large-3 rubriekSpacing'>";
      echo "<li><a class='hoofdRubriek specialsomething' href='#'><b>$hoofdRubriek[Rubrieknaam]</b></a></li>";
      echo "<hr>";

      $subRubrieken = $dbh->query("SELECT TOP 3 Rubrieknaam FROM Rubriek WHERE Rubriek = '$hoofdRubriek[Rubrieknummer]' ORDER BY Volgnr, Rubrieknaam ASC");
      while($subRubriek = $subRubrieken->fetch()){

        echo "<li><a class='subRubriek specialsomething' href='#'>$subRubriek[Rubrieknaam]</a></li>";
      }
      echo "</div>";
    }
  }

  Function rubriekenHeader(){
    global $dbh;

    $hoofdRubrieken = $dbh->query("SELECT top 7 Rubrieknaam, Rubrieknummer FROM Rubriek WHERE Rubriek = -1 ORDER BY Volgnr, Rubrieknaam ASC");
    while($hoofdRubriek = $hoofdRubrieken->fetch()){

      echo "<li class='spacingHeaderRubrieken noPadding noMargins'><a class='marginAndPaddingA' href='#'>$hoofdRubriek[Rubrieknaam]</a></li>";
    }
  }
?>
<div class="grid-container noPadding colourHeader">
    <div class="grid-x hide-for-small-only">
        <div class="medium-12 cell">
            <ul class="dropdown menu centerText" data-dropdown-menu>
                <?php rubriekenHeader(); ?>
                <li class="spacingHeaderRubrieken noPadding noMargins test"><a class="marginAndPaddingA" href="#">Meer</a>
                    <ul class="menu MegaMenu noBorder">
                        <div class="grid-x grid-margin-x colourAndBoxMegaMenu">
                            <?php rubriekenBoom(); ?>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
