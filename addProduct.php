<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    die();
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') :
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Request!.Only POST method is allowed',
    ]);
    exit;
endif;

require_once 'config/db.php';

$data = json_decode(file_get_contents("php://input"));


try {
    $id_category = $data->id_category;
    $name_product = $data->name_product;
    $description = $data->description;
    $pic1 = $data->pic;
    $pic = substr($pic1,12);
    $price = $data->price;
    $availability = $data->availability;
    
    $query = "INSERT INTO `Product`(
    id_category,
    name_product,
    description,
    pic,
    price,
    availability
    ) 
    VALUES(
    :id_category,
    :name_product,
    :description,
    :pic,
    :price,
    :availability   
    )";

    $stmt = $con->prepare($query);

    $stmt->bindValue(':id_category', $id_category, PDO::PARAM_STR);
    $stmt->bindValue(':name_product', $name_product, PDO::PARAM_STR);
    $stmt->bindValue(':description', $description, PDO::PARAM_STR);
    $stmt->bindValue(':pic', $pic, PDO::PARAM_STR);
    $stmt->bindValue(':price', $price, PDO::PARAM_STR);
    $stmt->bindValue(':availability', $availability, PDO::PARAM_STR);
    if ($stmt->execute()) {

        http_response_code(201);
        echo json_encode([
            'success' => 1,
            'message' => 'Data Inserted Successfully.'
        ]);
        exit;
    }

    echo json_encode([
        'success' => 0,
        'message' => 'There is some problem in data inserting'
    ]);
    exit;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
}