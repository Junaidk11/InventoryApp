<?php include('includes/header.php'); ?>

<?php

//Include functions file, i.e. The Helper functions 

//check to see if user if logged in else redirect to index page

/* 
    Use the 'user_is_logged_in' session to check if the user is logged in or not. 
    
    the 'user_is_logged_in' session is set to TRUE, if user has logged in at the index.php page,
    
    if 'user_is_logged_in' session is not set, then the user is always redirect to the index.php page.
*/


    if(!($_SESSION['user_is_logged_in'])) // if user_is_logged_in is not set
    {
        
        header("Location: ../index.php"); 
        //redirect('../index.php'); // Redirect to the index.php page, first you need to get out of this page, because index.php is not in the admin folder. 
    }
?>



<?php 
/************** Fetching data from database using id ******************/

//require database class files - include the connection file for connection between PHP and the database. 
require('includes/pdocon.php');

//instatiating our database objects
$db = new Pdocon; // create the connection between the php file and the database, to have access to the database information 

//Create a query to select all users to display in the table
 $db->query("SELECT * FROM admin WHERE email=:email");

// Now bind the :email to the email of the user logged in right now, which can be accessed from the session created when the user logged in, the session 'user_data'.

$db->bindvalue(':email', $_SESSION['user_data']['email'],PDO::PARAM_STR);
   
//Fetch all data and keep in a result set
$row = $db->fetchSingle(); // this function will execute the prepared query above, and return admin table row where email = email of current logged in user. 

// Next, we will use the fetched data and display it on the admin's account page using HTML code below. 

?>


<!-- Email notification script here 
<script>
    
    $(document).ready(function(){
        
        display_email_supplier();
        
        setInterval(function(){ display_email_supplier()}, 4000); 
        function display_email_supplier(){ 
            $.get("ajax_email_supplier.php",function(show_email){$("#emailnotification").html(show_email)})
        
    }        
    });
    
    
</script>
-->


<!-- 
      The HTML code below is for displaying the contents on the admin page, after he/she has successfully logged in. 
-->

  <div class="well">
  <small class="pull-right"><a href="inventory.php"> View Inventory</a></small>
  
  <small class="center" style="width:200px; height:200px;" ><a href="users.php"> View Users</a></small>
  <?php 
      /*
      Collect the admin's name and put it in there using the session super global
      
    Using the super global variable of $_SESSION, access the current logged in user's information from session 'user_data', that was created when the user logged in. 
     */
      
    $fullname = $_SESSION['user_data']['fullname']; 
    
    echo '<small class="pull-left" style="color:#337ab7; width:1700px; height:1px;">'.$fullname.' | Veiwing/Editing </small>';
?>
    <h2 class="text-center">Account Information</h2> <hr>
   </div>
   
