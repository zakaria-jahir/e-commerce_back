<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    die();
}


if ($_SERVER['REQUEST_METHOD'] !== 'PUT') :
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Request detected! Only PUT method is allowed',
    ]);
    exit;
endif;

require 'config/db.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id_client)) {
    echo json_encode(['success' => 0, 'message' => 'Please enter correct project id.']);
    exit;
}

try {

    $fetch_post = "SELECT * FROM `Client` WHERE id_client=:id_client";
    $fetch_stmt = $con->prepare($fetch_post);
    $fetch_stmt->bindValue(':id_client', $data->id_client, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :
        $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
        $id_client = isset($data->id_client) ? $data->id_client : $row['id_client'];
        $name_client = $data->name_client;
        $last_name = $data->last_name;
        $adresse = $data->adresse;
        $mail = $data->mail;
        $phone = $data->phone;
        $update_query = "UPDATE `Client` SET name_client = :name_client,last_name= :last_name,adresse= :adresse,mail= :mail, phone= :phone
        WHERE id_client = :id_client";

        $update_stmt = $con->prepare($update_query);

        $update_stmt->bindValue(':id_client', htmlspecialchars(strip_tags($id_client)), PDO::PARAM_INT);
        $update_stmt->bindValue(':name_client', htmlspecialchars(strip_tags($name_client)), PDO::PARAM_STR);
        $update_stmt->bindValue(':last_name', htmlspecialchars(strip_tags($last_name)), PDO::PARAM_STR);
        $update_stmt->bindValue(':adresse', htmlspecialchars(strip_tags($adresse)), PDO::PARAM_STR);
        $update_stmt->bindValue(':mail', htmlspecialchars(strip_tags($mail)), PDO::PARAM_STR);
        $update_stmt->bindValue(':phone', htmlspecialchars(strip_tags($phone)), PDO::PARAM_INT);


        if ($update_stmt->execute()) {

            echo json_encode([
                'success' => 1,
                'message' => 'ligne commande updated successfully'
            ]);
            exit;
        }

        echo json_encode([
            'success' => 0,
            'message' => 'Did not update. Something went  wrong.'
        ]);
        exit;

    else :
        echo json_encode(['success' => 0, 'message' => 'Invalid ID. No record found by the ID.']);
        exit;
    endif;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
}