<?php include('includes/header.php'); ?>

<?php
/* 
Don't need it, the header.php include takes care of it. 
//Include functions

//check to see if user if logged in else redirect to index page
*/
?>



  <div class="well">
   
  <small class="pull-right"><a href="users.php"> View Users</a> </small>
 
  <?php //Collect the admin's name and put it in there using the session super global
    
    echo '<small class="pull-left" style="color:#337ab7;">'.$_SESSION['user_data']['fullname'].' | Editing Users</small>';

?>
    
    <h2 class="text-center">User Information</h2>
    <br>
   </div> 
   
    
<div class="rows">
    <?php // call your display function to display session message on top page 
       
       showmsg(); // Any message you wish to be displayed on this page, you can set the message in the keepmsg(), which stores the message in a session. As soon as the session 'msg' is set, the showmsg() will display it. 
       
       ?>
     <div class="col-md-6 col-md-offset-3"> 
          <br>
           <form class="form-horizontal" role="form" method="post" action="<?php $_SERVER["PHP_SELF"];?>">
           
   <?php 
    /************** Fetching data from database using id ******************/

        if(isset($_GET['user_id'])) // Ensure id is sent. 
        {
            $user_id = $_GET['user_id'];
        }

       
               
        //require database class files
        require('includes/pdocon.php');

        //instatiating our database objects
        $db=new Pdocon;


        //Create a query to display customer inf // You must bind the id coming in from the url
        $db->query("SELECT * FROM users WHERE id=:id");

        //Get the id and keep it in a variable - using the $_GET super global variable

        //Bind your id
        $db->bindvalue(':id',$user_id, PDO::PARAM_INT);  

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
            <label class="control-label col-sm-2" for="name" style="color:#f3f3f3;">Name</label>
            <div class="col-sm-10">
              <input type="name" name="name" class="form-control" id="name" value="<?php echo $result['full_name']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-2" for="email" style="color:#f3f3f3;">Email</label>
            <div class="col-sm-10">
              <input type="email" name="email" class="form-control" id="email" value="<?php echo $result['email']; ?>" required>
            </div>
          </div>
          <div class="form-group ">
            <label class="control-label col-sm-2" for="pwd" style="color:#f3f3f3;">Password:</label>
            <div class="col-sm-10">
             <fieldset disabled> 
              <input type="password" name="password" class="form-control disabled" id="pwd" placeholder="Cannot Change Password" required>
             </fieldset> 
            </div>
          </div>

          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <input type="submit" class="btn btn-primary" name="update_form_pressed" value="Update">
              <button type="submit" class="btn btn-danger pull-right" name="delete_form_pressed">Delete</button>
            </div>
          </div>
          
        </form>
         
         <?php } //end  ?>
          
<?php 
    /************** Update data to database using id ******************/  

    if(isset($_POST['update_form_pressed'])) // is the update button pressed?
    {

    //Get field names from from and validate    
        $raw_name  = cleandata($_POST['name']);
        $raw_email = cleandata($_POST['email']);

        // Validate and sanitize the submitted information 

        $clean_name = sanitizer($raw_name);
        $clean_email = validateemail($raw_email);

        //Write your query

        $db->query("UPDATE users SET full_name=:name, email=:email WHERE id=:id");

        //binding values with your variable
        $db->bindvalue(':id',$user_id, PDO::PARAM_INT);
        $db->bindvalue(':name',$clean_name, PDO::PARAM_STR);
        $db->bindvalue(':email',$clean_email,PDO::PARAM_STR);

        //Execute query statement to send it into the database
        $run_query = $db->execute();

        //Confirm execution and set your messages to display as well has redirection and errors

        if($run_query) // users table successfully updated.
        {
            redirect('users.php');

            // set a 'success' message to be displayed on the customers.php page 

            $message ='<div class="alert alert-Success text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Congrats!</strong> User update successful! </div>';

            keepmsg($message);

        }
        else
        {
            redirect('users.php');
            $message ='<div class="alert alert-Failure text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Sorry!</strong> User update unsuccessful. Try again. </div>';

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
  <strong> Please Confirm </strong> Do you want to delete delete this user?<br>
  <a href="#" class="btn btn-default" data-dismiss="alert" aria-label="close">No</a>
  <br>
  
  <form  method="post" action="edit_user.php">
  <input type="hidden" value="'.$user_id.'"name="id"><br>
  <input type="submit" name="delete_user" value="Yes" class="btn btn-danger">
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
         
if(isset($_POST['delete_user']))//name of the submit button in the confirmation block that pops up after the user has decided to delete a customer
{
     // get the id from the url
    $delete_id = $_POST['id'];
    
    
    //write your query
    $db->query('DELETE FROM users WHERE id=:id');
     
     
    //binding values with your  url id variable
     $db->bindvalue(':id', $delete_id, PDO::PARAM_INT);
   
     
    //Execute query statement to send it into the database
    $run_query = $db->execute();
   
     
    //Confirm execution and display a delete success message and redirect admin to index page
    
    if($run_query)
    {
         redirect('users.php'); // After deleting the desired customer, redirect the user to the customers.php page and display the message set below. 
        
        
        $message = '<div class="alert alert-success text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Customer delete successful!</strong></div>'; 
        
        keepmsg($message);

       
    
    }
    else 
    {
        $message = '<div class="alert alert-danger text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Delete unsuccessful!</strong> User not deleted. </div>'; 
        
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

</div>
</div>

<?php include('includes/footer.php'); ?>