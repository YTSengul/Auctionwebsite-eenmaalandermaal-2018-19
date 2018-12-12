<?php
include_once "components/connect.php";
include_once 'components/meta.php';
?>

<body>

<?php include_once 'components/header.php'; ?>


<div class="grid-container">
    <div class="grid-x grid-margin-x detailpagina">
        <div class="cell medium-3 large-3" style="background-color: #3b3b3b;">a</div>
        <div class="cell medium-9 large-9" style="background-color: #5e5e5e;">a
            <div class="medium-3 large-3" style="background-color: #7b7b7b;">b

            </div>
        </div>
        <div class="column">
            <img class="thumbnail" src="https://placehold.it/350x200">
            <h5>Other Product <small>$22</small></h5>
            <p>In condimentum facilisis porta. Sed nec diam eu diam mattis viverra. Nulla fringilla, orci ac euismod semper, magna diam.</p>
            <a href="#" class="button hollow tiny expanded">Buy Now</a>
        </div>
<?php include "components/scripts.html"; ?>
    </div>
</div>
</body>
</html>
