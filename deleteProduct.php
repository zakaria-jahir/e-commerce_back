<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    die();
}


if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') :
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Reqeust detected. HTTP method should be DELETE',
    ]);
    exit;
endif;

require_once 'config/db.php';

$data = json_decode(file_get_contents("php://input"));
//echo $data = file_get_contents("php://input");

$id_product =  $_GET['id_product'];



if (!isset($id_product)) {
    echo json_encode(['success' => 0, 'message' => 'Please provid id_product.']);
    exit;
}


try {
    
    $fetch_post = "SELECT * FROM `Product` WHERE id_product=:id_product";
    $fetch_stmt = $con->prepare($fetch_post);
    $fetch_stmt->bindValue(':id_product', $id_product, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :

        $delete_post = "DELETE FROM `Product` WHERE id_product=:id_product";
        $delete_post_stmt = $con->prepare($delete_post);
        $delete_post_stmt->bindValue(':id_product', $id_product, PDO::PARAM_INT);

        if ($delete_post_stmt->execute()) {

            echo json_encode([
                'success' => 1,
                'message' => 'Record Deleted successfully'
            ]);
            exit;
        }

        echo json_encode([
            'success' => 0,
            'message' => 'Could not delete. Something went wrong.'
        ]);
        exit;

    else :
        echo json_encode(['success' => 0, 'message' => 'Invalid_product id_product. No projects found by the id_product.']);
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