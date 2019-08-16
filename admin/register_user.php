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
if(isset($_POST['submit_user'])) // The register button is pressed by the user
{
    // Use the helper function to trim the data submitted through the form
    
    $raw_name = cleandata($_POST['name']);
    $raw_email = cleandata($_POST['email']);
    $raw_pwd = cleandata($_POST['password']);
    
    // Validate and sanitize the cleaned data
    $clean_name = sanitizer($raw_name);
    $clean_email = validateemail($raw_email);
    $clean_pwd = sanitizer($raw_pwd);
    
    //Hash password using our md5 function
    $hashed_Pass = hashpassword($clean_pwd); 
    
    
    //Check and see if user already exist in database using email so write query and bind email
    
        /*  Prepare a query first*/
    $db->query('SELECT * FROM users WHERE email =:email');
    $db->bindvalue(':email',$clean_email,PDO::PARAM_STR);
    
    //Call function to count row, fetch the result if the user is already in the database
    $run_query = $db->fetchSingle(); 
    
    
    if($run_query) // If query successfully executed, i.e. user found in system. 
    {
        
        //Display error if customer exist 
        
        //Echo a danger <div> that will alert the user that the user exist. 
        
        redirect('users.php');
        
        $message ='<div class="alert alert-Failure text-center">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong> User registeration unsuccessful. User already exist.</strong></div>';
        
        keepmsg($message); 
     
    }
    else
    {
        //Write query to insert values, bind values
        
        // The user doesn't exist, so we prepare a query, copy the user to the database. 
        
        $db->query("INSERT INTO users(id, full_name, email, password) VALUES(NULL,:name,:email, :pwd)");
        
        // Bind the values 
        $db->bindvalue(':name',$clean_name, PDO::PARAM_STR);
        $db->bindvalue(':email',$clean_email,PDO::PARAM_STR);
        $db->bindvalue(':pwd',$hashed_Pass,PDO::PARAM_STR);
        
        // execute the query and store result if the registration was successful or not. 
    
        $run_query = $db->execute();
        
        if($run_query) // Check if the registration was a success 
        {
            // If registration successful, echo <div> stating a success
            
             
            redirect('users.php');         
            $message='<div class="alert alert-Success text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong> User registeration successful! </strong></div>';
            
            keepmsg($message); 
             
            
              
        }
        else
        {
            
            redirect('users.php');      
            // If the registeration failed 
            $message= '<div class="alert alert-Failure text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong> Oops!</strong> User registeration unsuccessful. Please try again. </div>';
            
            keepmsg($message);
            
            
        }    
 
    }
    
}
?>

    <div class="row">
      <div class="col-md-4 col-md-offset-4">
         
          <p class="pull-right" style="color:#777"> Adding User to Database</p><br>
      </div>
      <div class="col-md-4 col-md-offset-4">
          
           <form class="form-horizontal" role="form" method="post" action="register_user.php">
            <div class="form-group">
            <label class="control-label col-sm-2" for="name"></label>
            <div class="col-sm-10">
              <input type="name" name="name" class="form-control" id="name" placeholder="Enter Full Name" required>
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-2" for="email"></label>
            <div class="col-sm-10">
              <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" required>
            </div>
          </div>
          
          <div class="form-group">
            <label class="control-label col-sm-2" for="pwd"></label>
            <div class="col-sm-10"> 
              <input type="password" name="password" class="form-control" id="pwd" placeholder="Enter Password" required>
            </div>
          </div>

          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <div class="checkbox">
                <label><input type="checkbox" required> Accept</label>
              </div>
            </div>
          </div>

          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10 text-center">
              <button type="submit" class="btn btn-primary pull-right" name="submit_user">Register</button>
              <a class="pull-left btn btn-danger" href="users.php"> Cancel</a>
            </div>
          </div>
</form>
          
  </div>
</div>
  
<?php include('includes/footer.php'); ?>  