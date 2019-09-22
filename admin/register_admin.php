<?php /* Include header file of the index page.  */  include('../includes/header.php'); include('includes/functions.php'); ?>
<div class="row">
      <div class="col-md-4 col-md-offset-4">
          <p class=""><a class="pull-right" href="../index.php"> Login</a></p>
      </div>
      <div class="col-md-4 col-md-offset-4">
            <form class="form-horizontal" role="form" method="post" action="register_admin.php" enctype="multipart/form-data">
              <div class="form-group">
                <label class="control-label col-sm-2" for="name"></label>
                <div class="col-sm-10">
                  <input type="name" name="name" class="form-control" id="name" placeholder="Enter Full Name" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="sex"></label>
                <div class="col-sm-10">
                  <select type="" name="sex" class="form-control" id="sex" >
                      <option value="">Select Sex</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      <option value="Secret">N/A</option>
                  </select>
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
                <label class="control-label col-sm-2" for="image"></label>
                <div class="col-sm-10">
                  <input type="file" name="image" id="image" placeholder="Choose Image" required>
                </div>
              </div>
              <div class="form-group"> 
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox">
                    <label><input type="checkbox" required> Accept Agreement</label>
                  </div>
                </div>
              </div>
              <div class="form-group"> 
                <div class="col-sm-offset-2 col-sm-10 text-center">
                  <button type="submit" class="btn btn-primary pull-right" name="submit_login">Register</button>
                  <a class="pull-left btn btn-danger" href="../index.php"> Cancel</a>
                </div>
              </div>
            </form>
      </div>
</div>
<!-- ******************************************************************** Register new Admin ************************************************************ -->
<?php if(isset($_POST['submit_login'])) {
    require('includes/pdocon.php'); 
    $db = new Pdocon; 
    $raw_name = cleandata($_POST['name']);
    $raw_sex = cleandata($_POST['sex']);
    $raw_email = cleandata($_POST['email']);
    $raw_pwd = cleandata($_POST['password']);
    
    $clean_name = sanitizer($raw_name);
    $clean_sex = sanitizer($raw_sex);
    $clean_email = validateemail($raw_email);
    $clean_pwd = sanitizer($raw_pwd);
    $hashed_Pass = hashpassword($clean_pwd); 
     /* This what happens:
    
        Once, the submit button is pressed, FILES super global variable will collect the selected image which has a filename of 'name' and will store it in field 'image' of its associative array. Next, a copy of the submitted image will be made as shown in the $collectedImage_temp.
        
        Next, after form validation, the submitted image will be moved to the permanent location using a PHP move function as shown below.
        
        the move_uploaded_file() is the helper php function that moves the uploaded file to its permanent folder. 
    
    */
    $collectedImage = $_FILES['image']['name'];
    $collectedImage_temp = $_FILES['image']['tmp_name'];
    move_uploaded_file($collectedImage_temp, "uploaded_image/$collectedImage");   
    /*
    First: 
        Prepare a query to check with the database if its executable on the database. 
            Preparing an INSERT query, where you want to check if the submitted email already exists. 
    Second: 
        Bind the query email with the user submitted CLEANED email. 
    Third: 
        If the database finds the submitted new admin email, then $row value will be a non-zero value, i.e. >0 . 
    
    */
    $db->query("SELECT * FROM admin WHERE email=:email");
    $db->bindvalue(':email',$clean_email, PDO::PARAM_STR);
    $row = $db->fetchSingle();
    if($row){
        /*
        If the submitted new admin is already in the database, we're going to echo an Bootstrap <div>. 
    
        The <div> is just an HTML block of warning. Check Boostrap Tutorial on w3schools.com and go to BS Alerts, to find all sorts of <div> that can be echoed as messages to interact with the user. 
    
        */
        echo '<div class="alert alert-danger text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Sorry!</strong> User already exist. Register or try again. </div>';
    }else{
        /* Register new admin into the databaase's admin table. */
        $db->query("INSERT INTO admin(id, fullname, email, password, sex,image) VALUES(NULL,:name, :email, :pwd,:sex,:image)"); 
        $db->bindvalue(':name', $clean_name, PDO::PARAM_STR);
        $db->bindvalue(':email',$clean_email, PDO::PARAM_STR);
        $db->bindvalue(':pwd',$hashed_Pass,PDO::PARAM_STR);
        $db->bindvalue(':sex',$clean_sex,PDO::PARAM_STR);
        $db->bindvalue(':image',$collectedImage,PDO::PARAM_STR);
        $run = $db->execute();
        if($run) {
                /*  Admin registration successful. */
            /*
                Why aren't we using the super global variable of $_SESSION and the keepmsg() function that we created as a HELPER function? 
                Ans: That helper function can only be used, once the user has logged in, and is manipulating things inside. 
                The <div> message is used whenever you're communicating with someone on the outside of the database, the dive just creates a graphical block of information for the user.
                Incase, of success execution of updating database with new admin information, we echo a <div> from the w3schools.com's Bootstrap Tutorial for BS Alerts.
            
            */
            echo'<div class="alert alert-Success text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Congrats!</strong> Admin registeration successful! Please login. </div>';
        }else{
            echo'<div class="alert alert-Failure text-center">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Sorry!</strong> Admin registeration unsuccessful. Please try again. </div>';  
        }    
    }         
}?>
<?php include('../includes/footer.php'); ?>  