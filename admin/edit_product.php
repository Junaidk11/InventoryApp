<?php include('includes/header.php'); ?>

<?php
/* 
Don't need it, the header.php include takes care of it. 
//Include functions

//check to see if user if logged in else redirect to index page
*/

    if(!($_SESSION['user_is_logged_in'])) // if user_is_logged_in is not set
    {
        redirect('../index.php'); // Redirect to the index.php page, first you need to get out of this page, because index.php is not in the admin folder. 
    }
?>


  <div class="well">
   
  <small class="pull-right"><a href="inventory.php"> View Inventory</a> </small>
 
  <?php //Collect the admin's name and put it in there using the session super global
    
    echo '<small class="pull-left" style="color:#337ab7;">'. $_SESSION['user_data']['fullname'].' | Editing Product</small>';

?>
    
    <h2 class="text-center">Product Information</h2>
    <br>
   </div> 
   
   
   
   <div class="container">
<div class="rows">
    <?php // call your display function to display session message on top page 
       
       showmsg(); // Any message you wish to be displayed on this page, you can set the message in the keepmsg(), which stores the message in a session. As soon as the session 'msg' is set, the showmsg() will display it. 
       
       ?>
     <div class="col-md-6 col-md-offset-3"> 
          <br>
           <form class="form-horizontal" role="form" method="post" action="<?php $_SERVER["PHP_SELF"];?>" enctype="multipart/form-data">
           
   <?php 
    /************** Fetching data from database using id ******************/

        if(isset($_GET['product_id'])) // Ensure id is sent. 
        {
            $product_id = $_GET['product_id'];
        }

       
               
        //require database class files
        require('includes/pdocon.php');

        //instatiating our database objects
        $db=new Pdocon;


        //Create a query to display customer inf // You must bind the id coming in from the url
        $db->query("SELECT * FROM inventory WHERE id=:id");

        //Get the id and keep it in a variable - using the $_GET super global variable

        //Bind your id
        $db->bindvalue(':id',$product_id, PDO::PARAM_INT);  

        //Fetching the data
        $result = $db->fetchSingle(); // fetchSingle has execute() called inside it
    ?>
           
           
    <!--
                
                Fetching the data and display it in the form value fields
                
    -->
           
    <?php  // Display result in the form values:
         
             if ($result) {  // Only display result, if the query was successful.

    ?>
        <div class="form-group">
            <label class="control-label col-sm-2" for="name" style="color:#f3f3f3;">Product</label>
            <div class="col-sm-10">
              <input type="name" name="name" class="form-control" id="name" value="<?php echo $result['productName']; ?>" required>
            </div>
          </div>
           
          <div class="form-group">
            <label class="control-label col-sm-2" for="description" style="color:#f3f3f3;">Description</label>
            <div class="col-sm-10">
              <input type="text" name="description" class="form-control" id="description" value="<?php echo $result['productDescription']; ?>" required>
            </div>
          </div>
           
            <div class="form-group">
            <label class="control-label col-sm-2" for="suppliername" style="color:#f3f3f3;">Supplier</label>
            <div class="col-sm-10">
              <input type="text" name="suppliername" class="form-control" id="suppliername" value="<?php echo $result['productSupplier']; ?>" required>
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-2" for="supplieremail" style="color:#f3f3f3;">Email</label>
            <div class="col-sm-10">
              <input type="email" name="supplieremail" class="form-control" id="supplieremail" value="<?php echo $result['productEmail']; ?>" required>
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-2" for="productcost" style="color:#f3f3f3;">Cost</label>
            <div class="col-sm-10">
              <input type="number" name="productcost" class="form-control" id="productcost" value="<?php echo $result['productCost']; ?>" required>
            </div>
          </div>
          
            <div class="form-group">
            <label class="control-label col-sm-2" for="quantity" style="color:#f3f3f3;">Quantity</label>
            <div class="col-sm-10">
              <input type="number" name="quantity" class="form-control" id="quantity" value="<?php echo $result['quantity']; ?>" required>
            </div>
          </div>
          
           
           <div class="form-group">
            <label class="control-label col-sm-2" for="productminreq" style="color:#f3f3f3;">Minimum Required</label>
            <div class="col-sm-10">
              <input type="number" name="productminreq" class="form-control" id="productminreq" value="<?php echo $result['thresholdQuantity']; ?>" required>
            </div>
          </div>
           
            <div class="form-group">
            <label class="control-label col-sm-2" for="image"></label>
            <div class="col-sm-10">
              <input type="file" name="image" id="image" placeholder="Choose Image" >
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <input type="submit" class="btn btn-primary" name="update_form_pressed" value="Update">
              <button type="submit" class="btn btn-danger pull-right" name="delete_form_pressed">Delete</button>
            </div>
          </div>
          
        </form>
    </div>
        
        <div class="col-md-3">
           <div style="padding: 40px;">
             <div class="thumbnail">
              <a href="edit_product.php?product_id=<?php echo $result['id'] ?>">
                   <?php // Get the image from table and keep in a variable 
                            $image = $result['image'];
                    ?>
                <?php //echo  image folder and concatinate it with a style  
                echo '<img src="uploaded_image/'. $image .'"style="width:150px;height:150px">'; ?> 
              </a>
            </div>
           </div>
       </div>
         
         <?php } //end  
    ?>
         
    </div>
