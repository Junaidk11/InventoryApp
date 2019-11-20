<?php  ob_start(); session_start(); ?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Physics Lab Inventory Manager </title>
        
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src="js/jquery.js"></script>
        <script src="js/scripts.js"></script>
        
        <!-- Custom CSS -->
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/custom.css" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="css/plugins/morris.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        
        <!-- Responsive CCS -->
        
        <style>
        </style>
    </head>
    <body style="padding-top: 30px;">            
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
        <div class="container-fluid" >
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand navbar-light" href="" style="color: #f3f3f3">Inventory<strong>MANAGER</strong></a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <!-- Check If user is logged in and collect session info and echo -->
             <?php 
                if(isset($_SESSION['user_is_logged_in']))
                {
                    header("Location: admin/inventory.php"); 
                }else {
                ?> <!-- close your php to execute HTML code-->
             <!--else if the user is not loggin in then show this, open else-block code in php, close PHP to execute HTML code -->
              
                <ul class="nav navbar-nav navbar-right">
                    <li><p><a href="../index.php">Login</a></p></li>
                </ul>
                
                 <?php } ?> <!-- Close your else in PHP to close the else block -->
            </div>
        </div>
    </nav>
    <div class="container-fluid">
    