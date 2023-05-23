<?php

# Incllude database connection
require_once 'config/db.php';

# Select all data
$query = "SELECT id_product, name, name_product, description, pic, price, availability 
        FROM Product join Category using(id_category)";
$stmt = $con->prepare($query);
$stmt->execute();
$resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
$json = json_encode($resultat);
echo $json; 