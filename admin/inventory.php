<?php include('includes/header.php'); ?>
<?php 
/******************************************  Fetching all data from database *******************************************************************************/
require('includes/pdocon.php');
$db= new Pdocon;

/*Create a query to select all rows of inventory table.*/
$db->query("SELECT * FROM inventory"); 

/*Fetch all rows from inventory table.*/
$results = $db->fetchMultiple(); 
?>
 <!-- The script below is running in the background every 3-second to place new orders - if needed. -->
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
   <!-- Display messages to the users. -->
   <?php showmsg(); ?> 
   <div class = "row" id="emailnotification"><!-- Place Your email notification here --></div>
   
  <div class="jumbotron">
  <small class="pull-right"><a href="add_product.php"> Add Product </a></small>
  <?php /*Collect Admin's - From $_SESSION['user_data'] */
    $fullname = $_SESSION['user_data']['fullname']; 
    echo '<small class="pull-left" style="color:#337ab7;">'.$fullname.' | Viewing / Editing </small>';
   ?>
    <h2 class="text-center">Inventory</h2>
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
          </tr>
        </thead>
        <tbody>
    <?php  foreach ($results as $result) { ?>
          <tr>
            <td><?php echo $result['id'] ?></td>
            <td><?php echo $result['productName'] ?></td>
            <td><?php echo $result['productDescription'] ?></td>
            <td><?php echo $result['productSupplier'] ?></td>
            <td><?php echo $result['productEmail'] ?></td>
            <td><?php echo $result['productCost'] ?></td>
            <td><?php echo $result['quantity'] ?></td>
            <td><?php echo '<img src="uploaded_image/'. $result['image'] .'"style="width:100px;height:100px">'; ?></td>
            <td><a href="reports.php?report_id=<?php echo $result['id'] ?>" class='btn btn-primary'>View</a></td>
            <td><a href="edit_product.php?product_id=<?php echo $result['id']; ?>" class='btn btn-danger'>Edit</a></td> 
          </tr>
          <?php } //end your loop ?>
        </tbody>
     </table>
</div> <!-- end .jumbotron -->
</div> <!-- end .container -->
<?php include('includes/footer.php'); ?>