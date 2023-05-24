<?php

# Incllude database connection
require_once 'config/db.php';

# Select all data
$query = "SELECT count(*) as nbr FROM Ligne_commande";
$stmt = $con->prepare($query);
$stmt->execute();
$resultat = $stmt->fetch(PDO::FETCH_ASSOC);
$json = json_encode($resultat);
// echo $json; 
echo json_encode([
    'success' => $resultat,
    'message' => 'There is some problem in data inserting'
]);