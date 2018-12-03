<?php
session_start();
include_once "components/connect.php";
?>

<!doctype html>
<html class="" lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EenmaalAndermaal</title>
    <link rel="stylesheet" href="foundation/css/foundation.css">
    <link rel="stylesheet" href="foundation/css/app.css">
</head>

<body>

<?php include_once 'components/header.php'; ?>

<div class="grid-container">

    <!-- Include the header into the page -->
    <!-- ________________________________ -->

    <!-- Text for first auction type.                              -->

    <!-- ____________________________                              -->

    <!-- Auctions within the first category                        -->

    <div class="grid-x grid-padding-x home-veilingen-box">
        <div class="small-12 medium-12 cell">
            <h4>Veilingen aan het sluiten</h4>
        </div>

        <?PHP

        $sql_neem_artikelen_query = 'select * from test_startpagina';
        $sql_neem_artikelen = $dbh->prepare($sql_neem_artikelen_query);
        $sql_neem_artikelen->execute();
        $sql_artikelen_uit_database = $sql_neem_artikelen->fetchAll(PDO::FETCH_NUM);

        for ($x = 0; $x < 3; $x++) {

            $nummer = $sql_artikelen_uit_database[$x][0];
            $titel = $sql_artikelen_uit_database[$x][1];
            $aftellen_lang = $sql_artikelen_uit_database[$x][2];
            $aftellen = substr($aftellen_lang, 0, -8);
            $afbeelding = $sql_artikelen_uit_database[$x][3];
            $hoogste_bod = $sql_artikelen_uit_database[$x][4];


            echo '<div class="small-6 medium-4 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/' . $afbeelding . '">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>' . $titel . '</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">' . $aftellen . '</h5>
              </div>
            </div>
            <p class="text-center" >Hoogste bod: €' . $hoogste_bod . ',-</p>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>
        ';
        }

        $nummer = $sql_artikelen_uit_database[4][0];
        $titel = $sql_artikelen_uit_database[4][1];
        $aftellen_lang = $sql_artikelen_uit_database[4][2];
        $aftellen = substr($aftellen_lang, 0, -8);
        $afbeelding = $sql_artikelen_uit_database[4][3];
        $hoogste_bod = $sql_artikelen_uit_database[4][4];

        echo '<!-- This one gets hidden in larger devices. Only appears on smaller devices like phones. -->
        <div class="small-6 medium-4 cell hide-for-medium hide-for-large">
            <img class="thumbnail" src="img/' . $afbeelding . '">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>' . $titel . '</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">' . $aftellen . '</h5>
              </div>
            </div>
            <p class="text-center" >Hoogste bod: €' . $hoogste_bod . ',-</p>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        <!-- End of auctions first category.                                       -->'

        ?>
    </div>
    <!-- Auctions second category    -->

    <div class="grid-x grid-padding-x home-veilingen-box">
        <div class="medium-12 large-12 cell">
            <h4>Ook interresant voor u</h4>
        </div>

        <?PHP

        $sql_neem_artikelen_query = 'select * from test_startpagina';
        $sql_neem_artikelen = $dbh->prepare($sql_neem_artikelen_query);
        $sql_neem_artikelen->execute();
        $sql_artikelen_uit_database = $sql_neem_artikelen->fetchAll(PDO::FETCH_NUM);

        for ($x = 0; $x < 8; $x++) {

            $nummer = $sql_artikelen_uit_database[$x][0];
            $titel = $sql_artikelen_uit_database[$x][1];
            $aftellen_lang = $sql_artikelen_uit_database[$x][2];
            $aftellen = substr($aftellen_lang, 0, -8);
            $afbeelding = $sql_artikelen_uit_database[$x][3];
            $hoogste_bod = $sql_artikelen_uit_database[$x][4];

            echo '<div class="small-6 medium-3 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/' . $afbeelding . '">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>' . $titel . '</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">' . $aftellen . '</h5>
              </div>
            </div>
            <p class="text-center" >Hoogste bod: €' . $hoogste_bod . ',-</p>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>';
        }

        ?>
        <!-- End auctions second category                            -->

    </div>
</div>

<?php include "components/scripts.html"; ?>

</body>
</html>
