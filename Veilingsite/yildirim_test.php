<?php

session_start();

include_once "components/connect.php";

// De rubrieken in een array stoppen
$alle_rubrieken_query = "SELECT * FROM Rubriek WHERE Rubriek = -1";
$sql_alle_rubrieken = $dbh->prepare($alle_rubrieken_query);
$sql_alle_rubrieken->execute();
$alle_hoofdrubrieken_data = $sql_alle_rubrieken->fetchAll(PDO::FETCH_NUM);
include_once "components/meta.php"
?>

<body>
<?php include_once "components/header.php" ?>

<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="medium-3 large-3 cell">
            <form action="#" method="POST">
                <select name="zoek_0">
                    <?PHP
                    foreach ($alle_hoofdrubrieken_data as $hoofdrubrieken) {
                        echo '<option value="' . $hoofdrubrieken[0] . '">' . $hoofdrubrieken[1] . '</option>';
                    }
                    ?>
                </select>
                <input type="submit" value="zoek" name="rubriek_zoek_0" class="button expanded">
            </form>
        </div>

        <?php

        if (!empty($_SESSION['rubriek0'])) {
            $zoek_0 = $_SESSION['rubriek0'];
        }

        if (isset($_POST['zoek_0']) || isset($_SESSION['rubriek0'])) {

            if (empty($_SESSION['rubriek0'])) {
                $_SESSION['rubriek0'] = $_POST['zoek_0'];
            }

            if (empty($zoek_0)) {
                $zoek_0 = $_POST['zoek_0'];
            }
            $zoek_1_rubrieken_query = "SELECT Rubrieknummer FROM Rubriek WHERE Rubrieknummer = '$zoek_0'";
            $sql_zoek_1_rubrieken = $dbh->prepare($zoek_1_rubrieken_query);

            $sql_zoek_1_rubrieken->execute();
            $zoek_1_rubrieken_data = $sql_zoek_1_rubrieken->fetchAll(PDO::FETCH_NUM);
            $zoek_1_nummer = $zoek_1_rubrieken_data[0][0];

            $zoek_1_rubrieken_query = "SELECT * FROM Rubriek WHERE Rubriek = '" . addslashes($zoek_1_nummer) . "'";
            $sql_zoek_1_rubrieken = $dbh->prepare($zoek_1_rubrieken_query);
            $sql_zoek_1_rubrieken->execute();
            $zoek_1_rubrieken_data = $sql_zoek_1_rubrieken->fetchAll(PDO::FETCH_NUM);

        }

        ?>

        <div class="medium-3 large-3 cell">
            <form action="#" method="POST">
                <select name="zoek_1">
                    <?PHP
                    if (!empty($_SESSION['rubriek1'])) {
                        $zoek_1 = $_SESSION['rubriek1'];
                    }
                    echo $zoek_1;
                    $zoek_0 = $_POST['zoek_0'];
                    foreach ($zoek_1_rubrieken_data as $zoek_1_rubrieken) {
                        if ($zoek_1_rubrieken[1] == $zoek_1) {
                            echo '<option selected value="' . $zoek_1_rubrieken[0] . '">' . $zoek_1_rubrieken[1] . '</option>';
                        } else {
                            echo '<option value="' . $zoek_1_rubrieken[0] . '">' . $zoek_1_rubrieken[1] . '</option>';
                        }
                    }
                    ?>
                </select>
                <input type="submit" value="zoek" name="rubriek_zoek_1" class="button expanded">
            </form>
        </div>

        <?php

        if (!empty($_SESSION['rubriek1'])) {
            $zoek_1 = $_SESSION['rubriek1'];
        }

        if (isset($_POST['zoek_1'])) {

            if (empty($_SESSION['rubriek1'])) {
                $_SESSION['rubriek1'] = '';
            }

            if (empty($zoek_1)) {
                $zoek_1 = $_POST['zoek_1'];
            }

            $zoek_1 = $_POST['zoek_1'];
            $zoek_2_rubrieken_query = "SELECT Rubrieknummer FROM Rubriek WHERE Rubrieknummer = '$zoek_1'";
            $sql_zoek_2_rubrieken = $dbh->prepare($zoek_2_rubrieken_query);
            $sql_zoek_2_rubrieken->execute();
            $zoek_2_rubrieken_data_nummer = $sql_zoek_2_rubrieken->fetchAll(PDO::FETCH_NUM);
            $zoek_2_nummer = $zoek_2_rubrieken_data_nummer[0][0];

            $zoek_2_rubrieken_query = "SELECT * FROM Rubriek WHERE Rubriek = '$zoek_2_nummer'";

            $sql_zoek_2_rubrieken = $dbh->prepare($zoek_2_rubrieken_query);
            $sql_zoek_2_rubrieken->execute();
            $zoek_2_rubrieken_data = $sql_zoek_2_rubrieken->fetchAll(PDO::FETCH_NUM);

            if (sizeof($zoek_2_rubrieken_data) > 0) {

                echo '<div class="medium-3 large-3 cell">
            <form action="#" method="POST">
                <select name="zoek_2">';

                foreach ($zoek_2_rubrieken_data as $zoek_2_rubrieken) {
                    if ($zoek_2_rubrieken[1] == $zoek_1) {
                        echo '<option selected value="' . $zoek_2_rubrieken[1] . '">' . $zoek_2_rubrieken[1] . '</option>';
                    } else {
                        echo '<option value="' . $zoek_2_rubrieken[1] . '">' . $zoek_2_rubrieken[1] . '</option>';
                    }
                }

                echo '</select>
                <input type="submit" value="zoek" name="rubriek_zoek_2" class="button expanded">
            </form>
        </div>';
            }
        }

        ?>

    </div>

</div>
</body>
</html>