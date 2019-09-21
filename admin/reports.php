<?php include('includes/header.php'); $product_id = $_GET['report_id']; ?>
<script>
$(document).ready(function(){ 
     /* Display  detailed product information on this page. 
        For now: 
            Refresh page every second. 
            Display/Refresh Product information every second. 
            Display/Refresh Supplier information every second. 
            Send automatic message to supplier if new order has been triggered. 
     */
    display_report_menu();
    display_supplier_info();
    display_email_supplier();
    setInterval(function(){ display_report_menu()}, 1000);  
    setInterval(function(){ display_supplier_info()}, 2000);
    setInterval(function(){ display_email_supplier()}, 3000); 
    function display_report_menu(){
        
        $.get("ajax_report_menu.php?cus_id=<?php echo $product_id; ?>", function(show_report){$("#report_menu").html(show_report)})
    }   
    function display_supplier_info(){
        
        $.get("ajax_show_supplier.php?cus_id=<?php echo $product_id; ?>", function(show_customer){$("#customerinfo").html(show_customer)})
    }
    function display_email_supplier(){
        $.get("ajax_email_supplier.php",function(show_email)
         {$("#emailnotification").html(show_email)})      
    }
});
</script>
<?php 
require('includes/pdocon.php'); 
$db = new Pdocon;
$db->query("SELECT * FROM inventory WHERE id=:id");
$db->bindvalue(':id',$product_id, PDO::PARAM_INT);
$run_query = $db->fetchSingle();
if($run_query){ 
?>  
<div id="page-wrapper">
   <div class="container-fluid">
     <?php  $fullname = $_SESSION['user_data']['fullname']; echo'<small class="pull-left" style="color:#337ab7;">'.$fullname.' | Viewing Report </small>';?>
                <!-- Page Heading -->
        <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"> Product Report </h3>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-envelope"></i><a href="msg-supplier.php?product_id=<?php echo $product_id; ?>"> Order </a>  
                            </li>
                            <small class="pull-right"><a href="inventory.php"> View Inventory </a> </small>
                        </ol>
                    </div>
                </div>
                <!-- Email success information from Ajax inside div below. -->
        <div class="row" id="emailnotification">
        
        </div>
                <!-- Report information from Ajax Here -->    
        <div class="row" id="report_menu">
        
        </div> 
                <!-- Update threshold information from Ajax Here -->    
        <div class="row" id="alert_success">
            
        </div>
        <div class="row">
             <div class="col-lg-4">
                 <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title text-center"><i class="fa fa-money fa-fw"></i> Supplier Information </h3>
                            </div>
                                 <!-- Supplier information from Ajax Here -->
                            <div id="customerinfo" class="panel-body" style="background-color:white;">
                                <div class="text-right">
                                    <a href="#"><i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                </div>
              </div>
             <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center"><i class="fa fa-money fa-fw"> </i> <?php echo $run_query['productName']; ?> </h3>
                    </div>
                    <div class="panel-body">
                        <?php echo '<img src="uploaded_image/'. $run_query['image'] .'"style="width:400px;height:400px" class="center">'; ?>
                        <div class="text-right">
                            <a href="#"><i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
             </div>
             <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                      <h3 class="panel-title text-center"><i class="fa fa-money fa-fw"></i> Minimum Required</h3>
                    </div>
                    <div class="panel-body">
                        <form method="post" class="form-horizontal" role="form" action="ajax_form_post.php" id="updatedata">   
                            <div class="form-group" >
                                  <label class="control-label col-sm-2" for="salary" style="color:#777;">Minimum</label>
                                  <div class="col-sm-10">
                                    <input type="text" name="threshold" class="form-control" placeholder="Update minimum required" required>
                                  </div>
                            </div>
                            <div class="form-group">
                                  <div class="col-sm-10">
                                       <input type="hidden" name="product_id" class="form-control" id="product_id" value="<?php echo $product_id;?>" required>
                                  </div>
                            </div>
                            <div class="form-group"> 
                                  <div class="col-sm-offset-2 col-sm-10">
                                       <input type="submit" class="btn btn-primary" name="update_threshold" value="submit">
                                  </div>
                            </div>
                         </form>         
                         <div class="text-right">
                            <a href="#"><i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
 <!-- ************************************************* Updating data to database using id **************************************************************** -->
<script>
    /* 
        Post Data to Database.
        Prevent Form from sending Data by itself and refreshing the page.
        Collect all data from the form.
        Get the action value from the form.
        Create your .post() and reset the form values.
    */          
    $(document).ready(function(){ 
     /* Prevents the update form from refreshing when the submit id of the form is set. 
        #updatedata is the HTML id associated with the update form, the HTML is assigned a function name. */
        $('#updatedata').submit(function(stop_default){ 
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
              $('#updatedata')[0].reset(); 
         });
    });
</script>
<?php include('includes/footer.php'); ?>