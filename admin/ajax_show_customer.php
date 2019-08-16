<?php include('includes/header.php'); ?>


<?php

//Include functions

//check to see if user if logged in else redirect to index page

// Don't need to - the file already included in the header file. 

?>



<?php 

// Collect the customer id from the url to this page, using the GET super global variable
$id = $_GET['cus_id'];


/****************Get  customer info to ajax *******************/

//require database class files
require('includes/pdocon.php');

//instatiating our database objects
$db = new Pdocon; 


//Create a query to display customer inf // You must bind the id coming in from the ajax data
$db->query('SELECT * FROM users WHERE id=:id'); 

    
//Get the id and keep it in a variable from the ajax - already implemented above 


//Bind your id
$db->bindvalue(':id',$id, PDO::PARAM_INT);

//Fetching the data and keep it a row variable
$row = $db->fetchSingle();


//Display this result to ajax
    if($row){
        
        echo '  <div  class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr >
                                <th class="text-center">Customer Name</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                           <tr class="text-center">
                            <td>' . $row['full_name'] . '</td>
                            <td>$ ' . $row['spending_amt'] . '</td>
                            <td>' . $row['email'] . '</td>
                          </tr>

                        </tbody>
                    </table>
                </div>';
    }



?>

