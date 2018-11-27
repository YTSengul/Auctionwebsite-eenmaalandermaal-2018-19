<?php
  Function rubriekenBoom(){
    global $dbh;

    $hoofdRubrieken = $dbh->query("SELECT Rubrieknaam, Rubrieknummer FROM Rubriek WHERE Rubriek = -1 ORDER BY Volgnr, Rubrieknaam ASC");
    while($hoofdRubriek = $hoofdRubrieken->fetch()){

      echo "<div class='cell medium-6 large-3 rubriekSpacing'>";
      echo "<li><a class='hoofdRubriek' href='#'><b>$hoofdRubriek[Rubrieknaam]</b></a></li>";
      echo "<hr>";

      $subRubrieken = $dbh->query("SELECT TOP 3 Rubrieknaam FROM Rubriek WHERE Rubriek = '$hoofdRubriek[Rubrieknummer]' ORDER BY Volgnr, Rubrieknaam ASC");
      while($subRubriek = $subRubrieken->fetch()){

        echo "<li><a class='subRubriek' href='#'>$subRubriek[Rubrieknaam]</a></li>";
      }
      echo "</div>";
    }
  }

  Function rubriekenHeader(){
    global $dbh;

    $hoofdRubrieken = $dbh->query("SELECT top 7 Rubrieknaam, Rubrieknummer FROM Rubriek WHERE Rubriek = -1 ORDER BY Volgnr, Rubrieknaam ASC");
    while($hoofdRubriek = $hoofdRubrieken->fetch()){

      echo "<li><a href='#'>$hoofdRubriek[Rubrieknaam]</a>";
      echo "<ul class='menu'>";

      $subRubrieken = $dbh->query("SELECT Rubrieknaam FROM Rubriek WHERE Rubriek = '$hoofdRubriek[Rubrieknummer]' ORDER BY Volgnr, Rubrieknaam ASC");
      while($subRubriek = $subRubrieken->fetch()){

        echo "<li><a href'#'>$subRubriek[Rubrieknaam]</a></li>";
      }
      echo "</ul></li>";
    }
  }
?>

<div class="top-nav">
  <div class="medium-12 cell">
    <ul class="dropdown menu" data-dropdown-menu>
      <?php rubriekenHeader(); ?>
      <li><a href="#">Meer</a>
        <ul class="menu MegaMenu">
          <div class="grid-x grid-margin-x colourAndBoxMegaMenu">
            <?php rubriekenBoom(); ?>
          </div>
        </ul>
      </li>
    </ul>
  </div>
</div>
fix