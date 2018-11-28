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
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
  </head>

  <body>

  <?php include_once 'components/header.php'; ?>

    <div class="grid-container">

      <!-- Include the header into the page -->
      <!-- ________________________________ -->

      <!-- Text for first auction type.                              -->
      <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
          <p class="homepage-titel">Veilingen aan het sluiten</p>
        </div>
      </div>
      <!-- ____________________________                              -->

      <!-- Auctions within the first category                        -->

      <div class="grid-x grid-padding-x">

        <div class="small-6 medium-4 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/img_no_thumb.jpg">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>Titel</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">00:00</h5>
              </div>
            </div>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>

        <div class="small-6 medium-4 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/img_no_thumb.jpg">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>Titel</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">00:00</h5>
              </div>
            </div>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>

        <div class="small-6 medium-4 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/img_no_thumb.jpg">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>Titel</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">00:00</h5>
              </div>
            </div>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>

        <!-- This one gets hidden in larger devices. Only appears on smaller devices like phones. -->
        <div class="small-6 medium-4 cell hide-for-medium hide-for-large">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/img_no_thumb.jpg">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>Titel</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">00:00</h5>
              </div>
            </div>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>
        <!-- End of auctions first category.                                       -->

        <!-- Auctions second category                                -->
        <div class="medium-12 large-12 cell">
          <p class="homepage-titel">Ook interresant voor u</p>
        </div>

        <div class="small-6 medium-3 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/img_no_thumb.jpg">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>Titel</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">00:00</h5>
              </div>
            </div>
            <p class="text-center" >Hoogste bod: €0,-</p>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>

        <div class="small-6 medium-3 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/img_no_thumb.jpg">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>Titel</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">00:00</h5>
              </div>
            </div>
            <p class="text-center" >Hoogste bod: €0,-</p>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>

        <div class="small-6 medium-3 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/img_no_thumb.jpg">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>Titel</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">00:00</h5>
              </div>
            </div>
            <p class="text-center" >Hoogste bod: €0,-</p>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>

        <div class="small-6 medium-3 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/img_no_thumb.jpg">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>Titel</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">00:00</h5>
              </div>
            </div>
            <p class="text-center" >Hoogste bod: €0,-</p>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>

        <div class="small-6 medium-3 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/img_no_thumb.jpg">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>Titel</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">00:00</h5>
              </div>
            </div>
            <p class="text-center" >Hoogste bod: €0,-</p>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>

        <div class="small-6 medium-3 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/img_no_thumb.jpg">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>Titel</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">00:00</h5>
              </div>
            </div>
            <p class="text-center" >Hoogste bod: €0,-</p>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>

        <div class="small-6 medium-3 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/img_no_thumb.jpg">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>Titel</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">00:00</h5>
              </div>
            </div>
            <p class="text-center" >Hoogste bod: €0,-</p>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>

        <div class="small-6 medium-3 cell">
          <div class="veiling-sluit-index">
            <img class="thumbnail" src="img/img_no_thumb.jpg">

            <div class="grid-x grid-padding-x">
              <div class="small-6 medium-6 cell">
                <h5>Titel</h5>
              </div>
              <div class="small-6 medium-6 cell timer">
                <h5 class="float-right">00:00</h5>
              </div>
            </div>
            <p class="text-center" >Hoogste bod: €0,-</p>
            <a href="#" class="button expanded">Bied nu!</a>
          </div>
        </div>
        <!-- End auctions second category                            -->

      </div>
    </div>

    <?php include "components/scripts.html"; ?>

  </body>
</html>
