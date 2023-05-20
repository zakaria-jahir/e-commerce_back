<?php 

# Check if form was submited
if($_POST){
    require_once 'config/db.php';
    try{
        $query = "INSERT INTO Product SET id_category=:id_category, name_product=:name_product, description=:description, pic=:pic, price=:price, availability=:availability";

        #prepare query 
        $stmt = $con->prepare($query);
        # Posted values
        $id_product = $_POST['id_product'];
        $id_category = $_POST['id_category'];
        $name_product = $_POST['name_product'];
        $description = $_POST['description'];
        $pic = $_POST['pic'];
        $price = $_POST['price'];
        $availability = $_POST['availability'];
         # Bind the parameters
         $stmt->bindParam(':id_product', $id_product);
         $stmt->bindParam(':id_category', $id_category);
         $stmt->bindParam(':name_product', $name_product);
         $stmt->bindParam(':description', $description);
         $stmt->bindParam(':pic', $pic);
         $stmt->bindParam(':price', $price);
         $stmt->bindParam(':availability', $availability);
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