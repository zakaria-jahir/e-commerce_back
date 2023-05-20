<?php

# Incllude database connection
require_once 'config/db.php';

# Select all data
// SELECT t1.column1, t2.column2, t3.column3, t4.column4
// FROM table1 t1
// JOIN table2 t2 ON t1.column = t2.column
// JOIN table3 t3 ON t2.column = t3.column
// JOIN table4 t4 ON t3.column = t4.column;

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