<?php 

# Check if form was submited
if($_POST){
    require_once 'config/db.php';
    try{
        $query = "UPDATE Client SET name_client=:name_client, last_name=:last_name, mail=:mail, pass=:pass WHERE id_client = :id_client";

        #prepare query 
        $stmt = $con->prepare($query);
        # Posted values
        $id_client = $_POST['id_client'];
        $name_client = $_POST['name_client'];
        $last_name = $_POST['last_name'];
        $mail = $_POST['mail'];
        $pass = $_POST['pass'];
        # Bind the parameters
        $stmt->bindParam(':id_client', $id_client);
        $stmt->bindParam(':name_client', $name_client);
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