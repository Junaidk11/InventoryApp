<?php include('includes/header.php'); ?>

<?php
//Include  helper functions file  - You don't have to, because the header.php file has already included the functions.php file. 

// Adding files twice creates an error - to avoid duplications. 

//check to see if user is logged in else redirect to index page
?>  
   
<div class="row">
      <div class="col-md-4 col-md-offset-4">
      </div>
      <div class="col-md-4 col-md-offset-4">
           <form class="form-horizontal" role="form" method="POST" action="<?php $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">
           
           <?php 
               
             
               /************** Fetching data from database using id ******************/
               if(isset($_GET['admin_id']))
               {
                   $admin_id = $_GET['admin_id'];
               }

                //require database class files
                require('includes/pdocon.php');

                //instatiating our database objects
                $db = new Pdocon;

                //Create a query to display customer inf 
                $db->query('SELECT * FROM admin WHERE id=:id');
 
 
                // You must bind the id coming in from the url
                $db->bindvalue(':id',$admin_id, PDO::PARAM_INT);

                $row=$db->fetchSingle(); // this will fetch the row of information from the admin table corresponding to the :id = admin_id
         ?>
        


        <!--
                
                Fetching the data and display it in the form value fields
                
        -->
        
        
               
            <div class="form-group">
               <?php
                
                    if($row) // if the prepared query was succesfully executed, i.e. query found the user in the database. 
                    {
                         // display the user's information on the table for editing 
                ?>
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
              <input type="file" name="image" id="image" placeholder="Choose Image" value="" required>
            </div>
          </div>

          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10 text-center">
              <button type="submit" class="btn btn-primary pull-right" name="submit_update">Update</button>
              <a class="pull-left btn btn-danger" href="my_admin.php">Cancel</a>
            </div>
          </div>
          
         
</form>  
         <?php // end your php
                } // end your if block here, after displaying all the information 
               ?>  
         
<!-- 
        The following code will be used to update the admin, if the update button is pressed by the user. 
        
        We can simply copy paste the register_admin.php with minor changes. 
         
            
-->   
          
<?php
          
/************** Update data to database using id ******************/  
                
/*
Get field names from from and validate          
        
           
     //Getting image and move images to admin_image folders
          
     
     //Write your query
     
     
     
     //binding values with your variable
    
     
     //Execute query statement to send it into the database
     
     
     //Confirm execution and set your messages to display as well has redirection and errors
     
     */
          
/************** Updating Admin ******************/

if(isset($_POST['submit_update'])) // Check if the update is pressed
{
    $raw_name = cleandata($_POST['name']); 
    $raw_email = cleandata($_POST['email']);
    $raw_pwd = cleandata($_POST['password']);
    
    $clean_name = sanitizer($raw_name);
    $clean_email = validateemail($raw_email);
    $clean_pwd = sanitizer($raw_pwd);
    
    $hashed_Pass = hashpassword($clean_pwd); 
    
    // Image adding steps:
    
    //First Collect Image, using the $_FILES super global
    /*  
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
    
    /*Echo the variables to check if we're getting the values - Just a test code */
//    
//    echo $clean_name; 
//    echo $clean_sex;
//    echo $clean_email;
//    echo $hashed_Pass;   
//    
//    

    // Insert submitted values for updated admin into the databaase's admin table. 

    $db->query("UPDATE admin SET fullname=:name, email=:email, password=:pwd, image=:image WHERE id=:id"); 
    
    // Bind the variables to their values - Not when updating??
    
    $db->bindvalue(':name', $clean_name, PDO::PARAM_STR);
    $db->bindvalue(':email',$clean_email, PDO::PARAM_STR);
    $db->bindvalue(':pwd',$hashed_Pass,PDO::PARAM_STR);
    $db->bindvalue(':image',$collectedImage,PDO::PARAM_STR);
    $db->bindvalue(':id',$admin_id, PDO::PARAM_INT);
        
    // Execute the query
    $run = $db->execute();


    if($run) // if the query successfully executed 
    {
        // echo another <div> to the user to notify them that the information has been successfully uploaded to the database.
        redirect('my_admin.php');

        /*

            You might wonder, why aren't we using the super global variable of $_SESSION and the keepmsg() function that we created as a HELPER function, 

            that helper function can only be used, once the user has logged in, and is manipulating things inside. 

            The <div> message is used whenever you're communicating with someone on the outside of the database, the dive just creates a graphical block of information for the user. 

            Incase, of success execution of updating database with new admin information, we echo a <div> from the w3schools.com's Bootstrap Tutorial for BS Alerts.

        */
        
        $message ='<div class="alert alert-Success text-center">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong> Admin update successful! </strong></div>';
        
        keepmsg($message);
         
       
    }
    else
    {
        redirect('my_admin.php');
        $message ='<div class="alert alert-Failure text-center">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong>Oops!</strong> Admin update unsuccessful. </div>';
        
        keepmsg($message);
    }    
}
          
?>        
          
  </div>
</div>
  
<?php include('includes/footer.php'); ?>  