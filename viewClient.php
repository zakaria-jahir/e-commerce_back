<?php

# Incllude database connection
require_once 'config/db.php';

# Select all data
$query = "SELECT * FROM Client";
$stmt = $con->prepare($query);
$stmt->execute();
$resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
$json = json_encode($resultat);
echo $json; 