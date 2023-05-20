<?php 

# Check if form was submited
if($_POST){
    require_once 'config/db.php';
    try{
        $query = "UPDATE Admin SET name=:name, last_name=:last_name,  mail=:mail,  pass=:pass WHERE id_admin = :id_admin";

        #prepare query 
        $stmt = $con->prepare($query);
        # Posted values
        $id_admin = $_POST['id_admin'];
        $name = $_POST['name'];
        $last_name = $_POST['last_name'];
        $mail = $_POST['mail'];
        $pass = $_POST['pass'];
        # Bind the parameters
        $stmt->bindParam(':id_admin', $id_admin);
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