<?php

// Function getRubrieken(){

    $querry = "SELECT Rubrieknaam FROM Rubriek WHERE Rubriek = -1";
    $stmt = $dbh->prepare($querry);
    $stmt->execute();
    $alle_rubrieken = $stmt->fetchAll(PDO::FETCH_NUM);



    foreach ($alle_rubrieken as $rubrieken){
        foreach ($rubrieken as $rubriek){
            echo '<pre>';
            var_dump($rubriek);
            echo '</pre>';
        }
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