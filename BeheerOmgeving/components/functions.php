<?php
echo 'import CHECK -   ';
$hoofdRubrieken = array(getRubrieken());
echo 'array aangemaakt -';

// Function getRubrieken(){
    echo 'called function getRubrieken -';
    $querry = "SELECT Rubrieknaam FROM Rubriek WHERE Rubriek = -1";
    echo "querry made: $querry /-/ ";
    $stmt = $dbh->prepare($querry);
    echo 'prepped -';
    $stmt -> execute();
    echo 'executed -';
    
    $pos=0;
    
    while($rubriek = $stmt->fetch()){
        $naam = $stmt['rubrieknaam'];
        
        $hoofdRubrieken[$pos] = $naam;
        $pos += 1;
    }
    
    // count() function is used to count  
    // the number of elements in an array 
    $round = count($hoofdRubrieken);  
    echo "\nThe number of elements are $round \n"; 
    
    // Another way to loop through the array using for 
    echo "Looping using for: \n"; 
    for($n = 0; $n < $round; $n++){ 
        echo $hoofdRubrieken[$n], "\n"; 
    }
// }