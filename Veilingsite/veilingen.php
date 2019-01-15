<?php
include_once "components/connect.php";
include_once "components/meta.php";
?>

<?PHP
// er wordt gekeken op welke pagina de gebruiker zich bevindt, indien nihil, wordt er vanzel 1 aangegevem
if (isset($_GET['huidigepagina'])) {
    $huidigepagina = $_GET['huidigepagina'];
} else {
    $huidigepagina = 1;
}

//Hier wordt berekend tussen welke nummers de veilingen genomen moeten worden
$aantalveilingen_per_pagina = 10;
$vanaf_veiling = ($huidigepagina - 1) * $aantalveilingen_per_pagina;
$tot_veiling = $vanaf_veiling + ($aantalveilingen_per_pagina);
if ($huidigepagina != 1) {
    $tot_veiling = $vanaf_veiling + ($aantalveilingen_per_pagina - 1);
}

// Hier wordt gekeken of er een rubriek is ingegeven, zo ja, word die uit de database gehaald
if (isset($_GET['filter_rubriek'])) {
    $filter_rubriek = $_GET['filter_rubriek'];
    $_SESSION['filter_rubriek'] = $filter_rubriek;
} else if (isset($_SESSION['filter_rubriek'])) {
    $filter_rubriek = $_SESSION['filter_rubriek'];
} else {
    $filter_rubriek = -1;
}

// De gegevens van de veilingen worden uit de database gehaald
$sql_veilingen_query = "with cte
    as (select Rubrieknummer
        from Rubriek as t
        where RubriekNummer = $filter_rubriek
        UNION ALL
        select t.RubriekNummer
        from Rubriek as t
        join cte
        on t.VorigeRubriek = cte.RubriekNummer)
SELECT VoorwerpInRubriek.RubriekOpLaagsteNiveau, Voorwerp.Voorwerpnummer, Voorwerp.Titel, Voorwerp.Beschrijving, Voorwerp.EindMoment, Voorwerp.Thumbnail
FROM (
     SELECT *, ROW_NUMBER() OVER (ORDER BY EindMoment) AS RowNum
     FROM Voorwerp INNER JOIN VoorwerpInRubriek ON voorwerp = voorwerpnummer, cte WHERE VeilingGesloten = 0 AND VoorwerpInRubriek.RubriekOpLaagsteNiveau = cte.RubriekNummer
	 ) AS Voorwerp INNER JOIN VoorwerpInRubriek ON VoorwerpInRubriek.Voorwerp = Voorwerp.voorwerpnummer
WHERE Voorwerp.RowNum BETWEEN $vanaf_veiling AND $tot_veiling";

// de veilingen worden opgeslagen in een array
$sql_veilingen_data = $dbh->prepare($sql_veilingen_query);
$sql_veilingen_data->execute();
$veilingen = $sql_veilingen_data->fetchAll(PDO::FETCH_NUM);
$aantalveilingen = sizeOf($veilingen);


// hier wordt de laatste pagina van de veilingen gezocht
$sql_laatste_pagina_query = "with cte
as (select Rubrieknummer
from Rubriek as t
where RubriekNummer = $filter_rubriek
UNION ALL
select t.RubriekNummer
from Rubriek as t
join cte
on t.VorigeRubriek = cte.RubriekNummer)
SELECT CEILING(CAST(COUNT(*)as float)/ $aantalveilingen_per_pagina)
FROM (
     SELECT *, ROW_NUMBER() OVER (ORDER BY EindMoment) AS RowNum
     FROM Voorwerp INNER JOIN VoorwerpInRubriek ON voorwerp = voorwerpnummer, cte
	 WHERE VeilingGesloten = 0 AND RubriekOpLaagsteNiveau = cte.Rubrieknummer
     ) AS Voorwerp INNER JOIN VoorwerpInRubriek ON VoorwerpInRubriek.Voorwerp = Voorwerp.voorwerpnummer";

$laatste_pagina;

