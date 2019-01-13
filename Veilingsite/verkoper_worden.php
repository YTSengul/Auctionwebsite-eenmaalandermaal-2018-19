<?php
    include_once "components/connect.php";
    include_once "components/meta.php";

    if (!isset($_SESSION['ingelogde_gebruiker'])) {
        header('location: index.php');
    }

    $step1 = false;

    if(isset($_POST['optionSelected'])){
        $step1 = true;
        echo "LALALALLALLA";
    }

    function form2(){
        echo '
            <form action="#" method="POST">
                <label>Controle optie</label>
                <input type="text" placeholder="Controle optie" name="forminput">
                <label>Bank</label>
                <input type="text" placeholder="Bank" name="forminput2">
                <label>Bankrekening</label>
                <input type="text" placeholder="Bankrekening" name="forminput3">
                <label>Creditcard</label>
                <input type="text" placeholder="Creditcard" name="forminput4">
                <input type="submit" value="Login" name="login" class="button expanded float-right">
            </form>
            ';
    }

    function form1(){
        echo '
            <form action="#" method="POST">
                <label>Controle optie</label>
                <select name="Controle optie">
                    <option value="Creditcard">Creditcard</option>
                    <option value="Post">Post</option>
                </select>
                <input type="submit" value="Next" name="optionSelected" class="button expanded float-right">
            </form>
            ';
    }
?>

<body>

    <?php include_once "components/header.php"; ?>

    <div class="grid-container">
        <div class="grid-x">
            <div class="cell large-5 float-center">
                <?php
                    if($step1){
                        form2();
                    }
                    else{
                        form1();
                    }
                ?>
            </div>
        </div>
    </div>

    <?php include "components/scripts.html"; ?>

</body>

</html>
