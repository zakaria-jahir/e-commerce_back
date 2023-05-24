<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    die();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Request! Only POST method is allowed',
    ]);
    exit;
}

require 'config/db.php';

$data = json_decode(file_get_contents("php://input"));

try {
    $id_client = $data->id_client;
    $id_product = $data->id_product;
    $curDate = date("Y-m-d");

    // Check if the combination of id_panier, id_product, and date_creation exists in ligne_commande
    $query = "SELECT COUNT(*) as count FROM Ligne_commande lc
              INNER JOIN panier p ON lc.id_panier = p.id_panier
              WHERE p.id_client = :id_client
              AND lc.id_product = :id_product
              AND p.date_creation = :date_creation";
    $stmt = $con->prepare($query);
    $stmt->bindValue(':id_client', $id_client, PDO::PARAM_INT);
    $stmt->bindValue(':id_product', $id_product, PDO::PARAM_INT);
    $stmt->bindValue(':date_creation', $curDate);
    $stmt->execute();
    $row = $stmt->fetch();

    if ($row['count'] > 0) {
        // Combination already exists, return a success response without adding the product again
        $response['success'] = true;
        $response['message'] = 'Product already exists in the cart.';
        echo json_encode($response);
        exit;
    }

    // The combination does not exist, proceed with adding the product to ligne_commande
    $sql = "SELECT id_panier FROM `panier` WHERE id_client = :id_client";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(':id_client', $id_client, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();

    if ($row) {
        $response['success'] = true;
        $response['message'] = 'User exists.';
        echo json_encode($response);

        $query = "INSERT INTO `Ligne_commande` (
            id_product,
            id_panier,
            quantite
        ) VALUES (
            :id_product,
            :id_panier,
            1
        )";

        $stmt = $con->prepare($query);

        $stmt->bindValue(':id_panier', $row['id_panier'], PDO::PARAM_INT);
        $stmt->bindValue(':id_product', $id_product, PDO::PARAM_INT);
        $stmt->execute();

        http_response_code(201);
        exit;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
    exit;
}
?>
