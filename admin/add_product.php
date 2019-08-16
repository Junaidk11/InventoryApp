<?php include('includes/header.php'); ?>


<?php

/*Include functions

/* 
 
    You don't need to include your helper functions because they're already included in the admin's header file, which we have included at the start. 

*/

//check to see if user if logged in else redirect to index page - the header.php of the admin takes care of that. It ensures if the admin is not logged, redirect logout.php file - where the user gets redirected to the index.php and session is cleared. 

?>
 
 
<?php
/************** Register new customer ******************/


//require database class files
require('includes/pdocon.php');

//instatiating our database objects
$db = new Pdocon;

//Collect and clean values from the form
if(isset($_POST['submit_product'])) // The register button is pressed by the user
{
    // Use the helper function to trim the data submitted through the form
    
    $raw_name = cleandata($_POST['name']);
    $raw_description = cleandata($_POST['description']);
    $raw_quantity = cleandata($_POST['quantity']);
    
    // Validate and sanitize the cleaned data
    $clean_name = sanitizer($raw_name);
    $clean_description = sanitizer($raw_description);
    $clean_quantity = validateint($raw_quantity);
    
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
    
    
    //Check and see if user already exist in database using email so write query and bind email
    
        /*  Prepare a query first*/
    $db->query('SELECT * FROM inventory WHERE productName =:name');
    $db->bindvalue(':name',$clean_name,PDO::PARAM_STR);
    
    
    //Call function to count row, fetch the result if the user is already in the database
    $run_query = $db->fetchSingle(); 
    
    
    if($run_query) // If query successfully executed, i.e. user found in system. 
    {
        
        //Display error if customer exist 
        
        //Echo a danger <div> that will alert the user that the user exist. 
        
        redirect('inventory.php');
        
        $message ='<div class="alert alert-Failure text-center">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong>Sorry!</strong> The product already exist in the inventory.</div>';
        
        keepmsg($message); 
     
    }
    else
    {
        //Write query to insert values, bind values
        
        // The user doesn't exist, so we prepare a query, copy the user to the database. 
        
        $db->query("INSERT INTO inventory(id, productName, productDescription, quantity, image) VALUES(NULL,:name,:description,:quantity,:image)");
        
        // Bind the values 
        $db->bindvalue(':name',$clean_name, PDO::PARAM_STR);
        $db->bindvalue(':description',$clean_description,PDO::PARAM_STR);
        $db->bindvalue(':quantity',$clean_quantity,PDO::PARAM_INT);
        $db->bindvalue(':image',$collectedImage,PDO::PARAM_STR);
        
    
        // execute the query and store result if the addition was successful or not. 
    
        $run_query = $db->execute();
        
        if($run_query) // Check if the registration was a success 
        {
            // If registration successful, echo <div> stating a success
            
             
            redirect('inventory.php');         
            $message='<div class="alert alert-Success text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong> Product registration successful</strong></div>';
            
            keepmsg($message); 
                  
        }
        else
        {
            redirect('inventory.php');      
            // If the registeration failed 
            $message= '<div class="alert alert-Failure text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Oops!</strong> Product registeration unsuccessful. Please try again. </div>'; 
            keepmsg($message); 
            
        }    
 
    }
    
}
?>

    <div class="row">
      <div class="col-md-4 col-md-offset-4">
          <p class="pull-right" style="color:#777"> Adding Product to Database</p><br>
      </div>
      <div class="col-md-4 col-md-offset-4">
          
           <form class="form-horizontal" role="form" method="post" action="<?php $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data" >
            <div class="form-group">
            <label class="control-label col-sm-2" for="name"></label>
            <div class="col-sm-10">
              <input type="name" name="name" class="form-control" id="name" placeholder="Enter Product Name" required>
            </div>
          </div>
            <div class="form-group">
            <label class="control-label col-sm-2" for="description"></label>
            <div class="col-sm-10">
              <input type="text" name="description" class="form-control" id="description" placeholder="Enter Description" required>
            </div>
          </div>
           <div class="form-group">
            <label class="control-label col-sm-2" for="quantity"></label>
            <div class="col-sm-10">
              <input type="text" name="quantity" class="form-control" id="quantity" placeholder="Enter Quantity" required>
            </div>
          </div>
           <div class="form-group">
            <label class="control-label col-sm-2" for="image"></label>
            <div class="col-sm-10">
              <input type="file" name="image" id="image" placeholder="Choose Image" required>
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10 text-center">
              <button type="submit" class="btn btn-primary pull-right" name="submit_product">Add Product</button>
              <a class="pull-left btn btn-danger" href="inventory.php">Cancel</a>
            </div>
          </div>
</form>
          
  </div>
</div>
  
<?php include('includes/footer.php'); ?>  