function filter_rubrieken()
{
    global $dbh;
    global $sql_laatste_pagina_query;
    global $filter_rubriek;

// De laatste pagina wordt opgeslagen in een array
    $sql_laatste_pagina_data = $dbh->prepare($sql_laatste_pagina_query);
    $sql_laatste_pagina_data->execute();
    global $laatste_pagina;
    $laatste_pagina = $sql_laatste_pagina_data->fetchAll(PDO::FETCH_NUM);
    $laatste_pagina = $laatste_pagina[0][0];

// Hier worden de rubrieken gevonden om in de filter te laten zien
    $vind_rubrieken_voor_filter_query = "select * from Rubriek where VorigeRubriek = $filter_rubriek order by Volgnummer, RubriekNaam ASC";
    $vind_rubrieken_voor_filter_data = $dbh->prepare($vind_rubrieken_voor_filter_query);
    $vind_rubrieken_voor_filter_data->execute();
    $vind_rubrieken_filter = $vind_rubrieken_voor_filter_data->fetchAll(PDO::FETCH_NUM);

    if (sizeof($vind_rubrieken_filter) == 0) {
        global $dbh;
        global $sql_laatste_pagina_query;

// De laatste pagina wordt opgeslagen in een array
        $sql_laatste_pagina_data = $dbh->prepare($sql_laatste_pagina_query);
        $sql_laatste_pagina_data->execute();
        global $laatste_pagina;
        $laatste_pagina = $sql_laatste_pagina_data->fetchAll(PDO::FETCH_NUM);
        $laatste_pagina = $laatste_pagina[0][0];

// Hier worden de rubrieken gevonden om in de filter te laten zien
        $vind_rubrieken_voor_filter_query = "select * from Rubriek where VorigeRubriek = " . $_SESSION['vollevorigerubriek'] . " order by Volgnummer, RubriekNaam ASC";
        $vind_rubrieken_voor_filter_data = $dbh->prepare($vind_rubrieken_voor_filter_query);
        $vind_rubrieken_voor_filter_data->execute();
        $vind_rubrieken_filter = $vind_rubrieken_voor_filter_data->fetchAll(PDO::FETCH_NUM);

    } else {
        $_SESSION['vollevorigerubriek'] = $filter_rubriek;
    }

// De rubrieken worden toegevoegd aan de filter
    foreach ($vind_rubrieken_filter as $rubriek) {
        //echo '<pre>';
        //var_dump($rubriek);
        //echo '</pre>';
        if ($rubriek[0] == $filter_rubriek) {
            echo "<li><label><a href='veilingen.php?huidigepagina=1&filter_rubriek=$rubriek[0]' ><b>$rubriek[1]</b></a></label></li>";
        } else {
            echo "<li><label><a href='veilingen.php?huidigepagina=1&filter_rubriek=$rubriek[0]' >$rubriek[1]</a></label></li>";
        }
    }
}

// hier zijn de breadcrumbs in te vinden
function create_Breadcrumbs($RubriekNummer)
{
    global $dbh;
    $vind_hoofdrubriek_query = "select * from Rubriek where RubriekNummer = $RubriekNummer";
    $vind_hoofdrubriek_data = $dbh->prepare($vind_hoofdrubriek_query);
    $vind_hoofdrubriek_data->execute();
    $vind_hoofdrubriek = $vind_hoofdrubriek_data->fetchAll(PDO::FETCH_NUM);
    $hoofdrubriek = $vind_hoofdrubriek[0];
    return $hoofdrubriek;
}

// hier wordt de array van de breadcrumb gemaakt
$breadcrumbs_namen = array();
$breadcrumbs_nummers = array();
function call_Breadcrumbs($filter_rubriek, &$breadcrumbs_namen, &$breadcrumbs_nummers)
{
    $rubriek_voor_hoofdcategorie = NULL;
    if ($filter_rubriek != -1) {
        for ($x = 0; $rubriek_voor_hoofdcategorie[2] != -1; $x++) {
            if ($rubriek_voor_hoofdcategorie != NULL) {
                $rubriek_voor_hoofdcategorie = create_Breadcrumbs($rubriek_voor_hoofdcategorie[2]);
                array_push($breadcrumbs_namen, "$rubriek_voor_hoofdcategorie[1]");
                array_push($breadcrumbs_nummers, "$rubriek_voor_hoofdcategorie[0]");
            } else {
                $rubriek_voor_hoofdcategorie = create_Breadcrumbs($filter_rubriek);
                array_push($breadcrumbs_namen, "$rubriek_voor_hoofdcategorie[1]");
                array_push($breadcrumbs_nummers, "$rubriek_voor_hoofdcategorie[0]");
            }
        }
    }
    sort_show_breadcrumbs($breadcrumbs_namen, $breadcrumbs_nummers);
}

//hier worden de breadcrumbs op rij gezet en geshowed dit wordt gedaan om het van achter naar voor te sorteren

