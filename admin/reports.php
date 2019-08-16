<?php include('includes/header.php'); ?>


<?php

//Include functions

//check to see if user if logged in else redirect to index page

//Don't need to include functions.php because the functions.php is included in the header.php so you don't call it twice. 

?>
<?php


// This page is used to provide a report about the selected customer from the users.php file. Therefore, we need the 'id' of the customer selected to have access to the users information from the database - if needed. 

// The customer id can be collected using the GET super global which allows extracting information from the url of this page. 


//Get ID and pass it on to ajax
    $product_id = $_GET['report_id'];

?>
    
    
<script>

$(document).ready(function(){
    
    
    /* 
    Things to implement in this function
    
    1- Call function to display Customer information at a certain interval
    2- Get id from php to javascript 
    3- create a function to display result menu using .ajax()
    4- create a function to get and Display customer information .ajax() 
    5- Use Java's setInterval(function, refreshperiodinmillisecond) - this method 
     calls the function every defined millisecond, the page gets refreshed at that specified interval - automatically. Without this function, you would need
     to press refresh before your page shows the updated information. 
    */
    /*
     
     The function below will only be called once on this page. Therefore, to notice updated information on the page, you would need to press the refresh button everytime. To avoid this, you can call the functions below periodically by utilizing Java's setinterval method as shown by the uncommmented section below
    
    //Call function to display result menu at a certain interval
    display_report_menu();
    
    //Call function to display customer information 
    display_customer_info()
    
    // The function below utilizes the .ajax() method to display a report to the user. 
    // Read the ajax tutorial that I did. 
   
    */
    
    setInterval(function(){ display_report_menu()}, 2000);  // the display_report_menu() function is called every 1 second automatically. 
    setInterval(function(){ display_customer_info()}, 4000); // the display_customer_info() function is called every 1 second automatically. 
   
    function display_report_menu()
    {

        $.ajax({
            
            url: 'ajax_report_menu.php?cus_id=<?php echo $product_id; ?>', 
            type: 'POST',
            success: function(show_report){
                
                if(show_report){
                    $("#report_menu").html(show_report);
                }
                
            }
          
        });
    
    }
    
    
    function display_customer_info(){
        
        $.get("ajax_show_customer.php?cus_id=<?php echo $product_id; ?>", function(show_customer){$("#customerinfo").html(show_customer)})
    }
 
});
    
</script>
    
    


    <div id="page-wrapper">

            <div class="container-fluid">

                 <?php 
                                    $fullname = $_SESSION['user_data']['fullname']; 
                                    echo '<small class="pull-left" style="color:#337ab7;">'.$fullname.' | Product Report</small>';  
                 ?>
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">
                        </h3>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-envelope"></i><a href="msg-customer.php?cus_id=<?php echo $product_id; ?>">Place a new order</a>  
                            </li>
                            <small class="pull-right"><a href="inventory.php"> View Inventory </a> </small>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                 <!-- FIRST ROW WITH PANELS -->

                <!-- /.row -->
                <div class="row" id="report_menu">
                 
              
                </div> 

                <div class="row">
                    
                    <div class="col-lg-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title text-center"><i class="fa fa-money fa-fw"></i> Product Information</h3>
                            </div>
                            <div id="customerinfo" class="panel-body" style="background-color:lightgrey;">
                                 <!-- Customer information from Ajax Here -->
                              
                                <div class="text-right">
                                    <a href="#"><i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title text-center"><i class="fa fa-money fa-fw"></i> Order History</h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-center">
                                        <thead>
                                            <tr>
                                                <th>Order#</th>
                                                <th>Order Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>3326</td>
                                                <td>10/21/2013</td>
                                            </tr>
                                            <tr>
                                                <td>3325</td>
                                                <td>10/21/2013</td>
                                        
                                            </tr>
                                            <tr>
                                                <td>3324</td>
                                                <td>10/21/2013</td>
                                            </tr>
                                            <tr>
                                                <td>3323</td>
                                                <td>10/21/2013</td>
                                            </tr>
                                      
                                        </tbody>
                                    </table>
                                </div>
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
                            <div id="alert_success" class="panel-body">
   
                                          <br>
                                          
                                            <form method="post" class="form-horizontal" role="form" action="ajax_form_post.php" id="updatedata">
                                                  <div class="form-group">
                                                        <label class="control-label col-sm-2" for="salary" style="color:#777;">Quantity</label>
                                                        <div class="col-sm-10">
                                                          <input type="text" name="salary" class="form-control" id="salary" placeholder="Update threshold" required>
                                                        </div>
                                                  </div>
                                                  <div class="form-group">
                                                        <div class="col-sm-10">
                                                          <input type="hidden" name="cus_id" class="form-control" id="cus_id" value="<?php echo $product_id; ?>" required>
                                                        </div>
                                                  </div>

                                                  <div class="form-group"> 
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                      <input type="submit" class="btn btn-primary" name="update_customer" value="submit"  id="">
                                                    </div>
                                                  </div>
                                            </form>
                                            
                                <div class="text-right">
                                    <a href="#"><i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <script>
                        
            /************** Updating data to database using id ******************/ 
                        
                        // The .post method of AJAX allows you to update the database without refreshing the page. 
                        // I.e. using the .post method of AJAX, you can see real-time changes on the webpage. 
                    
                    $(document).ready(function(){ 

                           //Post Data to Database


                            //Prevent Form from sending Data by itself and refreshing the page


                            //Collect all data from the form 
                        

                            //Get the action value from the form
                           

                            //Create you .post() and reset the form values
                        
                        
                       // Create a function that prevents the update form from refreshing when the submit id of the form is set.  
                        $('#updatedata').submit(function(stop_default){ // #updatedata is the HTML id associated with the update form, the HTML is assigned a function name.
                        
                            stop_default.preventDefault();  //This function will prevent the udpate form id from refreshing the page to the assigned action page
                            
                            // Now we call the AJAX .post method to send the form data to the database using the ajax_form_post php file 
                            
                            var url = $(this).attr("action"); // $(this) is a way to tell ajax that the value to be assigned to the variable 'url' is in this page. The value is stored in the attribute "action" of the form that called this .post method. 
                            
                            var data = $(this).serialize(); // This AJAX method  serializes all the data coming from the form that was prevented from refreshing.  Note: you can check the serialize data by calling the alert(data) to check that the page doesn't refresh and the form data is sent via a small popup window
                   
                            $.post(url, data, function(confirm_form_submission){
                                
                                $('#alert_success').html(confirm_form_submission); // the alert_success html id is the div that is holding the form. So we use the div to alert the user that form data was submitted using the .post() AJAX method. 
                            }); 
                            
                            
                            // Next, we want to refresh the form, after submission, if we skip this, the values in the form will still be there. 
                            $('#updatedata')[0].reset(); // This will reset the id associated with the form that was just submitted via .post() AJAX method
                        });
               
                    });

                </script>
 
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
<?php include('includes/footer.php'); ?>