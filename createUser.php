<?php

if($_POST){

    # Include database connection
    require_once 'config/db.php';

    try{

        # Insert query
        $query = "INSERT INTO Client SET nom=:nom, prenom=:prenom, mail=:mail, numTel=:numTel, password=:password";
        # Prepare query for execution
        $stmt = $con->prepare($query);
        # Posted values
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mail = $_POST['mail'];
        $numTel = $_POST['numTel'];
        $password = $_POST['password'];
        # Bind the parameters
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':numTel', $numTel);
        $stmt->bindParam(':password', $password);
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