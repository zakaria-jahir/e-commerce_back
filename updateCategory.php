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

require_once 'config/db.php';

$data = json_decode(file_get_contents("php://input"));

//print_r($data);

//die();



if (!isset($data->id_category)) {
    echo json_encode(['success' => 0, 'message' => 'Please enter correct category id.']);
    exit;
}

try {

    $fetch_post = "SELECT * FROM `Category` WHERE id_category=:id_category";
    $fetch_stmt = $con->prepare($fetch_post);
    $fetch_stmt->bindValue(':id_category', $data->id_category, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :
        //echo 'AAA';
        $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
        $id_category = isset($data->id_category) ? $data->id_category : $row['id_category'];
        $name = isset($data->name) ? $data->name : $row['name'];
        $update_query = "UPDATE `Category` SET id_category = :id_category, name = :name
        WHERE id_category = :id_category";

        $update_stmt = $con->prepare($update_query);

        $update_stmt->bindValue(':id_category', htmlspecialchars(strip_tags($id_category)), PDO::PARAM_INT);
        $update_stmt->bindValue(':name', htmlspecialchars(strip_tags($name)), PDO::PARAM_STR);
       
        $update_stmt->bindValue(':id_category', $data->id_category, PDO::PARAM_INT);


        if ($update_stmt->execute()) {

            echo json_encode([
                'success' => 1,
                'message' => 'Record udated successfully'
            ]);
            exit;
        }

        echo json_encode([
            'success' => 0,
            'message' => 'Did not udpate. Something went  wrong.'
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