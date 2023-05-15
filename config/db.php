<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, GET, POST, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=UTF-8');

$host = "db";
$db_name = "ecommerce";
$username = "root";
$password = "";

try {
  $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);

} catch (PDOException $e) {
  echo "Connection error: " . $e->getMessage();
}