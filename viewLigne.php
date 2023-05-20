<?php

# Incllude database connection
require_once 'config/db.php';

$query = "SELECT name_client, last_name, phone, mail, pic, name_product, quantite, price 
FROM Ligne_commande
JOIN Product on  Ligne_commande.id_product = Product.id_product
join panier on Ligne_commande.id_panier = panier.id_panier
join Client on panier.id_client = Client.id_client ";
$stmt = $con->prepare($query);
$stmt->execute();
$resultat = $stmt->fetchAll(PDO::FETCH_ASSOC);
$json = json_encode($resultat);
echo $json; 