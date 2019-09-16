<?php
session_start();//session is a way to store information (in variables) to be used across multiple pages.
if(session_destroy()){
    header("Location: ../index.php");   
} 
?><!-- 
    This file is connected to the signout button on the my_admin.php. 
    When the user presses the signout button, the user logout.php file is executed. 
    PHP starts the session to get access to the $_SESSION super global variable and then unsets the session in the super global variable that was set during the login, 
    recall: when we created a session 'user_is_logged_in' in the $_SESSION super global variable to keep track of the fact that the user has logged in. 
    
    The script below will unset the session and destory the session, when the user selects signout, and it will also redirect to the login page - which is the index.php page of the application. 
-->