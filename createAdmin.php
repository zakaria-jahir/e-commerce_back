<?php

if($_POST){

    # Include database connection
    require_once 'config/db.php';

    try{

        # Insert query
        $query = "INSERT INTO Admin SET name=:name, last_name=:last_name,  mail=:mail,  pass=:pass";
        # Prepare query for execution
        $stmt = $con->prepare($query);
        # Posted values
        $name = $_POST['name'];
        $last_name = $_POST['last_name'];
        $mail = $_POST['mail'];
        $pass = $_POST['pass'];
        # Bind the parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':pass', $pass);
        # Execute the query
        if ($stmt->execute()) {
            echo json_encode(array('result'=>'success'));
        }else{
            echo json_encode(array('result'=>'fail'));
        }
    }
    catch(PDOException $e){
        die('ERROR:' . $e->getMessage());
    }
}