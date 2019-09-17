<?php include('includes/header.php'); ?>

<?php 

//require database class files
require('includes/pdocon.php');

//instatiating our database object
$db = new Pdocon;

//Create query to check if new_order table has a new requestNewOrder set.

$db->query("SELECT * FROM new_order");

$results = $db->fetchMultiple();

foreach ($results as $result){
      // If there are entries in the new_order table, send email to each entry and delete the entry. 
        if($result['requestNewOrder']){
            
        // Request each Entry's supplier information from the Inventory database
        $db->query("SELECT * FROM inventory WHERE id=:id");
        $db->bindvalue(':id',$result['inventoryID'], PDO::PARAM_INT);
        $row = $db->fetchSingle();
        if($row){
             // Collect product fullname from the database
            $supplier_name = $row['productSupplier']; 
            // Create the email and send the message
            $to                 =   $row['productEmail'];        
            $email_subject = "Re: Placing new order for". $row['productName']."";
            $email_body = "\nDear $supplier_name, \n\nThis is a Test message for automated order.\n\n";
            $headers = "From: noreply@junaidjkhan.com"; 

            if(mail($to,$email_subject,$email_body,$headers)){
                
                     $db->query('DELETE FROM new_order WHERE inventoryID=:id');
                     $db->bindvalue(':id',$row['id'], PDO::PARAM_INT);
                     $success = $db->execute();
                
                         if($success){

                                echo "<div class='alert alert-success text-center'>
                                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  <strong>Success!</strong> Your order for ". $row['productName']."has been successfully placed. </div>";
                        
                        }
            }else{
                                  echo "<div class='alert alert-danger text-center'>
                                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  <strong>Sorry!</strong> Your Order for".$row['productName']." could not be Processed. Check report!</div>";
                        }
                       
                         return true;

    }
  }
}?>