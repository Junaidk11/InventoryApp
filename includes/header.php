<?php 
//Open ob_start and session_start functions 
    ob_start();
    session_start(); 
?><html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Physics Lab Inventory </title>

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

        

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]
        url(https://lh3.googleusercontent.com/-7kOBhr3B2dE/AAAAAAAAAAI/AAAAAAAAAAA/AOtt-yHs4g14qqNJaJBXAcpIMv_fV9dDGw/s32-c-mo/photo.jpg)
        -->
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

                <ul class="nav navbar-nav navbar-left">
                    <li><a href=""></a></li>
                </ul>
                
             <?php //Check to see if user is logged in and collect session info and echo
                
                if(isset($_SESSION['user_is_logged_in']))//if the user is logged in you want to redirect the user to its respective admin page.
                {
                   
                    /*
                        The '..' in the path name will take the php one folder back, and then find the respective file, which is the my_admin.php page. 
                    */
                    
                    /*
                    
                        If a user is logged in, in the index.php's header file, the page always gets redirected to the admin page of the user. 
                    */
                    header("Location: admin/my_admin.php"); 
                    
                }else {
                ?> <!-- close your php to execute HTML code-->
                
             <!--else if the user is not loggin in then show this
             Open your else block code in php, close PHP to execute HTML code -->
              
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="index.php">Welcome Guest!</a></li>
                </ul>
                 <?php } ?> <!-- Close your else in PHP to close the else block -->

            </div>

        </div>

    </nav>
        
    <div class="container-fluid">
    