<?php
session_start();
$beschrijving = $_SESSION['beschrijving'];
echo $beschrijving;