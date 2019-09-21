<?php include('includes/header.php'); ?>
<?php
/*********************************************************** Add new product to the inventory ***************************************************************/
/*Instantiate database connection.*/
require('includes/pdocon.php');
$db = new Pdocon;

/* Collect & clean values from the form. */
if(isset($_POST['submit_product']))
{
    /*  Validate and Sanitize data submitted. */
    $raw_name = cleandata($_POST['name']);
    $raw_description = cleandata($_POST['description']);
    $raw_supplier = cleandata($_POST['suppliername']);
    $raw_email = cleandata($_POST['supplieremail']);
    $raw_cost  = cleandata($_POST['productcost']);
    $raw_quantity = cleandata($_POST['quantity']);
    $raw_threshold = cleandata($_POST['productminreq']);
    
    $clean_name = sanitizer($raw_name);
    $clean_description = sanitizer($raw_description);
    $clean_supplier = sanitizer($raw_supplier);
    $clean_email = validateemail($raw_email);
    $clean_cost = validateint($raw_cost);
    $clean_quantity = validateint($raw_quantity);
    $clean_threshold =  validateint($raw_threshold); 
    
    /* 
        First Collect Image, using the $_FILES super global
        $imagestorage = FILES['whereTheimageisComingFrom']['filename'];
    */
    $collectedImage = $_FILES['image']['name'];
    /* 
    The collectedImage needs to be saved temporarily in another variable, to be able to move the Image to a permanent location - before being uploaded to the server. Note: We don't upload the image physically, we only pass the file pathname and PHP will use that file for displaying, whenever requested. 
    */
    $collectedImage_temp = $_FILES['image']['tmp_name']; 
    /* This what happens:
    
        Once, the submit button is pressed, FILES super global variable will collect the selected image which has a filename of 'name' and will store it in field 'image' of its associative array. Next, a copy of the submitted image will be made as shown in the $collectedImage_temp.
        
        Following form validation, the submitted image will be moved to the permanent location using a PHP move function as shown below.
    
        the move_uploaded_file() is the helper php function that moves the uploaded file to its permanent folder. 
    
    */
    move_uploaded_file($collectedImage_temp, "uploaded_image/$collectedImage");   
    /* Validation and sanitization of submitted data - completed. */
    
    /*Check if Product already exist in database using Product name.*/
    /*Steps: Prepare Query
             Bind Values
             Run query, followed by fetching results. */
    $db->query('SELECT * FROM inventory WHERE productName =:name');
    $db->bindvalue(':name',$clean_name,PDO::PARAM_STR);
    $run_query = $db->fetchSingle(); 
    
    if($run_query){
        /*If query successfully executed, i.e. Product already exist on the database. */
        redirect('inventory.php');
        $message ='<div class="alert alert-Failure text-center">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong>Oops!</strong> The product already exist in the inventory.</div>';
        keepmsg($message);
    }else{
        /* Product doesn't exist. Add Product to database. */
        $db->query("INSERT INTO inventory(id, productName, productDescription, productSupplier, productEmail, productCost, quantity, thresholdQuantity, image) VALUES(NULL,:name,:description, :supplier, :email, :cost, :quantity, :minreq, :image)");
    
        $db->bindvalue(':name',$clean_name, PDO::PARAM_STR);
        $db->bindvalue(':description',$clean_description,PDO::PARAM_STR);
        $db->bindvalue(':supplier',$clean_supplier, PDO::PARAM_STR);
        $db->bindvalue(':email',$clean_email, PDO::PARAM_STR);
        $db->bindvalue(':cost',$clean_cost, PDO::PARAM_INT);
        $db->bindvalue(':quantity',$clean_quantity,PDO::PARAM_INT);
        $db->bindvalue(':minreq',$clean_threshold, PDO::PARAM_INT);
        $db->bindvalue(':image',$collectedImage,PDO::PARAM_STR);
        $run_query = $db->execute();
    
        if($run_query){
            /* Registration successful. */
            redirect('inventory.php');         
            $message='<div class="alert alert-Success text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong> Product registration successful!</strong></div>';
            keepmsg($message);        
        }else{
             /* Registeration failed. */
            redirect('inventory.php');      
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
          <p class="pull-right" style="color:#777"> Adding Product to Inventory</p><br>
      </div>
      <div class="col-md-4 col-md-offset-4">
          
           <form class="form-horizontal" role="form" method="post" action="<?php $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data" >
            <div class="form-group">
            <label class="control-label col-sm-2" for="name" style="color:#f3f3f3;">Name</label>
            <div class="col-sm-10">
              <input type="name" name="name" class="form-control" id="name" placeholder="Enter Product Name" required>
            </div>
          </div>
            <div class="form-group">
            <label class="control-label col-sm-2" for="description" style="color:#f3f3f3;">Description</label>
            <div class="col-sm-10">
              <input type="text" name="description" class="form-control" id="description" placeholder="Enter description" required>
            </div>
          </div>
          
             <div class="form-group">
            <label class="control-label col-sm-2" for="suppliername" style="color:#f3f3f3;">Supplier</label>
            <div class="col-sm-10">
              <input type="text" name="suppliername" class="form-control" id="suppliername" placeholder="Enter supplier name" required>
            </div>
          </div>
          
           <div class="form-group">
            <label class="control-label col-sm-2" for="supplieremail" style="color:#f3f3f3;">Email</label>
            <div class="col-sm-10">
              <input type="email" name="supplieremail" class="form-control" id="supplieremail" placeholder="Enter supplier email" required>
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-2" for="productcost" style="color:#f3f3f3;">Cost</label>
            <div class="col-sm-10">
              <input type="number" name="productcost" class="form-control" id="productcost" placeholder="Enter product cost" required>
            </div>
          </div>
           <div class="form-group">
            <label class="control-label col-sm-2" for="quantity" style="color:#f3f3f3;">Quantity</label>
            <div class="col-sm-10">
              <input type="text" name="quantity" class="form-control" id="quantity" placeholder="Enter quantity" required>
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-2" for="productminreq" style="color:#f3f3f3;">Required</label>
            <div class="col-sm-10">
              <input type="number" name="productminreq" class="form-control" id="productminreq" placeholder="Enter minimum required in-stock" required>
            </div>
          </div>
                
           <div class="form-group">
            <label class="control-label col-sm-2" for="image" style="color:#f3f3f3;"></label>
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