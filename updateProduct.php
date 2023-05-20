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



if (!isset($data->id_product)) {
    echo json_encode(['success' => 0, 'message' => 'Please enter correct category id.']);
    exit;
}

try {

    $fetch_post = "SELECT * FROM `Product` WHERE id_product=:id_product";
    $fetch_stmt = $con->prepare($fetch_post);
    $fetch_stmt->bindValue(':id_product', $data->id_product, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :
        //echo 'AAA';
        $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
        $id_product = isset($data->id_product) ? $data->id_product : $row['id_product'];
        $id_category = isset($data->id_category) ? $data->id_category : $row['id_category'];
        $name_product = isset($data->name_product) ? $data->name_product : $row['name_product'];
        $description = isset($data->description) ? $data->description : $row['description'];
        $pic = isset($data->pic) ? $data->pic : $row['pic'];
        $price = isset($data->price) ? $data->price : $row['price'];
        $availability = isset($data->availability) ? $data->availability : $row['availability'];
        $update_query = "UPDATE `Product` SET id_product = :id_product, id_category= :id_category, name_product = :name_product, description= :description, pic= :pic, price= :price,
        availability= :availability WHERE id_product = :id_product";

        $update_stmt = $con->prepare($update_query);

        $update_stmt->bindValue(':id_product', htmlspecialchars(strip_tags($id_product)), PDO::PARAM_INT);
        $update_stmt->bindValue(':id_category', htmlspecialchars(strip_tags($id_category)), PDO::PARAM_INT);
        $update_stmt->bindValue(':name_product', htmlspecialchars(strip_tags($name_product)), PDO::PARAM_STR);
        $update_stmt->bindValue(':description', htmlspecialchars(strip_tags($description)), PDO::PARAM_STR);
        $update_stmt->bindValue(':pic', htmlspecialchars(strip_tags($pic)), PDO::PARAM_STR);
        $update_stmt->bindValue(':price', htmlspecialchars(strip_tags($price)), PDO::PARAM_STR);
        $update_stmt->bindValue(':availability', htmlspecialchars(strip_tags($availability)), PDO::PARAM_STR);
       
        $update_stmt->bindValue(':id_product', $data->id_product, PDO::PARAM_INT);


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