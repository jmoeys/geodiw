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
$labAdd         = 'inst_add.php'; 
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



// In case the form has already been filled:
if( isset( $_SESSION['post_add_val'] ) ){ 
    $LAB_NAME    = ' value="' . $_SESSION['post_add_val'][ 'LAB_NAME' ] . '"'; 
    $WGS_LAT     = ' value="' . $_SESSION['post_add_val'][ 'WGS_LAT' ] . '"'; 
    $WGS_LONG    = ' value="' . $_SESSION['post_add_val'][ 'WGS_LONG' ] . '"'; 
    $LAB_DESC    = ' value="' . $_SESSION['post_add_val'][ 'LAB_DESC' ] . '"'; 
    $LAB_INST    = ' value="' . $_SESSION['post_add_val'][ 'LAB_INST' ] . '"'; 
    $LAB_URL     = ' value="' . $_SESSION['post_add_val'][ 'LAB_URL' ] . '"'; 
    $LAB_CITY    = ' value="' . $_SESSION['post_add_val'][ 'LAB_CITY' ] . '"'; 
    $LAB_COUNTRY = ' value="' . $_SESSION['post_add_val'][ 'LAB_COUNTRY' ] . '"'; 
}else{ 
    $LAB_NAME    = ''; 
    $WGS_LAT     = ''; 
    $WGS_LONG    = ''; 
    $LAB_DESC    = ''; 
    $LAB_INST    = ''; 
    $LAB_URL     = ''; 
    $LAB_CITY    = ''; 
    $LAB_COUNTRY = ''; 
}    


        
// Format the form table:
$formTable = '<!-- Add form -->' . PHP_EOL. 
'<form method="post" action="' . $labAdd . '">' . PHP_EOL. 
'    <table class="ghost">' . PHP_EOL. 

'    <thead>' . PHP_EOL. 
'        <th></th><th></th>' . PHP_EOL. 
'    </thead>' . PHP_EOL. 

'    <tbody>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>Name*:</td><td> <input name="LAB_NAME" type="text" ' . $LAB_NAME . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>Latitude (WGS):</td><td> <input name="WGS_LAT" type="text" ' . $WGS_LAT . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>Longitude (WGS):</td><td> <input name="WGS_LONG" type="text" ' . $WGS_LONG . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>Description:</td><td> <input name="LAB_DESC" type="text" ' . $LAB_DESC . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>URL*:</td><td> <input name="LAB_URL" type="text" ' . $LAB_URL . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>Institution:</td><td> <input name="LAB_INST" type="text" ' . $LAB_INST . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>City*:</td><td> <input name="LAB_CITY" type="text" ' . $LAB_CITY . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'        <tr>' . PHP_EOL. 
'            <td>Country*:</td><td> <input name="LAB_COUNTRY" type="text" ' . $LAB_COUNTRY . ' /> </td>' . PHP_EOL. 
'        </tr>' . PHP_EOL. 

'    </tbody></table>' . PHP_EOL . PHP_EOL. 
'    <br>' . PHP_EOL . PHP_EOL. 
'    <input name="add item" value="add item" type="submit" class="geodiwButton" style="width:15ex;">' . PHP_EOL. PHP_EOL.  
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

<p><i>*: Items marked with a star (*) are required.</i></p>

<p>See the <a href="http://en.wikipedia.org/wiki/Wikipedia:Obtaining_geographic_coordinates#OpenStreetMap" target="_blank">Wikipedia page</a> 
on WGS 1984 coordinates retrieval.</p>

<br>

<?php echo( $menu ); ?>

<br>

<?php include($footer); ?>



</body>

</html>

