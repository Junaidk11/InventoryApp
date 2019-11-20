<?php 
include('includes/header.php'); 
require('includes/pdocon.php');
$db = new Pdocon;
$db->query("SELECT * FROM new_order");
$results = $db->fetchMultiple();
foreach ($results as $result){
      /*
       If there are entries in the new_order table, send email to each entry and delete the entry. 
       Request each Entry's supplier information from the Inventory database.
       */
    if($result['requestNewOrder']){
            $db->query("SELECT * FROM inventory WHERE id=:id");
            $db->bindvalue(':id',$result['inventoryID'], PDO::PARAM_INT);
            $row = $db->fetchSingle();
            if($row){
                     /* 
                        Collect product fullname from the database. 
                        Create the email and send the message.
                    */
                    $supplier_name = $row['productSupplier']; 
                    $to                 =   $row['productEmail'];        
                    $email_subject = "Re: Placing new order for". $row['productName']."";
                    $email_body = "\nDear $supplier_name, \n\nThis is a Test message for an automated order.\n\n";
                    $headers = "From: noreply@junaidjkhan.com"; 
                    if(mail($to,$email_subject,$email_body,$headers)){
                        $db->query('DELETE FROM new_order WHERE inventoryID=:id');
                        $db->bindvalue(':id',$row['id'], PDO::PARAM_INT);
                        $success = $db->execute();
                        if($success){
                            echo "<div class='alert alert-success alert-dismissible fade in text-center'>
                                   <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Order for ". $row['productName']." has been successfully placed.</strong> </div>";
                        }
                    }else{
                            echo "<div class='alert alert-danger alert dismissible fade in text-center'>
                                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                    <strong>Order for".$row['productName']." could not be processed.</strong></div>";
                    }
                return true;

         }
  }
}?>