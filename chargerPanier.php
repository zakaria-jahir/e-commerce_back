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

if (!isset($data->id_panier)) {
    echo json_encode(['success' => 0, 'message' => 'Please enter correct project id.']);
    exit;
}

try {

    $fetch_post = "SELECT * FROM `panier` WHERE id_panier=:id_panier";
    $fetch_stmt = $con->prepare($fetch_post);
    $fetch_stmt->bindValue(':id_panier', $data->id_panier, PDO::PARAM_INT);
    $fetch_stmt->execute();

    if ($fetch_stmt->rowCount() > 0) :
        $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
        $id_panier = isset($data->id_panier) ? $data->id_panier : $row['id_panier'];
        $etat_livraison ='en cours';
        $etat_paeiment='en cours';
        $montant_total=$data->montant_total;
        $update_query = "UPDATE `panier` SET etat_livraison = :etat_livraison,etat_paiment = :etat_paiment,montant_total = :montant_total
        WHERE  id_panier= :id_panier";

        $update_stmt = $con->prepare($update_query);

        $update_stmt->bindValue(':etat_livraison', htmlspecialchars(strip_tags($etat_livraison)), PDO::PARAM_STR);
        $update_stmt->bindValue(':etat_paeiment', htmlspecialchars(strip_tags($etat_paeiment)), PDO::PARAM_STR);
        $update_stmt->bindValue(':id_panier', htmlspecialchars(strip_tags($id_panier)), PDO::PARAM_INT);
        $update_stmt->bindValue(':montant_total', htmlspecialchars(strip_tags($montant_total)), PDO::PARAM_INT);


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