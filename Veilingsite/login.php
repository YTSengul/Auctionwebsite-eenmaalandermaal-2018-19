<?php
include_once "header.php"
?>
<body>
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

        <h5>Nieuw bij EenmaalAndermaal?</h5>
        <h5>Registreren</h5>
        <form class='cell large-4'>
            <label>Gebruikersnaam</label>
            <input type="text" placeholder="Gebruikersnaam">
            <label>Wachtwoord</label>
            <input type="password" placeholder="Wachtwoord">
            <label>Herhaal Wachtwoord</label>
            <input type="password" placeholder="Herhaal wachtwoord">
        </form> 
    </div>
    <?php include "scripts.html"?>
</body>
</html>