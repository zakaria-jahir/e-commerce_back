<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
error_reporting(E_ERROR);
require 'config/db.php';

$data = json_decode(file_get_contents("php://input"));

$email = $data->mail;
$password = $data->pass;

try {
    $sql = "SELECT * FROM `Admin` WHERE mail = '$mail'";
    $stmt = $con->prepare($sql);
    $stmt->execute();

    $row = $stmt->fetch();
    if ($row) {
        $hashedPasswordFromDB = $row['pass'];
        if (password_verify($password, $hashedPasswordFromDB)) {
            $response['success'] = true;
            $response['message'] = 'Login successful.';
            $response['username'] = $row['username'];
        } else {
            $response['success'] = false;
            $response['message'] = 'Incorrect password.';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Email not found.';
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