function sort_show_breadcrumbs($breadcrumbs_namen, $breadcrumbs_nummers)
{
    echo "<li><a href='veilingen.php?huidigepagina=1&filter_rubriek=-1'>Hoofdrubrieken</a></li>";
    for ($x = sizeof($breadcrumbs_namen) - 1; $x >= 0; $x--) {
        echo "<li><a href='veilingen.php?huidigepagina=1&filter_rubriek=" . $breadcrumbs_nummers[$x] . "'>$breadcrumbs_namen[$x]</a></li>";
    }
}

?>

<body>

<?php include_once "components/header.php"; ?>

<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 float-center cell">
            <!--- Breadcrumbs -->
            <nav aria-label="You are here:" role="navigation" class="veilingen-breadcrumbs">
                <ul class="breadcrumbs">
                    <?php call_Breadcrumbs($filter_rubriek, $breadcrumbs_namen, $breadcrumbs_nummers); ?>
                    <!---<li>
                        <span class="show-for-sr">Current: </span> Huidige cat.
                    </li>-->
                </ul>
            </nav>

            <!---<select class="float-right veilingen-filter-hoofd ">
                <option>Optie 1</option>
                <option>Optie 2</option>
                <option>Optie 3</option>
                <option>Optie 4</option>
                <option>Optie 5</option>
            </select>-->

        </div>
        <div class="medium-3 large-3 float-center cell">
            <div class="product-filters">
                <ul class="mobile-product-filters vertical menu show-for-small-only" data-accordion-menu>
                    <li>
                        <a href="#"><h2>Rubrieken</h2></a>
                        <ul class="vertical menu" data-accordion-menu>
                            <li class="product-filters-tab">
                                <ul class="categories-menu menu vertical nested is-active">
                                    <a href="#" class="clear-all" id="category-clear-all">Clear All</a>
                                    <li><input class="category-clear-selection" id="category-checkbox1" type="checkbox"><label
                                                for="category-checkbox1">Category 1</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox2" type="checkbox"><label
                                                for="category-checkbox2">Category 2</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox3" type="checkbox"><label
                                                for="category-checkbox3">Category 3</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox4" type="checkbox"><label
                                                for="category-checkbox4">Category 4</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox5" type="checkbox"><label
                                                for="category-checkbox5">Category 5</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox6" type="checkbox"><label
                                                for="category-checkbox6">Category 6</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox7" type="checkbox"><label
                                                for="category-checkbox7">Category 7</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox8" type="checkbox"><label
                                                for="category-checkbox8">Category 8</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox9" type="checkbox"><label
                                                for="category-checkbox9">Category 9</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox10"
                                               type="checkbox"><label for="category-checkbox10">Category 10</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox11"
                                               type="checkbox"><label for="category-checkbox11">Category 11</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox12"
                                               type="checkbox"><label for="category-checkbox12">Category 12</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox13"
                                               type="checkbox"><label for="category-checkbox13">Category 13</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox14"
                                               type="checkbox"><label for="category-checkbox14">Category 14</label></li>
                                    <li><input class="category-clear-selection" id="category-checkbox15"
                                               type="checkbox"><label for="category-checkbox15">Category 15</label></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="vertical menu" data-accordion-menu>
                    <li class="product-filters-tab">
                        <ul class="categories-menu menu vertical nested is-active">
                            <?PHP filter_rubrieken(); ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <?PHP
        if (sizeof($veilingen) == 0) {
            // hier wordt gekeken of een gebruiker niet te ver gaat in de pagina's door de get te veranderen in de url. als hij te ver
            // gaat op deze manier wordt hij doorgestuurd naar pagina 1
            $huidigepagina = 1;

            //Hier wordt berekend tussen welke nummers de veilingen genomen moeten worden
            $aantalveilingen_per_pagina = 9;
            $vanaf_veiling = ($huidigepagina - 1) * $aantalveilingen_per_pagina;
            $tot_veiling = $huidigepagina * $aantalveilingen_per_pagina;

// De gegevens van de veilingen worden uit de database gehaald
            $sql_veilingen_query = "with cte
    as (select Rubrieknummer
        from Rubriek as t
        where RubriekNummer = $filter_rubriek
        UNION ALL
        select t.RubriekNummer
        from Rubriek as t
        join cte
        on t.VorigeRubriek = cte.RubriekNummer)
SELECT VoorwerpInRubriek.RubriekOpLaagsteNiveau, Voorwerp.Voorwerpnummer, Voorwerp.Titel, Voorwerp.Beschrijving, Voorwerp.EindMoment, Voorwerp.Thumbnail
FROM (
     SELECT *, ROW_NUMBER() OVER (ORDER BY EindMoment) AS RowNum
     FROM Voorwerp INNER JOIN VoorwerpInRubriek ON voorwerp = voorwerpnummer WHERE VeilingGesloten = 0
	 ) AS Voorwerp INNER JOIN VoorwerpInRubriek ON VoorwerpInRubriek.Voorwerp = Voorwerp.voorwerpnummer, cte
WHERE Voorwerp.RowNum BETWEEN $vanaf_veiling AND $tot_veiling AND Voorwerp.RubriekOpLaagsteNiveau = cte.Rubrieknummer";

// de veilingen worden opgeslagen in een array
            $sql_veilingen_data = $dbh->prepare($sql_veilingen_query);
            $sql_veilingen_data->execute();
            $veilingen = $sql_veilingen_data->fetchAll(PDO::FETCH_NUM);
            $aantalveilingen = $veilingen;

        }

        // Check of er een afbeelding is gevonden of niet.
        function c_file_exists($file)
        {
            $file_headers = @get_headers($file);
            if (strpos($file_headers[0], '404 Not Found')) {
                return false;
            }
            return true;
        }

        if (sizeof($veilingen) != 0) {
            foreach ($veilingen as $veiling) {

                echo "<div class='small-12 medium-9 large-9 float-center cell'>
            <div class='media-object veilingen-veiling-box '>
                <div class='media-object-section'>";
                if (c_file_exists("http://iproject4.icasites.nl/pics/dt_1_" . substr($veiling[5], 3))) {
                    echo "<img class='thumbnail veilingen-veiling-image' src = 'http://iproject4.icasites.nl/pics/dt_1_" . substr($veiling[5], 3) . "' >";
                } else {
                    echo "<img class='thumbnail veilingen-veiling-image' src='upload/$veiling[5]' alt='Foto van een product'>";
                }
                echo "</div>
                <div class='media-object-section veilingen-veiling-info'>
                    <h5 class='veilingen-veiling-titel float-left'><a href='detailpagina.php?Voorwerpnummer=" . $veiling[1] . "' > >" . substr($veiling[2], 0, 50) . "</a></h5>
                    <h5 class='veilingen-veiling-timer float-right countdown' end='" . $veiling[4] . "' ></h5>
                    <p class='hide-for-small-only veilingen-veiling-omschrijving'>" . substr(strip_tags($veiling[3]), 0, 140) . "</p>
                </div>
            </div>";

            }
        } else {
            echo "<div class='small-12 medium-9 large-9 float-center cell'>
            <div class='media-object veilingen-veiling-box '>
                <div class='media-object-section'>
                    <H2>GEEN VEILINGEN BESCHIKBAAR!</H2>
                </div>
            </div>";
        }
        ?>
        <!--    Hier kun je dingen plaatsen voor onderin in de pagina. ik zou hier een knop plaatsen voor de nav pagina's-->
        <!--    <label>-->
        <!--        My Review-->
        <!--        <textarea placeholder="None"></textarea>-->
        <!--    </label>-->
        <!--    <button class="button">Submit Review</button>-->
        <?PHP
        //de breadcrumbs van de navigatie
        // hier wordt gekeken naar de eerste pagina die onderin in de breadcumbs te zien moet zijn
        if ($huidigepagina == 1 || $laatste_pagina == $huidigepagina) {
            if ($huidigepagina == 1) {
                $pagina_voor_huidige_pagina = 1;
                $pagina_marger = 4;
            } // hier wordt gekeken naar de laatste pagina die onderin in de breadcumbs te zien moet zijn
            else if ($laatste_pagina == $huidigepagina) {
                $hoeveel_paginas_voor_huidige_laatste_pagina = 3;
                $pagina_voor_huidige_pagina = ($huidigepagina - $hoeveel_paginas_voor_huidige_laatste_pagina);
                $pagina_marger = 1;
            } // hier wordt gekeken naar de eerste pagina die onderin in de breadcumbs te zien moet zijn
            else {
                $hoeveel_paginas_voor_huidige_pagina = 1;
                $pagina_voor_huidige_pagina = ($huidigepagina - $hoeveel_paginas_voor_huidige_pagina);
                $pagina_marger = 3;
            }
        }  // hier wordt gekeken naar de eenalaatste pagina die onderin in de breadcumbs te zien moet zijn
        else if ($laatste_pagina - 1 == $huidigepagina) {
            $hoeveel_paginas_voor_huidige_eenalaatste_pagina = 2;
            $pagina_voor_huidige_pagina = ($huidigepagina - $hoeveel_paginas_voor_huidige_eenalaatste_pagina);
            $pagina_marger = 2;
        } else {
            $pagina_voor_huidige_pagina = ($huidigepagina - 1);
            $pagina_marger = 3;
        }

        // Met deze knop kan de gebruiker naar de eerste pagina gaan
        echo "<ul class='pagination text-center' role='navigation' aria-label='Pagination' data-page='6' data-total='16'>";
        if ($huidigepagina == 1) {
            echo "<li class='pagination-previous disabled'>Eerste pagina <span class='show-for-sr'>page</span></li>";
        } else {
            echo "<li class='pagination-previous'><a href='veilingen.php?huidigepagina=1' aria-label='Next page'>Eerste pagina <span class='show-for-sr'>page</span></li>";
        }

        // Hier wordt gekeken of de knop 'Vorige' op disabled kan staan
        if ($huidigepagina == 1) {
            echo "<li class='pagination-previous disabled'>Vorige <span class='show-for-sr'>page</span></li>";
        } else {
            echo "<li class='pagination-previous'><a href='veilingen.php?huidigepagina=$pagina_voor_huidige_pagina' aria-label='Next page'>Vorige <span class='show-for-sr'>page</span></li>";
        }

        // hier worden de paginanummers toegevoegd aan de pagina
        for ($x = $pagina_voor_huidige_pagina; $x < $huidigepagina + $pagina_marger; $x++) {
            if ($x == $huidigepagina) {
                echo "<li class='current'><span class='show-for-sr'>You're on page</span> $x</li>";
            } else if ($x > 0 & $x <= $laatste_pagina) {
                echo "<li><a href='veilingen.php?huidigepagina=$x' >$x</a></li>";
            }
        }

        // hier wordt gekeken of je op de laatste pagina bent van de veilingen, zo ja wordt de volgende knop disabled
        if ($huidigepagina == $laatste_pagina) {
            echo "<li class='pagination-next'><a href='#' aria-label='Next page' class='disabled' >Volgende <span
                            class='show-for-sr''>page</span></a></li>";
        } else {
            echo "<li class='pagination-next'><a href='veilingen.php?huidigepagina=" . ($huidigepagina += 1) . "' aria-label='Next page' >Volgende <span
                            class='show-for-sr''>page</span></a></li>";
        }

        // Met deze knop kan de gebruiker naar de laatste pagina gaan
        if ($huidigepagina == 1) {
            echo "<li class='pagination-next disabled'>Laatste pagina <span class='show-for-sr'>page</span></li>";
        } else {
            echo "<li class='pagination-next'><a href='veilingen.php?huidigepagina=" . $laatste_pagina . "' aria-label='Next page'>Laatste pagina <span class='show-for-sr'>page</span></li> </ul>";
        }
        ?>

    </div>
