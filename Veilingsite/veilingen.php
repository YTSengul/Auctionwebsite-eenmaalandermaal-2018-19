<?php
include_once "components/connect.php";
include_once "components/meta.php";
?>

<?PHP
$sql_veilingen_query = "SELECT *
FROM (
     SELECT *, ROW_NUMBER() OVER (ORDER BY EindMoment) AS RowNum
     FROM Voorwerp
     ) AS MyDerivedTable
WHERE MyDerivedTable.RowNum BETWEEN 0 AND 10 AND VeilingGesloten = 0";
$sql_veilingen_data = $dbh->prepare($sql_veilingen_query);
$sql_veilingen_data->execute();
$veilingen = $sql_veilingen_data->fetchAll(PDO::FETCH_NUM);
$aantalveilingen = $veilingen;

$tijd_uit_server = time(); // or your date as well
$your_date = strtotime(date($veilingen[0][10]));
$datediff = $your_date - $tijd_uit_server;

$dagen = round($datediff/(60*60*24));
$uren = round($datediff/(60*60));
$minuten = round($datediff/(60));
$seconden = round($datediff);

echo $dagen.'<br>';
echo $uren.'<br>';
echo $minuten.'<br>';
echo $seconden.'<br>';

?>

<body>
<?php include_once "components/header.php"; ?>
<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="hide-for-small-only medium-12 large-12 float-center cell">
            <!--- Breadcrumbs -->
            <nav aria-label="You are here:" role="navigation" class="veilingen-breadcrumbs">
                <ul class="breadcrumbs">
                    <li><a href="#">Cat 1</a></li>
                    <li><a href="#">Cat 2</a></li>
                    <li><a href="#">Cat 3</a></li>
                    <li>
                        <span class="show-for-sr">Current: </span> Huidige cat.
                    </li>
                </ul>
            </nav>

            <select class="float-right veilingen-filter-hoofd ">
                <option>Optie 1</option>
                <option>Optie 2</option>
                <option>Optie 3</option>
                <option>Optie 4</option>
                <option>Optie 5</option>
            </select>
        </div>
        <div class="hide-for-small-only medium-3 large-3 float-center cell">
            <div class="product-filters">
                <ul class="mobile-product-filters vertical menu show-for-small-only" data-accordion-menu>
                    <li>
                        <a href="#"><h2>Products</h2></a>
                        <ul class="vertical menu" data-accordion-menu>
                            <li class="product-filters-tab">
                                <a href="#">Category</a>
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
                            <li class="product-filters-tab">
                                <a href="#">Size</a>
                                <ul class="categories-menu menu vertical nested is-active">
                                    <a href="#" class="clear-all" id="size-clear-all">Clear All</a>
                                    <li><input id="size-checkbox1" type="checkbox"><label
                                                for="size-checkbox1">Small</label></li>
                                    <li><input id="size-checkbox2" type="checkbox"><label
                                                for="size-checkbox2">Medium</label></li>
                                    <li><input id="size-checkbox3" type="checkbox"><label
                                                for="size-checkbox3">Large</label></li>
                                    <li><input id="size-checkbox3" type="checkbox"><label
                                                for="size-checkbox3">X-Large</label></li>
                                    <li><input id="size-checkbox3" type="checkbox"><label
                                                for="size-checkbox3">XX-Large</label></li>
                                </ul>
                            </li>
                            <li class="product-filters-tab">
                                <a href="#">Color</a>
                                <ul class="categories-menu menu vertical nested">
                                    <a href="#" class="clear-all" id="color-clear-all">Clear All</a>
                                    <li><input id="color-checkbox1" type="checkbox"><label for="color-checkbox1">All
                                            Color</label></li>
                                    <li><input id="color-checkbox2" type="checkbox"><label
                                                for="color-checkbox2">Black</label></li>
                                    <li><input id="color-checkbox3" type="checkbox"><label
                                                for="color-checkbox3">White</label></li>
                                    <li><input id="color-checkbox4" type="checkbox"><label
                                                for="color-checkbox4">Grey</label></li>
                                    <li><input id="color-checkbox5" type="checkbox"><label
                                                for="color-checkbox5">Red</label></li>
                                    <li><input id="color-checkbox6" type="checkbox"><label
                                                for="color-checkbox6">Blue</label></li>
                                    <li><input id="color-checkbox7" type="checkbox"><label
                                                for="color-checkbox7">Green</label></li>
                                    <li><input id="color-checkbox8" type="checkbox"><label
                                                for="color-checkbox8">Purple</label></li>
                                    <li><input id="color-checkbox8" type="checkbox"><label for="color-checkbox8">Multi-color</label>
                                    </li>
                                </ul>
                            </li>
                            <li class="product-filters-tab">
                                <a href="#">Price</a>
                                <ul class="categories-menu menu vertical nested is-active">
                                    <a href="#" class="clear-all" id="price-clear-all">Clear All</a>
                                    <li><input id="price-checkbox1" type="checkbox"><label for="price-checkbox1">Under
                                            $25</label></li>
                                    <li><input id="price-checkbox2" type="checkbox"><label
                                                for="price-checkbox2">$25–$50</label></li>
                                    <li><input id="price-checkbox3" type="checkbox"><label for="price-checkbox3">$50–$250</label>
                                    </li>
                                    <li><input id="price-checkbox4" type="checkbox"><label for="price-checkbox4">$250–$600</label>
                                    </li>
                                    <li><input id="price-checkbox4" type="checkbox"><label for="price-checkbox4">$600–$1,000</label>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>

                <h1 class="product-filters-header hide-for-small-only">Products</h1>
                <ul class="vertical menu hide-for-small-only" data-accordion-menu>
                    <li class="product-filters-tab">
                        <a href="#">Category</a>
                        <ul class="categories-menu menu vertical nested is-active">
                            <a href="#" class="clear-all" id="category-clear-all">Clear All</a>
                            <li><input id="category-checkbox1" type="checkbox"><label for="category-checkbox1">Category
                                    1</label></li>
                            <li><input id="category-checkbox2" type="checkbox"><label for="category-checkbox2">Category
                                    2</label></li>
                            <li><input id="category-checkbox3" type="checkbox"><label for="category-checkbox3">Category
                                    3</label></li>
                            <li><input id="category-checkbox4" type="checkbox"><label for="category-checkbox4">Category
                                    4</label></li>
                            <li><input id="category-checkbox5" type="checkbox"><label for="category-checkbox5">Category
                                    5</label></li>
                            <li><input id="category-checkbox6" type="checkbox"><label for="category-checkbox6">Category
                                    6</label></li>
                            <li><input id="category-checkbox7" type="checkbox"><label for="category-checkbox7">Category
                                    7</label></li>
                            <li><input id="category-checkbox8" type="checkbox"><label for="category-checkbox8">Category
                                    8</label></li>
                            <li><input id="category-checkbox9" type="checkbox"><label for="category-checkbox9">Category
                                    9</label></li>
                            <li><input id="category-checkbox10" type="checkbox"><label for="category-checkbox10">Category
                                    10</label></li>
                            <li><input id="category-checkbox11" type="checkbox"><label for="category-checkbox11">Category
                                    11</label></li>
                            <li><input id="category-checkbox12" type="checkbox"><label for="category-checkbox12">Category
                                    12</label></li>
                            <li><input id="category-checkbox13" type="checkbox"><label for="category-checkbox13">Category
                                    13</label></li>
                            <li><input id="category-checkbox14" type="checkbox"><label for="category-checkbox14">Category
                                    14</label></li>
                            <li><input id="category-checkbox15" type="checkbox"><label for="category-checkbox15">Category
                                    15</label></li>
                        </ul>
                    </li>
                    <li class="product-filters-tab">
                        <a href="#">Size</a>
                        <ul class="categories-menu menu vertical nested is-active">
                            <a href="#" class="clear-all" id="size-clear-all">Clear All</a>
                            <li><input id="size-checkbox1" type="checkbox"><label for="size-checkbox1">Small</label>
                            </li>
                            <li><input id="size-checkbox2" type="checkbox"><label for="size-checkbox2">Medium</label>
                            </li>
                            <li><input id="size-checkbox3" type="checkbox"><label for="size-checkbox3">Large</label>
                            </li>
                            <li><input id="size-checkbox3" type="checkbox"><label for="size-checkbox3">X-Large</label>
                            </li>
                            <li><input id="size-checkbox3" type="checkbox"><label for="size-checkbox3">XX-Large</label>
                            </li>
                        </ul>
                    </li>
                    <li class="product-filters-tab">
                        <a href="#">Color</a>
                        <ul class="categories-menu menu vertical nested is-active ">
                            <a href="#" class="clear-all" id="color-clear-all">Clear All</a>
                            <li><input id="color-checkbox1" type="checkbox"><label for="color-checkbox1">All
                                    Color</label></li>
                            <li><input id="color-checkbox2" type="checkbox"><label for="color-checkbox2">Black</label>
                            </li>
                            <li><input id="color-checkbox3" type="checkbox"><label for="color-checkbox3">White</label>
                            </li>
                            <li><input id="color-checkbox4" type="checkbox"><label for="color-checkbox4">Grey</label>
                            </li>
                            <li><input id="color-checkbox5" type="checkbox"><label for="color-checkbox5">Red</label>
                            </li>
                            <li><input id="color-checkbox6" type="checkbox"><label for="color-checkbox6">Blue</label>
                            </li>
                            <li><input id="color-checkbox7" type="checkbox"><label for="color-checkbox7">Green</label>
                            </li>
                            <li><input id="color-checkbox8" type="checkbox"><label for="color-checkbox8">Purple</label>
                            </li>
                            <li><input id="color-checkbox8" type="checkbox"><label
                                        for="color-checkbox8">Multi-color</label></li>
                        </ul>
                    </li>
                    <li class="product-filters-tab">
                        <a href="#">Price</a>
                        <ul class="categories-menu menu vertical nested is-active ">
                            <a href="#" class="clear-all" id="price-clear-all">Clear All</a>
                            <li><input id="price-checkbox1" type="checkbox"><label for="price-checkbox1">Under
                                    $25</label></li>
                            <li><input id="price-checkbox2" type="checkbox"><label for="price-checkbox2">$25–$50</label>
                            </li>
                            <li><input id="price-checkbox3" type="checkbox"><label
                                        for="price-checkbox3">$50–$250</label></li>
                            <li><input id="price-checkbox4" type="checkbox"><label
                                        for="price-checkbox4">$250–$600</label></li>
                            <li><input id="price-checkbox4" type="checkbox"><label
                                        for="price-checkbox4">$600–$1,000</label></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <?PHP
        foreach ($veilingen as $veiling) {
            echo "<div class='small-12 medium-9 large-9 float-center cell'>
            <div class='media-object veilingen-veiling-box '>
                <div class='media-object-section'>
                    <img class='thumbnail veilingen-veiling-image' src='http://iproject4.icasites.nl/pics/dt_1_" . substr($veiling[15], 3) . "'>
                </div>
                <div class='media-object-section veilingen-veiling-info'>
                    <h5 class='veilingen-veiling-titel float-left'>$veiling[1]</h5>
                    <h5 class='veilingen-veiling-timer float-right' id='demo' ></h5>
                    <p class='hide-for-small-only veilingen-veiling-omschrijving'>" . substr(strip_tags($veiling[2]), 0, 140) . "</p>
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

        <ul class="pagination text-center" role="navigation" aria-label="Pagination" data-page="6" data-total="16">
            <li class="pagination-previous disabled">Previous <span class="show-for-sr">page</span></li>
            <li class="current"><span class="show-for-sr">You're on page</span> 1</li>
            <li><a href="#" aria-label="Page 2">2</a></li>
            <li><a href="#" aria-label="Page 3">3</a></li>
            <li><a href="#" aria-label="Page 4">4</a></li>
            <li class="ellipsis" aria-hidden="true"></li>
            <li><a href="#" aria-label="Page 12">12</a></li>
            <li><a href="#" aria-label="Page 13">13</a></li>
            <li class="pagination-next"><a href="#" aria-label="Next page">Next <span
                            class="show-for-sr">page</span></a></li>
        </ul>
    </div>
</div>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://dhbhdrzi4tiry.cloudfront.net/cdn/sites/foundation.js"></script>
<script>
    $('.categories-menu.menu.nested').each(function () {
        var filterAmount = $(this).find('li').length;
        if (filterAmount > 5) {
            $('li', this).eq(4).nextAll().hide().addClass('toggleable');
            $(this).append('<li class="more">More</li>');
        }
    });

    $('.categories-menu.menu.nested').on('click', '.more', function () {
        if ($(this).hasClass('less')) {
            $(this).text('More').removeClass('less');
        } else {
            $(this).text('Less').addClass('less');
        }
        $(this).siblings('li.toggleable').slideToggle();
    });
    $(document).foundation();
    $(document).app();
</script>
<script>
    // Set the date we're counting down to
    var countDownDate = new Date("<?PHP echo date($veiling[10]); ?>").getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get todays date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Output the result in an element with id="demo"
        document.getElementById("demo").innerHTML = days + "d " + hours + "h "
            + minutes + "m " + seconds + "s ";

        // If the count down is over, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "EXPIRED";
        }
    }, 1000);
</script>
</body>
</html>