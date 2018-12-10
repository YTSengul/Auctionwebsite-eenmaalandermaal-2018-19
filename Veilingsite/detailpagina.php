<?php
include_once "components/connect.php";

include_once "components/meta.php"; ?>
<body>
<?php include_once "components/header.php"; ?>
<div class="grid-container grid-x detailpagina">
    <div>
        <h2>Hier komt de titel van de veiling</h2>
    </div>
    <div class="cell large-7 productdetails">
        <div>
            <img class='detailfoto' src="img/gouden_schoen.jpg" alt="Foto van een product">
        </div>
        <div class="detailpagina-test">
            <img class='detailsubfoto' src="img/gouden_schoen.jpg" alt="Subfoto van een product">
            <img class='detailsubfoto' src="img/gouden_schoen.jpg" alt="Subfoto van een product">
            <img class='detailsubfoto' src="img/gouden_schoen.jpg" alt="Subfoto van een product">
            <img class='detailsubfoto' src="img/gouden_schoen.jpg" alt="Subfoto van een product">
            <img class='detailsubfoto' src="img/gouden_schoen.jpg" alt="Subfoto van een product">
        </div>
    </div>
    <div class="cell large-5 detail-biedingen">
        <div class="horizontaal">
            <h3>Doe een bod</h3>
            <h3>00:00</h3>
        </div>
        <hr>

        <div>
            <p>Hier kunt u bieden. Lorem ipsum bla die bla test tekst.</p>
        </div>
        <div>
            <form class="horizontaal">
                <input type="number" placeholder="Vul bedrag in...">
                <input class="button" type="submit" value="Bieden">
            </form>
        </div>
        <div>
            <h4>&euro; 0,-</h4>
            <h4>&euro; 0,-</h4>
            <h4>&euro; 0,-</h4>
            <h4>&euro; 0,-</h4>
            <h4>&euro; 0,-</h4>
        </div>
        <div>
            <h5>Hoogste bieding: 0</h5>
            <h5>Aantal biedingen: 0</h5>
            <h5>Resterende tijd: 0</h5>
        </div>
    </div>
    <div class="detailpagina-omschrijving">
        <ul class="tabs" data-tabs id="example-tabs">
            <li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Omschrijving</a></li>
            <li class="tabs-title"><a href="#panel2">Informatie</a></li>
        </ul>
        <hr>
        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.
            Cum
            sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis,
            ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo,
            fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis
            vitae,
            justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum
            semper
            nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac,
            enim.
            Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius
            laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper
            ultricies
            nisi. Nam eget dui.</p>
    </div>
</div>

<?php include "components/scripts.html"; ?>

</body>

</html>