</div>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://dhbhdrzi4tiry.cloudfront.net/cdn/sites/foundation.js"></script>
<script>
    timer();
    function timer() {

        setInterval(
            function () {

                var timers = $('.countdown');
                for (var i = 0; i < timers.length; i++) {
                    var el = timers[i];
                    var endTime = $(el).attr('end');
                    el.innerHTML = startTimer(new Date(endTime));
                }
            }
            , 1000);
    }

    function startTimer(countDownDate) {
        var now = new Date().getTime();
        var distance = countDownDate - now;
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        if (hours < 10) {
            hours = '0' + hours;
        }
        if (minutes < 10) {
            minutes = '0' + minutes;
        }
        if (seconds < 10) {
            seconds = '0' + seconds;
        }
        if (distance < 0) {
            return "EXPIRED";
        }
        if (days > 1) {
            return days + " dagen";
        }
        if (hours === 0 && minutes > 0) {
            return minutes + ":" + seconds;
        }
        if (minutes === 0 && seconds > 0) {
            return seconds;
        }
        return hours + ":" + minutes + ":" + seconds;

    }
</script>
<script>
    $('.categories-menu.menu.nested').each(function () {
        var filterAmount = $(this).find('li').length;
        if (filterAmount > 3) {
            $('li', this).eq(2).nextAll().hide().addClass('toggleable');
            $(this).append('<li class="more">Meer</li>');
        }
    });

    $('.categories-menu.menu.nested').on('click', '.more', function () {
        if ($(this).hasClass('less')) {
            $(this).text('Meer').removeClass('less');
        } else {
            $(this).text('Minder').addClass('less');
        }
        $(this).siblings('li.toggleable').slideToggle();
    });
    $(document).foundation();
    $(document).app();
</script>
</body>
</html>
