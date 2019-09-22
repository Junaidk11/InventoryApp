<?php include('includes/header.php'); ?> 
<?php 
 /************************************************************** Fetching data from database using id **************************************************/
      if(isset($_GET['admin_id'])){
          $admin_id = $_GET['admin_id']; 
          require('includes/pdocon.php');
          $db = new Pdocon; 
          $db->query('SELECT * FROM admin WHERE id=:id');
          $db->bindvalue(':id',$admin_id, PDO::PARAM_INT);
          $row=$db->fetchSingle();
          if($row){
?>
<div class="row">
      <div class="col-md-4 col-md-offset-4">
      
      </div>
      <div class="col-md-4 col-md-offset-4">
        <form class="form-horizontal" role="form" method="POST" action="<?php $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label class="control-label col-sm-2" for="name"></label>
                <div class="col-sm-10">
                  <input type="name" name="name" class="form-control" id="name" value="<?php echo $row['fullname']; ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="email"></label>
                <div class="col-sm-10">
                  <input type="email" name="email" class="form-control" id="email" value="<?php echo $row['email']; ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd"></label>
                <div class="col-sm-10"> 
                  <input type="password" name="password" class="form-control" id="pwd" placeholder="Confirm Password" value="" required>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="image"></label>
                <div class="col-sm-10">
                  <input type="file" name="image" id="image" placeholder="Choose Image" value="">
                </div>
            </div>
            <div class="form-group"> 
                <div class="col-sm-offset-2 col-sm-10 text-center">
                  <button type="submit" class="btn btn-primary pull-right" name="submit_update">Update</button>
                  <a class="pull-left btn btn-danger" href="my_admin.php">Cancel</a>
                </div>
            </div>
         </form>  
    <?php  } } ?>  
      </div>
</div>
          
          
<!--  ******************************************************* Updating Admin ************************************************************************* -->
<?php 
if(isset($_POST['submit_update'])) {
    $raw_name = cleandata($_POST['name']); 
    $raw_email = cleandata($_POST['email']);
    $raw_pwd = cleandata($_POST['password']);
    $clean_name = sanitizer($raw_name);
    $clean_email = validateemail($raw_email);
    $clean_pwd = sanitizer($raw_pwd);
    $hashed_Pass = hashpassword($clean_pwd); 
    $collectedImage = $_FILES['image']['name'];
    $collectedImage_temp = $_FILES['image']['tmp_name'];
    /* This what happens:
    
        Once, the submit button is pressed, FILES super global variable will collect the selected image which has a filename of 'name' and will store it in field 'image' of its associative array. Next, a copy of the submitted image will be made as shown in the $collectedImage_temp.
        
        Next, after form validation, the submitted image will be moved to the permanent location using a PHP move function as shown below.
        
        the move_uploaded_file() is the helper php function that moves the uploaded file to its permanent folder. 
    
    */
    move_uploaded_file($collectedImage_temp, "uploaded_image/$collectedImage");   
    $db->query("UPDATE admin SET fullname=:name, email=:email, password=:pwd, image=:image WHERE id=:id"); 
    $db->bindvalue(':name', $clean_name, PDO::PARAM_STR);
    $db->bindvalue(':email',$clean_email, PDO::PARAM_STR);
    $db->bindvalue(':pwd',$hashed_Pass,PDO::PARAM_STR);
    $db->bindvalue(':image',$collectedImage,PDO::PARAM_STR);
    $db->bindvalue(':id',$admin_id, PDO::PARAM_INT);
    $run = $db->execute();
    if($run){
        redirect('my_admin.php');
        $message ='<div class="alert alert-Success text-center">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong> Admin update successful! </strong></div>';
        keepmsg($message);
    }else{
        redirect('my_admin.php');
        $message ='<div class="alert alert-Failure text-center">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong>Oops!</strong> Admin update unsuccessful. </div>';
        keepmsg($message);
    }    
}  
?>  
<?php include('includes/footer.php'); ?>  