<?php
require_once 'config/db.php';

try {
    $id= isset($_GET['id_admin']) ? $_GET['id_admin'] : die('ERROR: Record ID not found.');

    $query = "DELETE FROM Admin WHERE id_admin = ?";
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