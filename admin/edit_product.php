<?php include('includes/header.php'); ?>
<div class="well"> 
  <small class="pull-right"><a href="inventory.php"> View Inventory</a></small>
   <?php echo '<small class="pull-left" style="color:#337ab7;">'. $_SESSION['user_data']['fullname'].' | Editing </small>'; ?>
   <h2 class="text-center">Product Information</h2>
</div> 
<div class="container">
        <div class="rows">
            <!--  Display any messages that are set in the session 'msg'-->
            <?php showmsg(); ?>
            <div class="col-md-6 col-md-offset-3"> 
                   <form class="form-horizontal" role="form" method="post" action="edit_product.php" enctype="multipart/form-data">
                     <!-- ************** Fetching data from database using id ****************** -->
                     <?php   if(isset($_GET['product_id'])){ // Ensure id is sent. 
                                $product_id = $_GET['product_id'];
                                /* Use the product_id and fetch and display product information from the database. */
                                require('includes/pdocon.php');
                                $db= new Pdocon; 
                                $db->query("SELECT * FROM inventory WHERE id=:id");
                                $db->bindvalue(':id',$product_id, PDO::PARAM_INT);  
                                $result = $db->fetchSingle(); 
                                if ($result) {?>
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
                              <input type="email" name="supplieremail" class="form-control" id="supplieremail" value="<?php echo $result['productEmail']; ?>">
                            </div>
                       </div>
                       <div class="form-group">
                            <label class="control-label col-sm-2" for="productcost" style="color:#f3f3f3;">Cost</label>
                            <div class="col-sm-10">
                              <input type="number" name="productcost" class="form-control" id="productcost" value="<?php echo $result['productCost']; ?>">
                            </div>
                       </div>
                       <div class="form-group">
                            <label class="control-label col-sm-2" for="quantity" style="color:#f3f3f3;">Quantity</label>
                            <div class="col-sm-10">
                              <input type="number" name="quantity" class="form-control" id="quantity" value="<?php echo $result['quantity']; ?>" required>
                            </div>
                       </div>
                       <div class="form-group">
                            <label class="control-label col-sm-2" for="quantity" style="color:#f3f3f3;">Link</label>
                            <div class="col-sm-10">
                              <input type="text" name="website" class="form-control" id="website" value="<?php echo $result['link']; ?>">
                            </div>
                       </div>
                       <div class="form-group">
                            <label class="control-label col-sm-2" for="productminreq" style="color:#f3f3f3;">Minimum Required</label>
                            <div class="col-sm-10">
                              <input type="number" name="productminreq" class="form-control" id="productminreq" value="<?php echo $result['thresholdQuantity']; ?>" required>
                            </div>
                       </div>
                       
                      <div class="form-group">
                          <div class="col-sm-10">
                               <input type="hidden" name="product_id" class="form-control" id="product_id" value="<?php echo $result['id'];?>" required>
                          </div>
                      </div>
                          
                       <div class="form-group">
                            <label class="control-label col-sm-2" for="image"></label>
                            <div class="col-sm-10">
                              <input type="file" name="image" id="image" placeholder="Choose Image" value="<?php echo $result['image']; ?>">
                            </div>
                       </div>
                       <div class="form-group"> 
                            <div class="col-sm-offset-2 col-sm-10">
                              <input type="submit" class="btn btn-primary" name="update_form_pressed" value="Update">
                              <input type="submit" class="btn btn-danger pull-right" name="delete_form_pressed" value="Delete">
                            </div>
                       </div>
                   </form>
           </div>
            <div class="col-md-3">
               <div style="padding: 40px;">
                     <div class="thumbnail">
                      <a href="edit_product.php?product_id=<?php echo $result['id']; ?>">
                        <?php /* Get the image from table and keep in a variable.*/  $image = $result['image'];  ?>
                        <?php /*echo  image folder and concatinate it with a style.*/ echo '<img src="uploaded_image/'. $image .'"style="width:150px;height:150px">'; ?> 
                      </a>
                    </div>
               </div>
           </div>
            <?php } } ?>
        </div>
