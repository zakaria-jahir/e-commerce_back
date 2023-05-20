<?php 

# Check if form was submited
if($_POST){
    require_once 'config/db.php';
    try{
        $query = "UPDATE Client SET adresse=:adresse, phone=:phone  WHERE id_client = :id";

        #prepare query 
        $stmt = $con->prepare($query);
        # Posted values
        $id = $_POST['id'];
        $adresse = $_POST['adresse'];
        $phone = $_POST['phone'];
         # Bind the parameters
         $stmt->bindParam(':id', $id);
         $stmt->bindParam(':adresse', $adresse);
         $stmt->bindParam(':phone', $phone);
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