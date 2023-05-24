<?php

# Incllude database connection
require_once 'config/db.php';
$data = json_decode(file_get_contents("php://input"));
$id=$data->id1;
# Select all data
try {
$query = "SELECT id_product,id_panier,pic, name_product, quantite, price FROM Ligne_commande JOIN Product USING(id_product) join panier using (id_panier) where id_client='$id'";
$stmt = $con->prepare($query);
    $stmt->execute();
    $row = $stmt->fetchAll();
    if ($row) {
            $response['success'] = true;
            $response['message'] = 'Panier success.';
            $response['data']=$row;
        }
     else {
        $response['success'] = false;
        $response['message'] = 'panier not found.';
    }

    echo json_encode($response);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
}