<?php include('includes/header.php'); ?>
<?php
/*Include functions - Don't have to, the header takes care of this and also ensures that a session is created for the current user.

//check to see if user if logged in else redirect to index page

*/
?>
<?php 
/************** Fetching all data from database ******************/


//require database class files
require('includes/pdocon.php');

//instatiating our database objects
$db= new Pdocon;


//Create a query to select all users to display in the table
$db->query("SELECT * FROM inventory"); // prepare a query to sellect all the suers   

//Fetch all data and keep in a result set
$results = $db->fetchMultiple();  // All the information in the users table from the database are fetched and stored in $results variable
?>
  <script>
    
    $(document).ready(function(){
        
        display_email_supplier();
        
        setInterval(function(){ display_email_supplier()}, 3000); 
        function display_email_supplier(){ 
            $.get("ajax_email_supplier.php",function(show_email){$("#emailnotification").html(show_email)})
        
    }        
    });
    
</script>
  <div class="container">

   <?php // call your display function to display session message on top page 
      showmsg();
      
   ?>
   
   <div class = "row" id="emailnotification">
           <!-- Place Your email notification here -->
   </div>
   
  <div class="jumbotron">
  
  <small class="pull-right"><a href="add_product.php"> Add Product </a> </small>
 
  <?php 
      
      //Collect session name and write a welcome message with the user session's name 
        
      /*
      Collect the admin's name and put it in there using the session super global
      
    Using the super global variable of $_SESSION, access the current logged in user's information from session 'user_data', that was created when the user logged in. 
     */
      
    $fullname = $_SESSION['user_data']['fullname']; 
    
    echo '<small class="pull-left" style="color:#337ab7;">'.$fullname.' | Veiwing / Editing</small>';
      
   ?>
    
    <h2 class="text-center">Inventory</h2> <hr>
    <br>
     <table class="table table-bordered table-hover text-center">
        <thead >
          <tr>
            <th class="text-center">Item ID</th>
            <th class="text-center">Name</th>
            <th class="text-center">Description </th>
            <th class="text-center">Supplier</th>
            <th class="text-center">Email</th>
            <th class="text-center">Cost</th>
            <th class="text-center">Quantity</th>
            <th class="text-center">Image</th>
            <!-- <th class="text-center">Image</th> -->
          </tr>
        </thead>
        <tbody>
    <?php  foreach ($results as $result) { // loop through your result set and fill in the table : ?>
          <tr>
            <td><?php echo $result['id'] ?></td>
            <td><?php echo $result['productName'] ?></td>
            <td><?php echo $result['productDescription'] ?></td>
            <td><?php echo $result['productSupplier'] ?></td>
            <td><?php echo $result['productEmail'] ?></td>
            <td><?php echo $result['productCost'] ?></td>
            <td><?php echo $result['quantity'] ?></td>
            <td><?php //echo  image folder and concatinate it with a style  
                echo '<img src="uploaded_image/'. $result['image'] .'"style="width:150px;height:150px">'; ?> </td>
            <td><a href="reports.php?report_id=<?php echo $result['id'] ?>" class='btn btn-primary'>View</a></td>
            <td><a href="edit_product.php?product_id=<?php echo $result['id']; ?>" class='btn btn-danger'>Edit</a></td>
            
          </tr>
          
          <?php } //end your loop ?>
        </tbody>
     </table>
</div>
  </div>
    
<?php include('includes/footer.php'); ?>