</div>
         
         
         
          
<?php 
    /************** Update data to database using id ******************/  

    if(isset($_POST['update_form_pressed'])) // is the update button pressed?
    {
    // Use the helper function to trim the data submitted through the form
    
    $raw_name = cleandata($_POST['name']);
    $raw_description = cleandata($_POST['description']);
    $raw_supplier = cleandata($_POST['suppliername']);
    $raw_email = cleandata($_POST['supplieremail']);
    $raw_cost  = cleandata($_POST['productcost']);
    $raw_quantity = cleandata($_POST['quantity']);
    $raw_threshold = cleandata($_POST['productminreq']);
    
    // Validate and sanitize the cleaned data
    $clean_name = sanitizer($raw_name);
    $clean_description = sanitizer($raw_description);
    $clean_supplier = sanitizer($raw_supplier);
    $clean_email = validateemail($raw_email);
    $clean_cost = validateint($raw_cost);
    $clean_quantity = validateint($raw_quantity);
    $clean_threshold =  validateint($raw_threshold); 
    
     // Image adding steps:
    
    /* First Collect Image, using the $_FILES super global
        $imagestorage = FILES['whereTheimageisComingFrom']['filename'];
    */
    
    $collectedImage = $_FILES['image']['name'];
    
    /* 
    The collectedImage needs to be saved temporarily in another variable, to be able to move the Image to a permanent location, i.e. before being uploaded to the server. Note: We don't upload the image physically, we only pass the file pathname and PHP will use that file for displaying, whenever requested. 
    */
    
    $collectedImage_temp = $_FILES['image']['tmp_name'];
    
    /* This what happens:
    
        Once, the submit button is pressed, FILES super global variable will collect the selected image which has a filename of 'name' and will store it in field 'image' of its associative array. Next, a copy of the submitted image will be made as shown in the $collectedImage_temp.
        
        Next, after form validation, the submitted image will be moved to the permanent location using a PHP move function as shown below.
        
        the move_uploaded_file() is the helper php function that moves the uploaded file to its permanent folder. 
    
    */
    
    // move the submitted image to the permanent folder of 'uploaded_image' 
    
     move_uploaded_file($collectedImage_temp, "uploaded_image/$collectedImage"); 
        
        //Write your query

        $db->query("UPDATE inventory SET productName=:name, productDescription=:description, productSupplier=:supplier, productEmail=:email, productCost=:cost, quantity=:quantity, image=:image, thresholdQuantity=:minreq WHERE id=:id");

        //binding values with your variable
        $db->bindvalue(':id',$product_id, PDO::PARAM_INT);
        $db->bindvalue(':name',$clean_name, PDO::PARAM_STR);
        $db->bindvalue(':description',$clean_description,PDO::PARAM_STR);
        $db->bindvalue(':supplier',$clean_supplier, PDO::PARAM_STR);
        $db->bindvalue(':email',$clean_email, PDO::PARAM_STR);
        $db->bindvalue(':cost',$clean_cost, PDO::PARAM_INT);
        $db->bindvalue(':quantity',$clean_quantity, PDO::PARAM_INT);
        $db->bindvalue(':image', $collectedImage, PDO::PARAM_STR);  //Add this when you figure out the image editing option
        $db->bindvalue(':minreq',$clean_threshold, PDO::PARAM_INT);
       

        //Execute query statement to send it into the database
        $run = $db->execute();

        //Confirm execution and set your messages to display as well has redirection and errors

        if($run) // users table successfully updated.
        {
            redirect('inventory.php');

            // set a 'success' message to be displayed on the customers.php page 

            $message ='<div class="alert alert-Success text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> Product update successful! </strong></div>';

            keepmsg($message);

        }
        else
        {
            redirect('inventory.php');
            $message ='<div class="alert alert-Failure text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Oops!</strong> Product update unsuccessful. Try again. </div>';

            keepmsg($message);  
        }

    }


    /************** Delete data from database using id ******************/ 
         
 if(isset($_POST['delete_form_pressed'])) // Check if delete button pressed 
{
 /* 
 
 We don't need the $user_id from the session because when a a customer is selected from the "customers.php" page for editing, the page redirects the user to the 'edit.php' page with the "Selected customer's 'user_id' in the URL which is already collected in this page - at the top using the $_GET super global. Now we can use the collected user_id, to send a danger msg to the user when he/she selects to delete a customer from -> the message is sent by creating a <div>, the button on this <div> is used to confirm the 'delete' command from the user. 
 
 */
     
// $user_id = $_SESSION['user_data']['id']; // collect the user id from the session of the user, this 'id' will be used to prepare a query to delete the acccount, if he/she decides to delete the account. 
     
     
    
    $message = '<div class="alert alert-danger text-center">
  <strong>Confirmation</strong><br> Do you want to delete this product?<br>
  <a href="#" class="btn btn-default" data-dismiss="alert" aria-label="close">No</a>
  <br>
  
  <form  method="post" action="edit_product.php">
  <input type="hidden" value="'.$product_id.'"name="id"><br>
  <input type="submit" name="delete_product" value="Yes" class="btn btn-danger">
  </form>
  </div>'; 
    
    keepmsg($message);
    
    /*
    showmsg(); // this will echo the session named 'msg' and unset it after echoing. 
    
    Don't need to call this, because showmsg() already called at the top of this page and the function is define such that it echoes the message set in the 'msg' session as soon as it is set. 
    
    */
}

