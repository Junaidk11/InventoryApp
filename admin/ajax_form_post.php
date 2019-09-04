<?php include('includes/header.php'); ?>


<?php


//Include functions

//check to see if user if logged in else redirect to index page

// Don't need to - the file already included in the header file. 
?>



<?php 

/****************Get  customer info to ajax *******************/

//require database class files
require('includes/pdocon.php');


//instatiating our database objects
$db = new Pdocon;


// Things to do in this file. 

//1. write a stametment that will check if a field name coming in from the ajax post is set and then Create a query to update user // You must bind the id coming in from the ajax data   
//2. Get the id and keep it in a variable from the ajax
//3. Bind your id 
//4. Execute and keep the execution result in a row variable
//5. send echo message to ajax


// First check if the form was submitting 

if(isset($_POST['product_id']))
{
    
    $product_id = $_POST['product_id']; // Retrieve the submitting customer_id, this will allow you to update the respective product's info in the database. 
    $raw_threshold  = cleandata($_POST['threshold']);
    $clean_threshold = validateint($raw_threshold);
    $db->query("UPDATE inventory SET thresholdQuantity=:threshold WHERE id=:id");
    $db->bindvalue(':id',$product_id, PDO::PARAM_INT);
    $db->bindvalue(':threshold',$clean_threshold,PDO::PARAM_INT); 
    $row = $db->execute();
    
            if($row)
            {

                 echo "<p class='bg-success text-center' style='font-weight:bold;'>Minimum Required Updated </p>";
    
            }
}

?>