</div>     
<?php 
    /****************************************************** Update/Delete product information **************************************************************/ 

    if(isset($_POST['update_form_pressed'])){
    /* 
        Trim the data submitted through the form. 
        Validate and sanitize the cleaned data. 
        Prepare query for database. 
        Bind form data to the query. 
        Send Query to the database. */
        if(empty($_FILES['image']['name'])){
     // No file selected for upload. 
              
            $raw_name = cleandata($_POST['name']);
            $raw_description = cleandata($_POST['description']);
            $raw_supplier = cleandata($_POST['suppliername']);
            $raw_email = cleandata($_POST['supplieremail']);
            $raw_cost  = cleandata($_POST['productcost']);
            $raw_quantity = cleandata($_POST['quantity']);
            $raw_link = cleandata($_POST['website']);
            $raw_threshold = cleandata($_POST['productminreq']);

            $clean_name = sanitizer($raw_name);
            $clean_description = sanitizer($raw_description);
            $clean_supplier = sanitizer($raw_supplier);
            $clean_email = validateemail($raw_email);
            $clean_cost = validateint($raw_cost);
            $clean_quantity = validateint($raw_quantity);
            $clean_link = sanitizer($raw_link);
            $clean_threshold =  validateint($raw_threshold); 

            /* This what happens:

                Once, the submit button is pressed, FILES super global variable will collect the selected image which has a filename of 'name' and will store it in field 'image' of its associative array. 
                Next, a copy of the submitted image will be made as shown in the $collectedImage_temp.
                Next, following form validation, the submitted image will be moved to the permanent location using a PHP move function as shown below.
                The move_uploaded_file() is the helper php function that moves the uploaded file to its permanent folder. 
            */
            $collectedImage = $_FILES['image']['name'];
            $collectedImage_temp = $_FILES['image']['tmp_name'];
            move_uploaded_file($collectedImage_temp, "uploaded_image/$collectedImage"); 

            require('includes/pdocon.php');  
            $db = new Pdocon;
            $db->query("UPDATE inventory SET productName=:name, productDescription=:description, productSupplier=:supplier, productEmail=:email, productCost=:cost, quantity=:quantity, link=:link, thresholdQuantity=:minreq WHERE id=:id");
            $db->bindvalue(':id',$_POST['product_id'], PDO::PARAM_INT);
            $db->bindvalue(':name',$clean_name, PDO::PARAM_STR);
            $db->bindvalue(':description',$clean_description,PDO::PARAM_STR);
            $db->bindvalue(':supplier',$clean_supplier, PDO::PARAM_STR);
            $db->bindvalue(':email',$clean_email, PDO::PARAM_STR);
            $db->bindvalue(':cost',$clean_cost, PDO::PARAM_INT);
            $db->bindvalue(':quantity',$clean_quantity, PDO::PARAM_INT);
            $db->bindvalue(':link',$clean_link, PDO::PARAM_STR);
            //$db->bindvalue(':image', $collectedImage, PDO::PARAM_STR);  
            $db->bindvalue(':minreq',$clean_threshold, PDO::PARAM_INT);
            $run_query = $db->execute(); 
            if($run_query) {
                    /* 
                   Product information successfully updated.
                   Set a success message. 
                   Redirect user to a new page. 
                   */
                    redirect('inventory.php');
                    $message ='<div class="alert alert-Success text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong> Product updated successfully! </strong></div>';
                    keepmsg($message);
            }else{
                    redirect('edit_product.php?product_id='.$run_query['id'].'');
                    $message ='<div class="alert alert-Failure text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Oops!</strong> Product update unsuccessful. Try again. </div>';
                    keepmsg($message);  
                }
    }elseif(!(empty($_FILES['image']['name']))){
              
            $raw_name = cleandata($_POST['name']);
            $raw_description = cleandata($_POST['description']);
            $raw_supplier = cleandata($_POST['suppliername']);
            $raw_email = cleandata($_POST['supplieremail']);
            $raw_cost  = cleandata($_POST['productcost']);
            $raw_quantity = cleandata($_POST['quantity']);
            $raw_link = cleandata($_POST['website']);
            $raw_threshold = cleandata($_POST['productminreq']);

            $clean_name = sanitizer($raw_name);
            $clean_description = sanitizer($raw_description);
            $clean_supplier = sanitizer($raw_supplier);
            $clean_email = validateemail($raw_email);
            $clean_cost = validateint($raw_cost);
            $clean_quantity = validateint($raw_quantity);
            $clean_link = sanitizer($raw_link);
            $clean_threshold =  validateint($raw_threshold); 

            /* This what happens:

                Once, the submit button is pressed, FILES super global variable will collect the selected image which has a filename of 'name' and will store it in field 'image' of its associative array. 
                Next, a copy of the submitted image will be made as shown in the $collectedImage_temp.
                Next, following form validation, the submitted image will be moved to the permanent location using a PHP move function as shown below.
                The move_uploaded_file() is the helper php function that moves the uploaded file to its permanent folder. 
            */
            $collectedImage = $_FILES['image']['name'];
            $collectedImage_temp = $_FILES['image']['tmp_name'];
            move_uploaded_file($collectedImage_temp, "uploaded_image/$collectedImage"); 

            require('includes/pdocon.php');  
            $db = new Pdocon;
            $db->query("UPDATE inventory SET productName=:name, productDescription=:description, productSupplier=:supplier, productEmail=:email, productCost=:cost, quantity=:quantity,link=:link, image=:image ,thresholdQuantity=:minreq WHERE id=:id");
            $db->bindvalue(':id',$_POST['product_id'], PDO::PARAM_INT);
            $db->bindvalue(':name',$clean_name, PDO::PARAM_STR);
            $db->bindvalue(':description',$clean_description,PDO::PARAM_STR);
            $db->bindvalue(':supplier',$clean_supplier, PDO::PARAM_STR);
            $db->bindvalue(':email',$clean_email, PDO::PARAM_STR);
            $db->bindvalue(':cost',$clean_cost, PDO::PARAM_INT);
            $db->bindvalue(':quantity',$clean_quantity, PDO::PARAM_INT);
            $db->bindvalue(':link', $clean_link, PDO::PARAM_STR);
            $db->bindvalue(':image', $collectedImage, PDO::PARAM_STR);  
            $db->bindvalue(':minreq',$clean_threshold, PDO::PARAM_INT);
            $run_query = $db->execute(); 
            if($run_query) {
                    /* 
                   Product information successfully updated.
                   Set a success message. 
                   Redirect user to a new page. 
                   */
                    redirect('inventory.php');
                    $message ='<div class="alert alert-Success text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong> Product updated successfully! </strong></div>';
                    keepmsg($message);
            }else{
                    redirect('edit_product.php?product_id='.$run_query['id'].'');
                    $message ='<div class="alert alert-Failure text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Oops!</strong> Product update unsuccessful. Try again. </div>';
                    keepmsg($message);  
                }
            
        }

}elseif(isset($_POST['delete_form_pressed'])){ 
        /*
          Delete button pressed.
          Confirm to continue with deleting the product. 
          */  
            redirect('edit_product.php?product_id='.$_POST['product_id'].'');
            $message = '<div class="alert alert-danger text-center">
          <strong>Confirmation</strong><br> Are you user you want to delete this product?<br>
          <a href="#" class="btn btn-default" data-dismiss="alert" aria-label="close">No</a>
          <br>

          <form  method="post" action="edit_product.php?product_id="'.$_POST['product_id'].'>
          <input type="hidden" value="'.$_POST['product_id'].'"name="product_id"><br>
          <input type="submit" name="delete_product" value="Yes" class="btn btn-danger">
          </form>
          </div>';
           keepmsg($message);
    }
    /* The delete is confirmed. */
    if(isset($_POST['delete_product'])){
         /*
           Grab the product id from the submitted form. 
           Prepare Delete Query. 
           Bind product id to the query. 
           Send query to the database. 
         */
        $delete_id = $_POST['product_id'];
        $db->query('DELETE FROM inventory WHERE id=:id');
        $db->bindvalue(':id', $delete_id, PDO::PARAM_INT);
        $run_query = $db->execute();
        if($run_query){
             /* Delete Successful. */
            redirect('inventory.php'); 
            $message = '<div class="alert alert-success text-center">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Product deleted successfully!</strong></div>'; 
            keepmsg($message);       
        }else{
            redirect('edit_product.php?product_id='.$delete_id.'');
            $message = '<div class="alert alert-danger text-center">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Oops!</strong> Delete unsuccessful. Try again. </div>'; 
            keepmsg($message);
        }
}
?>    
<?php include('includes/footer.php'); ?>