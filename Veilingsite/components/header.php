<?php
include_once "connect.php";
?>

<!doctype html>
<html class="" lang="nl" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EenmaalAndermaal</title>
    <link rel="stylesheet" href="../css/foundation.css">
    <link rel="stylesheet" href="../css/app.css">
</head>

<body>
<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-12 cell">
            <div class="header">
                <h1>EenmaalAndermaal</h1>
                <ul class="menu align-right">
                    <li><a href="#">Inloggen</a></li>
                    <li><p>of</p></li>
                    <li><a href="#">Registreren</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="top-nav">
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
    <script>
        $(document).foundation();
    </script>