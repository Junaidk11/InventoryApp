<?php include('includes/header.php'); ?>
<?php

//Include your HELPER function files

include('admin/includes/functions.php');


// We need to check if user logged in, else redirect the user to the index.php page - To do this we need the $_SESSION super global variable, the idea is to create a session when a user logs in and keep track of the user. 

//and also write a statement to redirect user when logged in 

?>
 
<?php

//require or include your database connection file
require('admin/includes/pdocon.php');
    
//instatiating our database objects
$db = new Pdocon; 
//Collect and process Data on login submission
/*

    Collect the submitted form data, trim the data, sanitize and validate. 
    Prepare a query to knock on the database to check if it executable. 
    If executable, check if user exists on the database by crossing check the submitted info with the database admin information. 
    If user exist, give access to the database,
    if not, echo a <div> to alert the user that they're not registered. 
*/


if (isset($_POST['submit_login']))// Check if submit button was pressed 
    {
        // Get your raw values from $_POST super global variable 
        $raw_email = cleandata($_POST['email']);
        $raw_pwd = cleandata($_POST['password']);
        
        
        // validate your email, and sanitize your password
        $clean_email = validateemail($raw_email);
        $clean_pwd = sanitizer($raw_pwd);
        
        // Create hashed password 
        $hashed_pwd = hashpassword($clean_pwd);
        
        // Prepare your query
        $db->query('SELECT * FROM admin WHERE email=:email AND password=:password');
        
        // Bind the submitted and cleaned values to the query
        
        $db->bindvalue(':email',$clean_email, PDO::PARAM_STR);
        $db->bindvalue(':password', $hashed_pwd, PDO::PARAM_STR);
        
        // Execute the prepared query and get the result of the query from the database - recall: we created a Helper function that will execute the prepared query and return result from the database. 
        
        $row = $db->fetchSingle(); // This function will return the prepared query's execution result. 
        
        if($row) // if the submitted login information exist,i.e $row has a now zero value, i.e. $row contains the submitted user's information found in the database.  
        {
           
            
            /*
            get the image name from the database, that is assigned to the current user. 
            */
            
             $database_image = $row['image']; 
            
             /* 
            we assigned a class to the user's image. To store the image path we used to the html tag of img. and we used double quotes because we used single quotes inside, you could flip it the other way around. 
            */ 
            
            $s_image = "<img src='uploaded_image/$database_image' class ='profile_image' />"; 
           
            
            /* 
            We need to create a session for the user 
            */
            
            $_SESSION['user_data'] = array(
                
                'fullname'  => $row['fullname'],
                'id'        => $row['id'],
                'email'     => $row['email'],
                'image'     => $s_image  
            );  
            
            /*
            After creating a session for the user who just logged in, we need to redirect the user to his/her account. In order to that, we create a new session using the $_SESSION super global variable and set it to true.
            */
            
            
            $_SESSION['user_is_logged_in'] = true;  // Use this session to redirect/ move the user around the application
            
            /*
            Now we need to call our redirect function, that we created in our HELPER functions file. We will redirect the user from here to its respective admin page. 
            */
            
            
            redirect('admin/inventory.php'); // Redirtecting the user to his/her admin page. 
            
            /*
            
            Now, once the user has been successfully authenticated, and the session has been created, we can start displaying messages using the keepmsg() function from the helper functions to display success and error messages to the loggedin user as he makes changes to the database. 
            
            */
            
            keepmsg('<div class="alert alert-success text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Welcome </strong>'. $row['fullname'].'! You are logged in as Admin </div>'); 
            
                /*
                We're creating a <div> to alert the user that his/her login attempt has been successful. 
                
                This message will be displayed when the user is redirected to his/her admin page. So it should be a welcome message. Since the user is redirected to the my_admin.php, we store the message in the $_SESSION, and display the msg where we wish to, using the helper function showmsg().  - the Welcome msg will be displayed on the my_admin.php page.
                */ 
        }
        else // The login information does not exist, we echo danger
        {
             echo '<div class="alert alert-danger text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Sorry!</strong> Incorrect email or password.Try again. </div>';
        }
        
    }
    /* 
      1- making the query using our functions
   

      2- To specify the WHERE statement, You need to call the bind method
    

      3- Fetching the resultset for a single result and keep in a row variable
    
         

     4- Collect the image, id, email, fullname and keep an session
   

            
    5- create a session variable and set it to true 
         
            
            
    6- Redirect a succuessfull login to customer.php
        
            
    7- Use the set_message function to set a welcome msg in a closable div and echo a div danger when login fails in else statement
    
    */
?>
  
  
  <div class="row">
      <div class="col-md-4 col-md-offset-4">
          <p class=""><a class="pull-right" href="admin/register_admin.php"> Register User</a></p><br>
      </div>
      <div class="col-md-4 col-md-offset-4">
        <form class="form-horizontal" role="form" method="post" action="index.php">   
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
            <div class="col-sm-offset-2 col-sm-10 text-center">
              <button type="submit" class="btn btn-primary text-center" name="submit_login">Login</button>
            </div>
          </div>
        </form>        
  </div>
</div>
<?php include('includes/footer.php'); ?>