<?php

# Incllude database connection
require_once 'config/db.php';

# Select all data
$query = "SELECT name_client , last_name, phone, name_product, price, availability, date_creation 
FROM Client join panier using(id_client) join Product using(id_product) ";
$stmt = $con->prepare($query);
$stmt->execute();
$resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
$json = json_encode($resultat);
echo $json; 