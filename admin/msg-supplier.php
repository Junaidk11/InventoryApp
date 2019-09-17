<?php include ('includes/header.php'); 
require('includes/pdocon.php'); 
?>  
<?php
/*
Include functions
Don't need to include functions.php because the functions.php is included in the header.php so you don't call it twice. 
check to see if user if logged in else redirect to index page
Check to see if the user is logged in, if the session 'user_is_logged_in' is not set, the user accessing this page should be redirected to the index.php, where the user can either register or login.
*/
    if(!($_SESSION['user_is_logged_in'])) // if user_is_logged_in is not set
    {
        /* 
        Redirect to the index.php page, first you need to get out of this page, because index.php is not in the admin folder. 
        */
        redirect('logout.php'); 
    }else{
        
  $product_id = $_GET['product_id']; // Grab the product id from the URL 
  $fullname = $_SESSION['user_data']['fullname']; 
?>
<div id="page-wrapper">
<div class="container-fluid">
  <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-envelope"></i> <a href="reports.php?report_id=<?php echo $product_id; ?>">View Report</a>  
                            </li>
                            <small class="pull-right"><a href="inventory.php"> View Inventory</a> </small>
                        </ol>
                    </div>
                </div>
                 <!-- /.row -->
  <div class="row"> 
      <div class="col-md-8 col-md-offset-2" >
                    <section id="contact" class="grey_section" style="padding:20px; border: 1px solid #ddd;">   
                         <div class="row">
                            <div class="widget widget_contact col-sm-3 to_animate" >
                               <p><strong>Supplier Information</strong></p><br>
                                <?php
                                /************** Get the value from database using id ******************/  
                                // Create a connection to your database by creating a PDO object
                                
                                $db= new Pdocon;
                                //Write your query
                                $db->query('SELECT * FROM inventory WHERE id=:id');
                                     //binding value with your id
                                $db->bindvalue(':id', $product_id, PDO::PARAM_INT);
                                //Fetch data and keep in row variable
                                $row = $db->fetchSingle(); 
                                   if($row) :      
                                ?>
 
                                <p style="background-color: #fff; padding: 3px">
                                    <strong>Name: </strong> <?php echo $row['productSupplier'];//echo product name  ?>
                                </p><hr>
                             
                                <p style="background-color: #fff; padding: 3px">
                                    <strong>Email: </strong> <?php echo $row['productEmail'];//echo email ?>
                                </p><hr>
                                
                                <p style="background-color: #fff; padding: 3px">
                                    <strong>Cost: </strong>$ <?php echo $row['productCost'];//echo amount ?>
                                </p><hr>
                              
                            </div>
                            
                            <div class="col-sm-3">
                               <p><strong></strong></p><br><br>
                               
                                <p>
                                    <strong></strong>
                                </p><br><br>
                             
                                <p class="pull-right">
                                   
                                </p><br><br>
                                <p class="pull-right">
                                   
                                </p><br>
                            </div>
                            <div class="col-sm-6">
                             
                                <form class="form-horizontal" role="form" method="post" action="msg-supplier.php">
                                    <div class="form-group">
                                        <label for="name">Subject <span class="required">*</span></label>
                                        <input type="text" aria-required="true" size="30" value="" name="subject" id="name" class="form-control" placeholder="Subject">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email <span class="required">*</span></label>
                                        <input type="email" aria-required="true" size="30" value="<?php echo $row['productEmail'] ?>" name="email" id="email" class="form-control" placeholder="<?php echo $row['productEmail'] ?>" disabled>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-10">
                                          <input type="hidden" name="product_id" class="form-control" id="product_id" value="<?php echo $product_id;?>" required>
                                        </div>
                                    </div>
                                   
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea aria-required="true" rows="8" cols="45" name="message" id="message" class="form-control" placeholder="Type Message Here"></textarea>
                                    </div>
                                   <div class="form-group">
                                        <!-- <input type="submit" value="Send" id="contact_form_submit" name="contact_submit" class="theme_button"> -->
                                        <button type="submit" id="contact_form_submit" name="email_submit" class="btn btn-default">Send</button>
                                    </div>
                                </form>
                            </div>  
                             <?php endif ; ?>
                        </div><!--row-->    
                    </section>   
                    <?php 
                    //Write a function to check if form is submited
          
                      if(isset($_POST['email_submit'])){

                        
                         //Get product Email
                         $product_id= $_POST['product_id']; // Get the product email from the "email" form 

                        // make connection to the database to fetch the respective cus_id's information - this is the customer you wish to send an email to. 
                       
                        //make the query 
                        $db->query('SELECT * FROM inventory WHERE id=:id');
                        

                        // bind 
                        $db->bindvalue(':id', $product_id, PDO::PARAM_STR); 
                       

                        //Fetch the user and keep it in a row variable
                        $row = $db->fetchSingle();
                        
                        
                        if($row)
                        // Collect customer fullname from the database and keep in and $cus_name variable
                        {
                            $manufacturer_name = $row['productSupplier']; 
                          
  
                        //Collect and validate form field data and keep in $subject and $message variable  
                            
                            $raw_subject = cleandata($_POST['subject']);
                            $clean_subject = sanitizer($raw_subject); 
                            
                            $raw_message = cleandata($_POST['message']);
                            $clean_message = sanitizer($raw_message);
                            
                        

                        // Create the email and send the message
                        $to                 =   $row['productEmail'];        
                        $email_subject = "Subject:  $clean_subject";
                        $email_body = "\nDear $manufacturer_name, \n\nThis is a message from Fraser International College.\n\n"."Here are the details:" ."\n\n $clean_message \n\n";
                        $headers = "From: noreply@junaidjkhan.com"; 
         
                        if(mail($to,$email_subject,$email_body,$headers)){
                           echo "<div class='alert alert-success text-center'>
                                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  <strong>Success!</strong> Your Message has been successfully sent.<a href='inventory.php'> Back to Inventory</a>
                                 </div>";
                            }else{
                            echo "<div class='alert alert-danger text-center'>
                                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                  <strong>Sorry!</strong> Your Message could not be Processed, Please Try Again <a href='inventory.php'> Back to Inventory</a>
                                 </div>";
                        }
                         return true;
                                         
                     }
                }     
        }
