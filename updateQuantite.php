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

if (!isset($data->id_panier)|| !isset($data->id_product)) {
    echo json_encode(['success' => 0, 'message' => 'Please enter correct project id.']);
    exit;
}

try {

    $fetch_post = "SELECT * FROM `ligne_commande` WHERE id_product=:id_product and id_panier=:id_panier";
    $fetch_stmt = $con->prepare($fetch_post);
    $fetch_stmt->bindValue(':id_panier', $data->id_panier, PDO::PARAM_INT);
    $fetch_stmt->bindValue(':id_product', $data->id_product, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :
        $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
        $id_product = isset($data->id_product) ? $data->id_product : $row['id_product'];
        $id_panier = isset($data->id_panier) ? $data->id_panier : $row['id_panier'];
        $quantite = $data->quantite;
        $update_query = "UPDATE `ligne_commande` SET quantite = :quantite
        WHERE id_product = :id_product and id_panier= :id_panier";

        $update_stmt = $con->prepare($update_query);

        $update_stmt->bindValue(':quantite', htmlspecialchars(strip_tags($quantite)), PDO::PARAM_INT);
        $update_stmt->bindValue(':id_product', htmlspecialchars(strip_tags($id_product)), PDO::PARAM_INT);
        $update_stmt->bindValue(':id_panier', htmlspecialchars(strip_tags($id_panier)), PDO::PARAM_INT);


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