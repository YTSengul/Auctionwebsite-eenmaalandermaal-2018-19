<?php

include_once "components/connect.php"; ?>

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

<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-6 large-6 cell">
            <a href="?voeg_hoofdrubriek_toe=true" class="button expanded">Voeg hoofdrubriek toe</a>
        </div>
        <div class="medium-6 large-6 cell">
            <a href="?voeg_subrubriek_toe=true" class="button expanded">Voeg subrubriek toe</a>
        </div>
        <div class="medium-6 large-6 cell">
            <a href="?verwijder_hoofdrubriek=true" class="button expanded">Verwijder hoofdrubriek</a>
        </div>
        <div class="medium-6 large-6 cell">
            <a href="?verwijder_subrubriek=true" class="button expanded">Verwijder subrubriek</a>
        </div>
    </div>
</div>

<?PHP

/* Neemt alle subrubrieken om in de select te laten zien. */
$sql_naam_subrubrieken_query = "SELECT Rubrieknaam FROM Rubriek ORDER BY Rubrieknaam ASC";
$sql_subrubrieken = $dbh->prepare($sql_naam_subrubrieken_query);
$sql_subrubrieken->execute();
$alle_subrubrieken = $sql_subrubrieken->fetchAll(PDO::FETCH_NUM);

if (isset($_GET['voeg_hoofdrubriek_toe'])) {
    echo '<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <form action="#" method="POST">
                <input type=text name="hoofdrubriek_invoer_toevoegen" placeholder="Vul hoofdrubriek in">
                <input type="submit" value="Voeg toe" name="voeg_hoofdrubriek_toe" class="button expanded">
            </form>
        </div>
    </div>
</div>';
} else if (isset($_GET['voeg_subrubriek_toe'])) {
    echo '<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-12 large-12 cell">
            <form action="#" method="POST">
                    <select name="subrubriek" required>
            ';
    foreach ($alle_subrubrieken as $subrubrieken) {
        foreach ($subrubrieken as $subrubriek) {
            echo '<option value="' . $subrubriek . '">' . $subrubriek . '</option>';
        }
    }
    echo '    </select>
                <input type=text name="subrubriek_invoer_toevoegen" placeholder="Vul subrubriek in">
                <input type="submit" value="Voeg toe" name="voeg_subrubriek_toe" class="button expanded">
            </form>
        </div>
    </div>
</div>';
}

?>
</body>
</html>

<?PHP
/* Neemt de hoogste getal die er is van de rubrieken voor de database */
$sql_naam_rubrieken_query = "SELECT Rubrieknaam FROM Rubriek WHERE Rubriek = -1";
$sql_rubrieken = $dbh->prepare($sql_naam_rubrieken_query);
$sql_rubrieken->execute();
$alle_rubrieken = $sql_rubrieken->fetchAll(PDO::FETCH_NUM);