// the result must be closable div that has a form with two buttons. one for no and one for yes. The no should close the closable div but the yes should proceed to deleting the admin, must delete the admin with the admin id   

?>

<?php 

//If the Yes Delete (confim delete) button is click from the closable div proceed to delete
         
if(isset($_POST['delete_product']))//name of the submit button in the confirmation block that pops up after the user has decided to delete a customer
{
     // get the id from the url
    $delete_id = $_POST['id'];
    
    
    //write your query
    $db->query('DELETE FROM inventory WHERE id=:id');
     
     
    //binding values with your  url id variable
     $db->bindvalue(':id', $delete_id, PDO::PARAM_INT);
   
     
    //Execute query statement to send it into the database
    $run_query = $db->execute();
   
     
    //Confirm execution and display a delete success message and redirect admin to index page
    
    if($run_query)
    {
         redirect('inventory.php'); // After deleting the desired customer, redirect the user to the customers.php page and display the message set below. 
        
        
        $message = '<div class="alert alert-success text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Product delete successful!</strong></div>'; 
        
        keepmsg($message);
    
    }
    else 
    {
        $message = '<div class="alert alert-danger text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Oops!</strong> Delete unsuccessful. Try again. </div>'; 
        
    }
}

?>        
 
 <!--        
         
         
         
         

    //Setting a confirmation message when the delete button is clicked // the result must be closable div that has a form with two buttons. one for no and one for yes. The no shoule close the closable div but the yes should proceed to deleting the customer, must delete the customer with the customer id         




    //If the Yes Delete (confim delete) button is click from the closable div proceed to delete



        // get the id from the url



        //write your query



        //binding values with your  url id variable



        //Execute query statement to send it into the database


        //Confirm execution and display a delete success message and redirect admin to customers page

-->

<?php include('includes/footer.php'); ?>