<div class="container"> 
  
  <div class = "row" id="emailnotification">
           <!-- Place Your email notification here -->
   </div>
   
   <div class="rows">
     
      <?php     /* call your display function to display session message on top page*/ 
            showmsg(); 
       
       /* 
       This displays message at top of the page, below the header, whenever a new message is set on this page, as the function definition in the functions.php file waits until the session 'msg' is set. 
       
       Hence, you only need to call the showmsg() helper function  once in your php file, inside a php tag. Following this, whenever a message in set in the session 'msg', the showmsg() will echo the message on the page and unset the session. 
       
       
       */
       ?>
      
     <div class="col-md-9">
         
          <?php  
             // loop through your row set and fill in the form by echoing the at each fieldname the corresponding data from the fetched row
         
            if($row) // i.e. check if something was returned by the fetching calling
            {  // Open the if block in PHP, and close PHP tag, to allow the HTMl code for the form fields to be generated. This will allow echoing at the respective form field using PHP tag again. 
                
         ?>
          
          <br>
           <form class="form-horizontal" role="form" method="post" action="my_admin.php">
            <div class="form-group">
            <label class="control-label col-sm-2" for="name" style="color:#f3f3f3;">Fullname:</label>
            <div class="col-sm-10">
              <input type="name" name="name" class="form-control" id="name" value="<?php echo $row['fullname']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="email" style="color:#f3f3f3;">Email:</label>
            <div class="col-sm-10">
              <input type="email" name="email" class="form-control" id="email" value="<?php  echo $row['email']; ?>" required>
            </div>
          </div>
          <div class="form-group ">
            <label class="control-label col-sm-2" for="pwd" style="color:#f3f3f3;">Password:</label>
            <div class="col-sm-10">
             <fieldset disabled> 
              <input type="password" name="password" class="form-control disabled" id="pwd" value="$<?php echo $row['password']; ?>" required>
             </fieldset> 
            </div>
          </div>
     
        <!--
            The following HTML code is for the Edit button on the my_admin.php page, when you click on the edit button, you get redirected to the edit_admin.php file AND you also get the admin's 'id' number in the url - this will allow you to fetch the particular user's information from the database for you to view and edit
        -->
         <br>
          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
                <a class="btn btn-primary" href="edit_admin.php?admin_id=<?php echo $row['id'] ?>">Edit</a>
                <button type="submit" class="btn btn-danger pull-right" name="delete_form">Delete</button>
            </div>
          </div>
          
          
          
        </form>
          
  </div>
       <div class="col-md-3">
           <div style="padding: 20px;">
             <div class="thumbnail">
              <a href="edit_admin.php?admin_id=<?php echo $row['id'] ?>">
               
                   <?php // Get the image from table and keep in a variable 
                            $image = $row['image'];
                    ?>
               
                <?php //echo  image folder and concatinate it with a style  
                echo '<img src="uploaded_image/'. $image .'"style="width:150px;height:150px">'; ?> 
              </a>
              <h4 class="text-center"><?php //echo fullname of admin
                echo $row['fullname']; ?></h4>
            </div>
           </div>
       </div>
       
       
       <?php } //close if block here ?>
       
  </div>  

</div>


<?php 
  
/************** Deleting data from database when delete button is clicked ******************/  
      
      
//Setting a confirmation message when the delete button is clicked 

if(isset($_POST['delete_form'])) // Check if delete button pressed 
{
    
    $admin_id = $_SESSION['user_data']['id']; // collect the user id from the session of the user, this 'id' will be used to prepare a query to delete the acccount, if he/she decides to delete the account. 
    
    $message = '<div class="alert alert-danger text-center">
  <strong>Please confirm!</strong><br>Do you want to delete your account?<br>
  <a href="#" class="btn btn-default" data-dismiss="alert" aria-label="close">No</a>
  <br>
  
  <form  method="post" action="my_admin.php">
  <input type="hidden" value="'.$admin_id.'"name="id"><br>
  <input type="submit" name="delete_admin" value="Yes" class="btn btn-danger">
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
if(isset($_POST['delete_admin']))//name of the submit button
{
     // get the id from the url
    $delete_id = $_POST['id'];
    
    
    //write your query
    $db->query('DELETE FROM admin WHERE id=:id');
     
     
    //binding values with your  url id variable
     $db->bindvalue(':id', $delete_id, PDO::PARAM_INT);
   
     
    //Execute query statement to send it into the database
    $run_query = $db->execute();
   
     
    //Confirm execution and display a delete success message and redirect admin to index page
    
    if($run_query)
    {
        $message = '<div class="alert alert-success text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Delete successful!</strong> Please register a new admin!</div>'; 
        
        keepmsg($message);

        redirect('logout.php'); // The logout.php redirects the user to the index.php page, but first it deletes the session.
    
    }
    else 
    {
        $message = '<div class="alert alert-danger text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Delete unsuccessful!</strong> User not deleted. </div>'; 
        
    }
}

?>


<?php include('includes/footer.php'); ?>