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
require 'config/db.php';

$data = json_decode(file_get_contents("php://input"));

try {
    $id_client = $data->id_client;
    $query = "INSERT INTO `panier`(
        id_client,
        date_creation
        ) 
        VALUES(
        :id_client,
        curdate()
        )";
    
        $stmt = $con->prepare($query);
    
        $stmt->bindValue(':id_client', $id_client, PDO::PARAM_INT);
        //$stmt->bindValue(':date_creation', $date_creation, PDO::PARAM_STR);
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                'success1' => true,
                'message' => 'Data Inserted Successfully.'
            ]);
            exit;
        }
    
        echo json_encode([
            'success1' => false,
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