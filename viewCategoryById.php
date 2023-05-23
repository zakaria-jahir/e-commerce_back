<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$id_category = isset($_GET['id_category']) ? $_GET['id_category'] : die('ERROR: ID not found.');

# Incllude database connection
require_once 'config/db.php';

try{
    $query = "SELECT id_category, name FROM Category WHERE id_category = ? LIMIT 0,1";
    $stmt = $con->prepare($query);

    $stmt->bindParam(1, $id_category);   

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