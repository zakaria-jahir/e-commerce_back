<?php

# Incllude database connection
require_once 'config/db.php';
session_start();


try{
    $query = "SELECT * FROM Admin ";
    $stmt = $con->query($query);
    $admins = $stmt->fetchAll();

$username = $_POST['mail'];
$password = $_POST['pass'];

$response = 'faild';
$id =0;
foreach ($admins as $admin) {
    
    if ($username == $admin["mail"]  && $password == $admin["pass"]) {
        
        $_SESSION['log'] = $username;
        $_SESSION['pw'] = $password;
        $response = 'success';
        $id = $admin['id'];

        $data = array(
            'id' => $id,
            'result' => $response
          );
    }
}
echo json_encode($data);

}
catch(PDOException $e){
    die('ERROR:' . $e->getMessage());
}