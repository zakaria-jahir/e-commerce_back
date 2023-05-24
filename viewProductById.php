<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$id_product = isset($_GET['id_product']) ? $_GET['id_product'] : die('ERROR: ID not found.');

# Incllude database connection
require_once 'config/db.php';

try{
    $query = "SELECT id_product, id_category,name_product, description, price,availability 
        FROM Product WHERE id_product = ? LIMIT 0,1";
    $stmt = $con->prepare($query);

    $stmt->bindParam(1, $id_product);   

    $stmt->execute();
    header("Content-type: application/json; charest=utf-8");

    // store to a varibale
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $json = json_encode($row);
    echo $json;
}
catch(PDOException $e){
    die('ERROR: ' . $e->getMessage());
}