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
        setInterval(function(){ display_email_supplier()}, 60000); 
        function display_email_supplier(){ 
            $.get("ajax_email_supplier.php",function(show_email){$("#emailnotification").html(show_email)}) 
    }        
    });  </script>
<style>
    
</style>
<div class="container">
   <!-- Display messages to the users. -->
   <?php showmsg(); ?>  
   <div class ="page-header" style="color: white;">
    <h2 class="text-center"> Search Inventory </h2>
  </div>
  <div class="jumbotron">
         <div class = "row" >
             <form class="form-inline" action="ajax_search_product.php" method="post" id="searchDatabase">
                     <div class="form-group">
                      <label for="productname" style="color: black;"> Product Name: </label>
                      <input type="text" class="form-control" id="productname" name="productname">
                    </div>
                    <button type="submit" class="btn btn-default" style="color:black;">Search</button>
             </form>
         </div>
         <!-- Place Your email notification here -->
         <div class = "row" id="emailnotification"></div>
         <!-- Search Form result from Ajax Here -->    
         <div class="row" id="alert_success"></div>
   </div>  
   
   
   <div class="page-header" style="color: white;">
      <h2 class="text-center"> Inventory </h2>
    </div>

   <div class="jumbotron">
       <small class="pull-right"><a href="add_product.php"> Add Product </a></small>
          <?php $fullname = $_SESSION['user_data']['fullname'];  echo '<small class="pull-left" style="color:#337ab7;">'.$fullname.' | Viewing / Editing </small>';?> 


     <table class="table table-bordered table-hover text-center">
        <thead >
          <tr>
            <th class="text-center" style="color:black;">ID</th>
            <th class="text-center" style="color:black;">Name</th>
            <th class="text-center" style="color:black;">Description </th>
            <!--<th class="text-center" style="color:black;">Supplier</th> -->
           <!-- <th class="text-center" style="color:black;">Email</th> -->
            <!--<th class="text-center" style="color:black;">Cost</th>-->
            <th class="text-center" style="color:black;">Quantity</th>
            <th class="text-center" style="color:black;">Image</th>
          </tr>
        </thead>
        <tbody>
    <?php  foreach ($results as $result) { ?>
          <tr>
            <td style="color:grey;"><?php echo $result['id'] ?></td>
            <td style="color:#262626;"><?php echo $result['productName'] ?></td>
            <td style="color:#262626;"><?php echo $result['productDescription'] ?></td>
            <!-- <td style="color:#262626;"><?php echo $result['productSupplier'] ?></td>-->
           <!-- <td style="color:#262626;"><?php echo $result['productEmail'] ?></td> -->
            <!-- <td style="color:#262626;">$<?php echo $result['productCost'] ?></td> -->
            <td style="color:#262626;"><?php echo $result['quantity'] ?></td>
            <td><?php echo '<img src="uploaded_image/'. $result['image'] .'"style="width:100px;height:100px">'; ?></td>
            <td><a href="https://<?php echo $result['link']; ?>" class='btn btn-primary'>Link</a></td>
            <td><a href="reports.php?report_id=<?php echo $result['id'] ?>" class='btn btn-primary'>View Report</a></td>
            <td><a href="edit_product.php?product_id=<?php echo $result['id']; ?>" class='btn btn-danger'>Update</a></td> 
          </tr>
          <?php } //end your loop ?>
        </tbody>
     </table>
 </div> <!-- end .jumbotron -->  
</div> <!-- end .container -->


<script>   
    $(document).ready(function(){ 
     /* Prevents the update form from refreshing when the submit id of the form is set. 
        #updatedata is the HTML id associated with the update form, the HTML is assigned a function name. */
        $('#searchDatabase').submit(function(stop_default){ 
               stop_default.preventDefault();  
              /* 
              Call AJAX .post method to send the form data to the database using the ajax_form_post php file.
              $(this) is a way to tell ajax that the value to be assigned to the variable 'url' is in this page. The value is stored in the attribute "action" of the form that called this .post method. 
              */ 
              var url = $(this).attr("action");
              /*
                serialize() :  AJAX method serializes all the data coming from the form that was prevented from refreshing.  
                Note: you can check the serialize data by calling alert(data) to check that the page doesn't refresh and the form data is sent via a small popup window. 
              */
              var data = $(this).serialize(); 
              $.post(url, data, function(confirm_form_submission){
                  /* the alert_success html id is the div that is holding the form. So we use the div to alert the user that form data was submitted using the .post() AJAX method. 
                  */
                  $('#alert_success').html(confirm_form_submission); 
              }); 
              /* Next, we want to refresh the form, after submission, if we skip this, the values in the form will still be there.
                 This will reset the id associated with the form that was just submitted via .post() AJAX method. 
                 */
              $('#searchDatabase')[0].reset(); 
         });
    });</script>
<?php include('includes/footer.php'); ?>