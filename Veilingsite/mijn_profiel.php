<?php
include_once "components/connect.php";
include_once 'components/meta.php';
?>
<body>
<?php include_once 'components/header.php'; ?>

<?PHP

// Check of er een afbeelding is gevonden of niet.
function c_file_exists($file){
    $file_headers = @get_headers($file);
    if(strpos($file_headers[0], '404 Not Found')) {
        return false;
    }
    return true;
}

Function prijs($Voorwerpnummer)
{
    global $dbh;

// Selects the top most Startprijs and Highest Bodbedrag based on the Voorwerpnummer.
    $queryPrice = "SELECT TOP 1 V.Startprijs, B.Bodbedrag
                       FROM Bod B FULL OUTER JOIN Voorwerp V ON B.Voorwerp = V.Voorwerpnummer
                       WHERE Voorwerpnummer = :Voorwerpnummer
                       ORDER BY B.Bodbedrag DESC, V.Startprijs DESC";

    $Prices = $dbh->prepare($queryPrice);
// $Prices->bindParam(":a", $b);
    $Prices->bindParam(":Voorwerpnummer", $Voorwerpnummer);
    $Prices->execute();
    while ($Price = $Prices->fetch()) {
        //Er is minimaal 1 keer geboden (daarvan is automatisch het hoogste bedrag al gepakt).
        if ($Price['Bodbedrag'] != null) {
            return $Price['Bodbedrag'];
        } else {
            return $Price['Startprijs'];
        }
    }
}

function get_from_voorwerp($header, $column, $filter, $order_by, $up_or_down, $tabel)
{
    global $dbh;
    global $sql_get_voorwerpen_data;

    $gebruikersnaam = $_SESSION["ingelogde_gebruiker"];
    if ($tabel == 'Voorwerp') {
        $sql_get_voorwerpen_query = "select top 8 * from Voorwerp where Verkoper = '$gebruikersnaam' AND $column = $filter order by $order_by $up_or_down";
    } elseif ($tabel == 'Bod') {
        $sql_get_voorwerpen_query = "select top 8 * from Voorwerp v RIGHT JOIN Bod b ON b.Voorwerp = v.Voorwerpnummer WHERE B.Gebruikersnaam = '" . $gebruikersnaam . "' AND $column = $filter order by $order_by $up_or_down";
    }
    $sql_get_voorwerpen = $dbh->prepare($sql_get_voorwerpen_query);
    $sql_get_voorwerpen->execute();
    $sql_get_voorwerpen_data = $sql_get_voorwerpen->fetchAll();
    //var_dump($sql_neem_voorwerpen_data);
    if ($sql_get_voorwerpen->rowCount() == 0) {
        return;
    } else {
        echo '    <div class="grid-x grid-padding-x home-veilingen-box">
                <div class="cell">
                    <h4>' . $header . '</h4>
                </div>';
    }
    $counter = 0;
    foreach ($sql_get_voorwerpen_data as $auction) {

        $title = (strlen($auction['Titel']) > 38) ? substr($auction['Titel'], 0, 35) . '...' : $auction['Titel'];

        echo '      
                <div class="cell small-6 medium-4 large-3">
                <div class="veiling-sluit-index">
                        <div class="resizeImage">';
                        if(c_file_exists('http://iproject5.icasites.nl/thumbnails/' . $auction['Thumbnail'])) {
                            echo '<img src="http://iproject5.icasites.nl/thumbnails/' . $auction['Thumbnail'] . '" alt="Auction Photo">';
                        } else {
                            echo '<img src="upload/' . $auction['Thumbnail'] . '" alt="Auction Photo">';
                        }
        echo '          </div>
                        <div class="card-body">
                            <div class="grid-x">
                                <div class="cell">
                                    <div class="grid-x">
                                        <div class="cell">
                                            <h5>' . $title . '</h5>
                                        </div>
                                        <div class="cell timer">
                                            <h5 class="countdown" end="' . $auction['EindMoment'] . '"></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="cell">
                                    <div class="grid-x FullCenter">
                                        <div class="cell large-6">
                                            <p class="noMargins noLineHeight">Prijs: €' . prijs($auction['Voorwerpnummer']) . '</p>
                                        </div>
                                        <div class="cell large-6">
                                            <div class="button-left noMargins">
                                                <a href="detailpagina.php?Voorwerpnummer=' . $auction['Voorwerpnummer'] . '"
                                                   class="button expanded noMargins">Bied nu!</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div></div>';
        $counter++;
    }
    echo '</div>';
}

echo '   <div class="grid-container">';
//Neemt de nog niet afgelopen veilingen van de verkoper uit de database
get_from_voorwerp('Mijn laatst toegevoegde veilingen', 'veilinggesloten', '0', 'BeginMoment', 'DESC', 'Voorwerp');

//Neemt de al afgelopen veilingen van de verkoper uit de database
get_from_voorwerp('Mijn afgelopen veilingen', 'veilinggesloten', '1', 'EindMoment', 'ASC', 'Voorwerp');

//Neemt de alle veilingen uit de database waar de gebruiker op heeft geboden
get_from_voorwerp('Mijn geboden veilingen', 'veilinggesloten', '0', 'EindMoment', 'ASC', 'Bod');

?>

<?php include "components/scripts.html"; ?>
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