?>                                                     
           </div><!--col 8 --> 
    </div><!--row -->   
    
    <br><br><br><br>
    
</div> <!--Container Fluid -->
 <!--******************************* Contact Customer  Form Processor*****************************-->    
</div><!--Page Wrapper -->



<br><br><br><br>


        <!-- libraries -->
        <script src="js/vendor/jquery-1.11.1.min.js"></script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/vendor/jquery.appear.js"></script>

        <!-- superfish menu  -->
        <script src="js/vendor/jquery.hoverIntent.js"></script>
        <script src="js/vendor/superfish.js"></script>
        
        <!-- page scrolling -->
        <script src="js/vendor/jquery.easing.1.3.js"></script>
        <script src='js/vendor/jquery.nicescroll.min.js'></script>
        <script src="js/vendor/jquery.ui.totop.js"></script>
        <script src="js/vendor/jquery.localscroll-min.js"></script>
        <script src="js/vendor/jquery.scrollTo-min.js"></script>
        <script src='js/vendor/jquery.parallax-1.1.3.js'></script>

        <!-- widgets -->
        <script src="js/vendor/jquery.easypiechart.min.js"></script><!-- pie charts -->
        <script src='js/vendor/jquery.countTo.js'></script><!-- digits counting -->
        <script src="js/vendor/jquery.prettyPhoto.js"></script><!-- lightbox photos -->
        <script src='js/vendor/jflickrfeed.min.js'></script><!-- flickr -->
        <script src='twitter/jquery.tweet.min.js'></script><!-- twitter -->

        <!-- sliders, filters, carousels -->
        <script src="js/vendor/jquery.isotope.min.js"></script>
        <script src='js/vendor/owl.carousel.min.js'></script>
        <script src='js/vendor/jquery.fractionslider.min.js'></script>
        <script src='js/vendor/jquery.flexslider-min.js'></script>
        <script src='js/vendor/jquery.bxslider.min.js'></script>

        <!-- custom scripts -->
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

<?php include ('includes/footer.php'); ?>