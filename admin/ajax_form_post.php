<?php 
include('includes/header.php');
if(isset($_POST['product_id'])){
    require('includes/pdocon.php');
    $db = new Pdocon;
    $product_id = $_POST['product_id']; 
    $raw_threshold  = cleandata($_POST['threshold']);
    $clean_threshold = validateint($raw_threshold);
    $db->query("UPDATE inventory SET thresholdQuantity=:threshold WHERE id=:id");
    $db->bindvalue(':id',$product_id, PDO::PARAM_INT);
    $db->bindvalue(':threshold',$clean_threshold,PDO::PARAM_INT); 
    $row = $db->execute();
            if($row){
                echo "<p class='bg-success text-center' style='font-weight:bold;'>Minimum Required Updated </p>";
            }
}?>