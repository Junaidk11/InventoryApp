<?php include('includes/header.php'); $fullname = $_SESSION['user_data']['fullname']; ?>
<!-- ************************************************** Fetching Admin Information************************************************  -->
<?php 
        /* Instantiate connection to the database. 
           Prepare query to fetch user information from the database. 
*/
        require('includes/pdocon.php');
        $db = new Pdocon; 
        $db->query("SELECT * FROM admin WHERE email=:email");
        $db->bindvalue(':email', $_SESSION['user_data']['email'],PDO::PARAM_STR);
        $row = $db->fetchSingle(); 
?>

<div class ="page-header">
    
      <div class ="pull-right" style="color: white;border-bottom:white;">
          <a href="inventory.php"><h2 class="pull-right" style="color: white;"> Inventory</h2></a>
      </div>
      <h2 class="text-center" style="color: white;"> Account Information </h2>
</div>
<?php  showmsg(); ?>
<div class="container">
   <?php if($row){  ?>
     <div class="rows">
              <div class="text-center">
                <a href="edit_admin.php?admin_id=<?php echo $row['id']; ?>">
                     <?php   $image = $row['image'];  ?>
                  <?php  echo '<img src="uploaded_image/'. $image .'"style="width:500px;height:500px" class="img-square">'; ?> 
                </a>
              </div>
               <h4 class="text-center" style="color: white;"><?php echo $row['fullname']; ?></h4>
    </div>
    <div class="rows">
         <div class="text-center">
               <form class="form-inline" role="form" method="post" action="my_admin.php">
                  <div class="form-group">
                    <label class="control-label" for="name" style="color:#f3f3f3;">Name: </label>
                      <input type="name" name="name" class="form-control" id="name" value="<?php echo $row['fullname']; ?>" required>
                  </div><br><br>

                  <div class="form-group">
                    <label class="control-label" for="email" style="color:#f3f3f3;">Email:</label>
                      <input type="email" name="email" class="form-control" id="email" value="<?php  echo $row['email']; ?>" required>
                  </div><br><br>
                  <button type="submit" class="btn btn-danger col-md-3 pull-left" name="delete_admin"> Delete Account </button>
                  <a type="edit" name ="edit" class="btn btn-primary col-md-3 pull-right" href="edit_admin.php?admin_id=<?php echo $row['id'] ?>">Edit Account Information</a>
                </form>
         </div>
           <?php } ?>
      </div>  
</div>

<?php 
/************** Deleting data from database when delete button is clicked ******************/  
if(isset($_POST['delete_admin'])) {
    $admin_id = $_SESSION['user_data']['id']; 
    redirect('my_admin.php');
    $message = '<div class="alert alert-danger alert-dismissible fade in text-center">
  <strong>Please confirm!</strong><br>Do you want to delete your account?<br>
  <a href="#" class="btn btn-default" data-dismiss="alert" aria-label="close">No</a>
  <br>
  
  <form  method="post" action="my_admin.php">
  <input type="hidden" value="'.$admin_id.'"name="admin_id"><br>
  <input type="submit" name="delete_admin_confirmed" value="Yes" class="btn btn-danger">
  </form>
  </div>'; 
    keepmsg($message);
}elseif(isset($_POST['delete_admin_confirmed'])){
    $delete_id = $_POST['admin_id'];
    $db->query('DELETE FROM admin WHERE id=:id');
    $db->bindvalue(':id', $delete_id, PDO::PARAM_INT);
    $run_query = $db->execute();
    if($run_query){
        /* User deleted successfully. */
        $message = '<div class="alert alert-success alert-dismissible fade in text-center" style="background-color:white;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>User deleted successfully.</strong> Login required. </div>'; 
        keepmsg($message);
        redirect('logout.php'); 
    }else{
        /* Delete unsuccessful. */
        $message = '<div class="alert alert-danger alert-dismissible fade in text-center" style="background-color:white;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Delete unsuccessful.</strong> User not deleted. </div>'; 
    }
}?>
<?php include('includes/footer.php'); ?>