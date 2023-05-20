<?php
require_once 'config/db.php';

try {
    $id= isset($_GET['id_product']) ? $_GET['id_product'] : die('ERROR: Record ID not found.');

    $query = "DELETE FROM Product WHERE id_product = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $id);

    if($stmt->execute()){
        echo json_encode(array('result'=>'success'));
    }else{
        echo json_encode(array('result'=>'fail'));
    }
}
catch(PDOException $e){
    die('ERROR: ' . $e->getMessage());
}