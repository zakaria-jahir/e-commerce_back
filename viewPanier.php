<?php

# Incllude database connection
require_once 'config/db.php';

# Select all data
$query = "SELECT name_client, last_name, phone, adresse, date_creation, etat_livraison, etat_paiment, montant_total 
        FROM panier join Client USING(id_client)
";
$stmt = $con->prepare($query);
$stmt->execute();
$resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
$json = json_encode($resultat);
echo $json; 