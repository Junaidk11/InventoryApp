<?php include ('includes/header.php'); require('includes/pdocon.php'); $product_id = $_GET['product_id'];  $fullname = $_SESSION['user_data']['fullname']; ?> 
<div id="page-wrapper">
    <div class="container-fluid">
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
                <div class="row"> 
                      <div class="col-md-8 col-md-offset-2" >
                            <section id="contact" class="grey_section" style="padding:30px; border:15px solid black;">   
                                <div class="row">
                                        <div class="widget widget_contact col-sm-3 to_animate">
                                                   <p style="background-color: white;"><strong style="color: black">Supplier Information</strong></p><hr>
                                                    <?php
                                                            $db= new Pdocon;
                                                            $db->query('SELECT * FROM inventory WHERE id=:id');
                                                            $db->bindvalue(':id', $product_id, PDO::PARAM_INT);
                                                            $row = $db->fetchSingle(); 
                                                               if($row) { ?>
                                                            <p style="background-color: white;">
                                                                <strong style="color:black;">Name: </strong> <?php echo $row['productSupplier']; ?>
                                                            </p><hr>
                                                            <p style="background-color: white;">
                                                                <strong style="color:black;">Email: </strong> <?php echo $row['productEmail']; ?>
                                                            </p><hr>
                                                            <p style="background-color: white;">
                                                                <strong style="color:black;">Cost: </strong>$ <?php echo $row['productCost']; ?>
                                                            </p><hr>
                                        </div>
                                        <div class="col-sm-3">
                                           <p><strong></strong></p><br>
                                           <p><strong></strong></p><br>
                                           <p class="pull-right"></p><br>
                                           <p class="pull-right"></p><br>
                                        </div>
                                        <div class="col-sm-6">
                                            <form class="form-horizontal" role="form" method="post" action="msg-supplier.php">
                                                <div class="form-group">
                                                    <label for="name" style="color:black;">Subject<span class="required"></span></label>
                                                    <input type="text" aria-required="true" size="30" value="" name="subject" id="name" class="form-control" placeholder="Subject" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email" style="color:black;">Email<span class="required">*</span></label>
                                                    <input type="email" aria-required="true" size="30" value="<?php echo $row['productEmail']; ?>" name="email" id="email" class="form-control" placeholder="<?php echo $row['productEmail']; ?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-10">
                                                      <input type="hidden" name="product_id" class="form-control" id="product_id" value="<?php echo $product_id; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="message" style="color:black;">Message</label>
                                                    <textarea aria-required="true" rows="8" cols="45" name="message" id="message" class="form-control" placeholder="Type Message Here" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                   <button>
                                                    <input type="submit" value="SEND" id="contact_form_submit" name="email_submit" class="theme_button"></button>
                                                </div>
                                             </form>
                                        </div>  
                                        <?php } ?>
                                </div>  
                            </section>  
                      </div>
                </div>
    </div>
</div>
<?php  
        if(isset($_POST['email_submit'])){
                 /* Get product Email. */
                 $product_id= $_POST['product_id']; 
                 $db->query('SELECT * FROM inventory WHERE id=:id');
                 $db->bindvalue(':id', $product_id, PDO::PARAM_STR); 
                 $row = $db->fetchSingle();
                 if($row){
                     $manufacturer_name = $row['productSupplier']; 
                     $raw_subject = cleandata($_POST['subject']);
                     $clean_subject = sanitizer($raw_subject);  
                     $raw_message = cleandata($_POST['message']);
                     $clean_message = sanitizer($raw_message);
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
?> 
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