if (isset($_POST['voeg_hoofdrubriek_toe']) && isset($_GET['voeg_hoofdrubriek_toe'])) {

    echo "<br>Succesvol toegevoegd aan database</br>";

    $sql_neem_hoogste_rubrieknummer = "select MAX(Rubrieknummer) from Rubriek";
    $sql_hoogste_hoofdrubrieknummer = $dbh->prepare($sql_neem_hoogste_rubrieknummer);
    $sql_hoogste_hoofdrubrieknummer->execute();
    $hoogste_hoofdrubrieknummer_data = $sql_hoogste_hoofdrubrieknummer->fetchAll(PDO::FETCH_NUM);
    $hoogste_hoofdrubrieknummer = $hoogste_hoofdrubrieknummer_data[0][0];
    $nieuw_hoogste_hoofdrubrieknummer = $hoogste_hoofdrubrieknummer += 1;

    /* De nieuwe rubriek wordt met een query in de database opgeslagen */
    $sql_voeg_nieuwe_hoofdrubriek_toe = "insert into rubriek ([Rubrieknummer], [Rubrieknaam], [Rubriek], [Volgnr]) values (:nieuw_hoogste_rubrieknummer, :nieuw_rubrieknaam, :nieuw_rubriek, :nieuw_volgnummer)";
    $voeg_nieuwe_hoofdrubriek_toe = $dbh->prepare($sql_voeg_nieuwe_hoofdrubriek_toe);

    $nieuw_rubrieknaam = $_POST['hoofdrubriek_invoer_toevoegen'];
    $nieuw_rubriek = -1;
    $nieuw_volgnummer = 0;

    if ($voeg_nieuwe_hoofdrubriek_toe) {
        $voeg_nieuwe_hoofdrubriek_toe->bindParam(":nieuw_hoogste_rubrieknummer", $nieuw_hoogste_hoofdrubrieknummer, PDO::PARAM_STR);
        $voeg_nieuwe_hoofdrubriek_toe->bindParam(":nieuw_rubrieknaam", $nieuw_rubrieknaam, PDO::PARAM_STR);
        $voeg_nieuwe_hoofdrubriek_toe->bindParam(":nieuw_rubriek", $nieuw_rubriek, PDO::PARAM_STR);
        $voeg_nieuwe_hoofdrubriek_toe->bindParam(":nieuw_volgnummer", $nieuw_volgnummer, PDO::PARAM_STR);

        $voeg_nieuwe_hoofdrubriek_toe->execute();

        try {
            if ($voeg_nieuwe_hoofdrubriek_toe) {
                echo "<br>Succesvol toegevoegd aan database</br>";
            } else {
                echo "<br>Fout met toevoegen aan database</br>";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }
} else if (isset($_POST['voeg_subrubriek_toe']) && isset($_GET['voeg_subrubriek_toe'])) {

    $sql_neem_hoogste_rubrieknummer = "select MAX(Rubrieknummer) from Rubriek";
    $sql_hoogste_hoofdrubrieknummer = $dbh->prepare($sql_neem_hoogste_rubrieknummer);
    $sql_hoogste_hoofdrubrieknummer->execute();
    $hoogste_hoofdrubrieknummer_data = $sql_hoogste_hoofdrubrieknummer->fetchAll(PDO::FETCH_NUM);
    $hoogste_hoofdrubrieknummer = $hoogste_hoofdrubrieknummer_data[0][0];
    $nieuw_hoogste_hoofdrubrieknummer = $hoogste_hoofdrubrieknummer += 1;

    /* Rubriek van de subrubriek vinden */
    $gekozen_hoofdrubriek_van_subrubriek = $_POST['subrubriek'];
    $sql_voegtoe_naam_subrubrieken_query = "SELECT * FROM Rubriek WHERE Rubrieknaam = '$gekozen_hoofdrubriek_van_subrubriek'";
    $sql_voegtoe_subrubrieken = $dbh->prepare($sql_voegtoe_naam_subrubrieken_query);
    $sql_voegtoe_subrubrieken->execute();
    $alle_voegtoe_subrubrieken = $sql_voegtoe_subrubrieken->fetchAll(PDO::FETCH_NUM);

    /* de rubriek van de subrubriek opslaan in een variabele */
    $hoofdrubriek_van_subrubriek = $alle_voegtoe_subrubrieken[0][0];

    /* De nieuwe subrubriek wordt met een query in de database opgeslagen */
    $sql_voeg_nieuwe_subrubriek_toe = "insert into rubriek ([Rubrieknummer], [Rubrieknaam], [Rubriek], [Volgnr]) values (:nieuw_hoogste_rubrieknummer, :nieuw_rubrieknaam, :nieuw_rubriek, :nieuw_volgnummer)";
    $voeg_nieuwe_subrubriek_toe = $dbh->prepare($sql_voeg_nieuwe_subrubriek_toe);

    $nieuw_subrubrieknaam = $_POST['subrubriek_invoer_toevoegen'];
    $nieuw_rubriek = $hoofdrubriek_van_subrubriek;
    $nieuw_volgnummer = 0;

    if ($voeg_nieuwe_subrubriek_toe) {
        $voeg_nieuwe_subrubriek_toe->bindParam(":nieuw_hoogste_rubrieknummer", $nieuw_hoogste_hoofdrubrieknummer, PDO::PARAM_STR);
        $voeg_nieuwe_subrubriek_toe->bindParam(":nieuw_rubrieknaam", $nieuw_subrubrieknaam, PDO::PARAM_STR);
        $voeg_nieuwe_subrubriek_toe->bindParam(":nieuw_rubriek", $nieuw_rubriek, PDO::PARAM_STR);
        $voeg_nieuwe_subrubriek_toe->bindParam(":nieuw_volgnummer", $nieuw_volgnummer, PDO::PARAM_STR);

        $voeg_nieuwe_subrubriek_toe->execute();

        try {
            if ($voeg_nieuwe_subrubriek_toe) {
                echo "<br>Succesvol toegevoegd aan database</br>";
            } else {
                echo "<br>Fout met toevoegen aan database</br>";
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }

}
?>

