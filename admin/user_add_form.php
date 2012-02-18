<?php




// Script options:
$login_check    = 'login_check.php'; 
$forbid_noLogin = 'forbid_noLogin.php'; 
$footer         = '../footer.php'; 
$rootDir        = '../';  
$manageUsers    = 'users_manage.php'; 
$preferences    = 'preferences.php'; 
$home           = '../index.php';
$logout         = 'logout.php'; 
$configLocal    = '../config/config.local.php'; 
$userAdd        = 'user_add.php'; 
$geodiwcss      = '../geodiw.css'; 
$debug          = 0; 



// Uncomment the code below to debug:
if( $debug == 1 ){ 
    ini_set('display_errors',1);
        error_reporting(E_ALL); 
    echo( 'Debug mode is on<br>' ); 
}   



// Test if the user is authorised (logged in), 
include( $login_check ); 
include( $forbid_noLogin ); 

if( !($_SESSION['userLevel'] == 0) ){ 
    die( 'Error: You don\'t have the right access to modify users. <a href="' . $home . '>Return to start page</a>">' ); 
    exit; 
} 



// In case the form has already been filled:
if( isset( $_SESSION['post_add_user'] ) ){ 
    $USER_LOGIN = ' value="' . $_SESSION['post_add_user'][ 'USER_LOGIN' ] . '"'; 
    $USER_PWD   = ' value="' . $_SESSION['post_add_user'][ 'USER_PWD' ] . '"'; 
    $USER_PWD2  = ' value="' . $_SESSION['post_add_user'][ 'USER_PWD2' ] . '"'; 
    $USER_MAIL  = ' value="' . $_SESSION['post_add_user'][ 'USER_MAIL' ] . '"'; 
    $USER_NAME  = ' value="' . $_SESSION['post_add_user'][ 'USER_NAME' ] . '"'; 
    if( $_SESSION['post_add_user'][ 'USER_LEVEL' ] == 1 ){ 
        $USER_LEVEL1 = ' selected="selected"'; 
        $USER_LEVEL0 = '';        
    }
    if( $_SESSION['post_add_user'][ 'USER_LEVEL' ] == 0 ){ 
        $USER_LEVEL0 = ' selected="selected"'; 
        $USER_LEVEL1 = '';        
    }
}else{ 
    $USER_LOGIN = ''; 
    $USER_PWD   = ''; 
    $USER_PWD2  = ''; 
    $USER_MAIL  = ''; 
    $USER_NAME  = ''; 
    $USER_LEVEL1= ''; 
    $USER_LEVEL0= ''; 
}    


        
// Format the form table:
$formTable = '<!-- Add form -->' . PHP_EOL. 
'<form method="post" action="' . $userAdd . '">' . PHP_EOL. 
'    <table class="ghost">' . PHP_EOL. 

'    <thead>' . PHP_EOL. 
'        <th></th><th></th>' . PHP_EOL. 
'    </thead>' . PHP_EOL. 

'    <tbody>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>Login:</td><td> <input name="USER_LOGIN" type="text" ' . $USER_LOGIN . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>Password:</td><td> <input name="USER_PWD" type="password" ' . $USER_PWD . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>Password (repeat):</td><td> <input name="USER_PWD2" type="password" ' . $USER_PWD2 . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>Name:</td><td> <input name="USER_NAME" type="text" ' . $USER_NAME . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>e-mail:</td><td> <input name="USER_MAIL" type="text" ' . $USER_MAIL . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>Level*:</td><td> <select name="USER_LEVEL"> '. PHP_EOL. 
'                                    <option value="1" ' . $USER_LEVEL1 . '>Editor (1)</option>' . PHP_EOL. 
'                                    <option value="0" ' . $USER_LEVEL0 . '>Superadmin (0)</option>' . PHP_EOL. 
'                                 </select></td>'. PHP_EOL. 
'        </tr>' . PHP_EOL. 

'    </tbody></table>' . PHP_EOL . PHP_EOL. 
'    <br>' . PHP_EOL . PHP_EOL. 
'    <input name="add user" value="add user" type="submit" class="geodiwButton" style="width:15ex;">' . PHP_EOL. PHP_EOL.  
'</form>' . PHP_EOL . PHP_EOL; 



// Prepare the bottom menu:
$menu = '<ul class="geodiwMenu">' . PHP_EOL. 
'    <li><a href="' . $home . '">Home</a></li>' . PHP_EOL. 
'    <li><a href="' . $manageUsers . '">Manage users</a></li>' . PHP_EOL. 
'    <li><a href="' . $preferences . '">Preferences</a></li>' . PHP_EOL. 
'    <li><a href="' . $logout . '">Logout</a></li>' . PHP_EOL. 
'</ul>' . PHP_EOL;



// Format the displayed user name:
$welcome = '<p>Welcome ' . $_SESSION['userName'] . ' (' . $_SESSION['userMail'] . ').</p>';



?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <?php echo( '<link href="' . $geodiwcss . '" rel="stylesheet" type="text/css">' ); ?> 
    <style type="text/css">
        input { width:600px; } 
        table.ghost { width:400px; table-layout:fixed; }
    </style>
    <title>Soil Science Lab Directory (SSLD) -- Add a directory entry</title>
</head>

<body>

<h1>Soil Science Lab Directory (SSLD)</h1>

<h2>Add a directory entry</h2>

<?php echo($welcome); ?> 


<?php echo($formTable); ?> 

<br>

<?php echo( $menu ); ?>

<?php include($footer); ?>



</body>

</html>

