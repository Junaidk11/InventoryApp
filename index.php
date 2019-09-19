<?php 
include('includes/header.php'); 
include('admin/includes/functions.php');
/*
require database connection file 
*/
require('admin/includes/pdocon.php');
/*
Instatiate database
*/
$db = new Pdocon; 
/*
Collect and process login data on the database.
*/
if (isset($_POST['submit_login']))
    {
        /*
        Collect form submitted data. 
        */
        $raw_email = cleandata($_POST['email']);
        $raw_pwd = cleandata($_POST['password']);
        /*
        validate your email, and sanitize your password. 
        */
        $clean_email = validateemail($raw_email);
        $clean_pwd = sanitizer($raw_pwd);
        /*
        Create hashed password - encrypting user password for security.
        */
        $hashed_pwd = hashpassword($clean_pwd);
        /*
        Prepare your query to check if user already exist. Bind cleaned form data to the prepared query to be executed on the database.
        */
        $db->query('SELECT * FROM admin WHERE email=:email AND password=:password');
        $db->bindvalue(':email',$clean_email, PDO::PARAM_STR);
        $db->bindvalue(':password', $hashed_pwd, PDO::PARAM_STR);
        /*
        Execute the prepared query and get the result of the query from the database.
        */
        $row = $db->fetchSingle();
        if($row) 
        { /*
            If user exist, login was successfull - Retrieve user information from database and display.
            */
             $database_image = $row['image']; 
             $s_image = "<img src='uploaded_image/$database_image' class ='profile_image' />"; 
            /*
            Create user session 
            */
            $_SESSION['user_data'] = array(
                'fullname'  => $row['fullname'],
                'id'        => $row['id'],
                'email'     => $row['email'],
                'image'     => $s_image  
            ); 
            /*
            Use this session to redirect/ move the user around the application.
            */
            $_SESSION['user_is_logged_in'] = true; 
            /*
              Redirect user to Inventory page. 
            */ 
            redirect('admin/inventory.php'); 
            /*
            Now user has been successfully authenticated, and the session has been created. Displaying messages using the keepmsg() function from the helper functions to display success and error messages to the Logged-In user as he/she makes changes to the database. 
            
            This message will be displayed on the inventory.php page using the showmsg() helper function. 
            */
            keepmsg('<div class="alert alert-success text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Login Successful. Welcome </strong>'. $row['fullname'].'!</div>'); 
        }
        else 
        {   /*
            The login information does not exist - i.e. Login unsuccessful. 
            User not authenticated, CANNOT use the helper function keepmsg(). 
            */
             echo '<div class="alert alert-danger text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Sorry!</strong> Incorrect email or password. Try again. </div>';
        }
    }
?>
   <!-- The INDEX page of the Inventory Manager. -->
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