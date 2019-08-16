<?php include('includes/header.php'); ?>

<?php
/*Include functions - Don't have to, the header takes care of this and also ensures that a session is created for the current user.

//check to see if user if logged in else redirect to index page

*/
?>

<?php 
/************** Fetching all data from database ******************/


//require database class files
require('includes/pdocon.php');

//instatiating our database objects
$db= new Pdocon;


//Create a query to select all users to display in the table
$db->query("SELECT * FROM users"); // prepare a query to sellect all the suers   

//Fetch all data and keep in a result set
$results = $db->fetchMultiple();  // All the information in the users table from the database are fetched and stored in $results variable
?>


  <div class="container">

   <?php // call your display function to display session message on top page 
      showmsg();
      
      
      ?>
   
  <div class="jumbotron">
  
  <small class="pull-right"><a href="register_user.php"> Add User</a> </small>
 
  <?php 
      
      //Collect session name and write a welcome message with the user session's name 
        
      /*
      Collect the admin's name and put it in there using the session super global
      
    Using the super global variable of $_SESSION, access the current logged in user's information from session 'user_data', that was created when the user logged in. 
     */
      
    $fullname = $_SESSION['user_data']['fullname']; 
    
    echo '<small class="pull-left" style="color:#337ab7;">'.$fullname.' | Veiwing / Editing</small>';
      
   ?>
    
    <h2 class="text-center">Users</h2> <hr>
    <br>
     <table class="table table-bordered table-hover text-center">
        <thead >
          <tr>
            <th class="text-center">User ID</th>
            <th class="text-center">Full Name</th>
            <th class="text-center">Email</th>
          </tr>
        </thead>
        <tbody>
    <?php  foreach ($results as $result)  {// loop through your result set and fill in the table : ?>
          <tr>
            <td><?php echo $result['id'] ?></td>
            <td><?php echo $result['full_name'] ?></td>
            <td><?php echo $result['email'] ?></td>
            <td><a href="edit_user.php?user_id=<?php echo $result['id'] ?>" class='btn btn-danger'>Edit</a></td>
            
          </tr>
          
          <?php } //end your loop ?>
        </tbody>
     </table>
</div>
  </div>
    
<?php include('includes/footer.php'); ?>