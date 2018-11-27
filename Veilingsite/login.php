<?php
include_once "components/connect.php";
?>

<!doctype html>
<html class="" lang="nl" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EenmaalAndermaal</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
  </head>

  <body>
    <div class="grid-container">

      <!-- Include the header into the page -->
      <?php include_once 'components/header.php'; ?>
      <!-- ________________________________ -->

      <div class='grid-container grid-x'>

        <div class="cell large-4">
          <h5>Inloggen</h5>
          <form>
            <label>Gebruikersnaam </label>
            <input type='text' placeholder='Gebruikersnaam'>
            <label>Wachtwoord</label>
            <input type='password' placeholder='Wachtwoord'>
          </form>
        </div>

        <form class='cell large-4'>
          <h5>Registreren</h5>
          <label>Gebruikersnaam</label>
          <input type="text" placeholder="Gebruikersnaam">
          <label>Wachtwoord</label>
          <input type="password" placeholder="Wachtwoord">
          <label>Herhaal Wachtwoord</label>
          <input type="password" placeholder="Herhaal wachtwoord">
        </form>
      </div>

    <?php include "components/scripts.html"; ?>
    </div>
  </body>
</html>