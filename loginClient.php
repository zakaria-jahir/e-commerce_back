<?php

# Incllude database connection
require_once 'config/db.php';
session_start();


try{
    $query = "SELECT * FROM Client ";
    $stmt = $con->query($query);
    $clients = $stmt->fetchAll();

$username = $_POST['mail'];
$password = $_POST['pass'];

$response = 'faild';
$id =0;
foreach ($clients as $client) {
    // echo ''.$client["mail"]." ".$client["password"].'';
    
    if ($username == $client["mail"]  && $password == $client["pass"]) {
        
        $_SESSION['log'] = $username;
        $_SESSION['pw'] = $password;
        $response = 'success';
        $id = $client['id_client'];

        $data = array(
            'id' => $id,
            'result' => $response
          );
    }
}
// echo json_encode(array('result'=>"$response",$id));
echo json_encode($data);


}
catch(PDOException $e){
    die('ERROR:' . $e->getMessage());
}
