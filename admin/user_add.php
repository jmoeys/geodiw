<?php



// Script options:
$login_check    = 'login_check.php'; 
$forbid_noLogin = 'forbid_noLogin.php'; 
$bottom         = '../bottom.php'; 
$rootDir        = '../';  
$home           = '../index.php';
$configLocal    = '../config/config.local.php'; 
$addUser        = 'user_add_form.php'; 
$manageUsers    = 'users_manage.php'; 
$debug          = 0; 



// Uncomment the code below to debug:
if( $debug == 1 ){ 
    ini_set('display_errors',1);
        error_reporting(E_ALL); 
    echo('Debug mode is on<br>'); 
}   



// Test if the user is authorised (logged in), 
include( $login_check ); 
include( $forbid_noLogin ); 

if( !($_SESSION['userLevel'] == 0) ){ 
    die( 'Error: You don\'t have the right access to modify users. <a href="' . $home . '>Return to start page</a>">' ); 
    exit; 
} 



// Save the $_POST values as $_SESSION values for eventual reuse.
if( isset( $_POST ) ){ 
    foreach( $_POST as $key => $value ){
        $_SESSION['post_add_user'][$key] = $value;
    }   
}



//check for required fields from the form
if( (!isset($_POST["USER_LOGIN"])) || (!isset($_POST["USER_PWD"]))  || (!isset($_POST["USER_PWD2"])) || 
    (!isset($_POST["USER_NAME"])) || (!isset($_POST["USER_MAIL"])) || (!isset($_POST["USER_LEVEL"])) ){
    die("Error: Some input fields are missing. Try again. <a href='" . $addUser . "'>Return to the add page</a>.<br>");
    exit;
}



//check if required fields are not empty:
if( ($_POST["USER_LOGIN"] == "" ) || ($_POST["USER_PWD"] == "") || ($_POST["USER_PWD2"] == "")    || 
    ($_POST["USER_LEVEL"] == "" ) || ($_POST["USER_MAIL"] == "" ) || ($_POST["USER_NAME"] == "" ) ){
    die("Error: Some input fields are empty. Try again. <a href='" . $addUser . "'>Return to the add page</a>.<br>");
    exit;
} 



// Load loacl config (database)
include($configLocal); 



//connect to server and select database
$mysqli = mysqli_connect( $dbHostX, $dbUserX, $dbPwdX, $dbNameX ); 



// Sanitise strings that will be passed to the database:
$USER_LOGIN    = addslashes( stripslashes( htmlentities( $_POST["USER_LOGIN"], ENT_QUOTES ) ) ); 
$USER_LOGIN    = mysqli_real_escape_string( $mysqli, $USER_LOGIN ); 

$USER_PWD     = addslashes( stripslashes( htmlentities( $_POST["USER_PWD"], ENT_QUOTES ) ) ); 
$USER_PWD     = mysqli_real_escape_string( $mysqli, $USER_PWD ); 

$USER_PWD2    = addslashes( stripslashes( htmlentities( $_POST["USER_PWD2"], ENT_QUOTES ) ) ); 
$USER_PWD2    = mysqli_real_escape_string( $mysqli, $USER_PWD2 ); 

$USER_NAME    = addslashes( stripslashes( htmlentities( $_POST["USER_NAME"], ENT_QUOTES ) ) ); 
$USER_NAME    = mysqli_real_escape_string( $mysqli, $USER_NAME ); 

$USER_MAIL     = addslashes( stripslashes( htmlentities( $_POST["USER_MAIL"], ENT_QUOTES ) ) ); 
$USER_MAIL     = mysqli_real_escape_string( $mysqli, $USER_MAIL ); 

$USER_LEVEL    = addslashes( stripslashes( htmlentities( $_POST["USER_LEVEL"], ENT_QUOTES ) ) ); 
$USER_LEVEL    = mysqli_real_escape_string( $mysqli, $USER_LEVEL ); 



// Check that the strings were not "altered"
if( !($USER_LOGIN == $_POST["USER_LOGIN"]) ){
  die("Error: forbidden character(s) detected in USER_LOGIN. Try again. <a href='" . $addUser . "'>Return to the add page</a>.<br>");
  exit;
}   
if( !($USER_PWD == $_POST["USER_PWD"]) ){
  die("Error: forbidden character(s) detected in USER_PWD. Try again. <a href='" . $addUser . "'>Return to the add page</a>.<br>");
  exit;
} 
if( !($USER_PWD2 == $_POST["USER_PWD2"]) ){
  die("Error: forbidden character(s) detected in USER_PWD2. Try again. <a href='" . $addUser . "'>Return to the add page</a>.<br>");
  exit;
} 
if( !($USER_NAME == $_POST["USER_NAME"]) ){
  die("Error: forbidden character(s) detected in USER_NAME. Try again. <a href='" . $addUser . "'>Return to the add page</a>.<br>");
  exit;
}  
if( !($USER_MAIL == $_POST["USER_MAIL"]) ){
  die("Error: forbidden character(s) detected in USER_MAIL. Try again. <a href='" . $addUser . "'>Return to the add page</a>.<br>");
  exit;
} 
if( !($USER_LEVEL == $_POST["USER_LEVEL"]) ){
  die("Error: forbidden character(s) detected in USER_LEVEL. Try again. <a href='" . $addUser . "'>Return to the add page</a>.<br>");
  exit;
} 



//Make sure the 2 provided passwords are identical
if( !($_POST["USER_PWD"] == $_POST["USER_PWD2"]) ){
  die("Error: the two passwords provided are not identical. Try again. <a href='" . $addUser . "'>Return to the add page</a>.<br>");
  exit;
} 



// Crypt the password, so it can be compared to what is in the database:
$USER_PWD = crypt( $USER_PWD, $encryptKeyX ); // consider using md5() if does not work




// Check that the user login does not exits yet:
$sql1 = 'SELECT DISTINCT `USER_LOGIN` FROM `ssld_users`'; 

if( $debug == 1 ){ echo( $sql1 . '<br>' ); } 



$result1 = $mysqli->query( $sql1 ); 
if( !$result1 === TRUE ){
    die( "Error:" . $mysqli -> error );
    $mysqli->close(); exit;
}   



// Check that login does not exists yet:
while( $newRow = $result1->fetch_array(MYSQLI_ASSOC) ){ 
    if( $newRow[ 'USER_LOGIN' ] == $USER_LOGIN ){ 
        die( 'Error: the user login has already been attributed! Try again. <a href="' . $addUser . '">Return to the add page</a>.<br>' ); 
        exit; 
    }   
}   
$result1->free(); 



// Quote the input values
$USER_NAME = '"' . $USER_NAME . '"'; 
$USER_MAIL = '"' . $USER_MAIL . '"'; 
$USER_LEVEL = '"' . $USER_LEVEL . '"'; 
$USER_PWD = '"' . $USER_PWD . '"'; 
$USER_LOGIN = '"' . $USER_LOGIN . '"'; 



// Retrieve the list of countries:
$sql = 'INSERT INTO `ssld_users` '. 
       '(`USER_LOGIN`, `USER_PWD`, `USER_NAME`, `USER_MAIL`, `USER_LEVEL`) '. 
       'VALUES( '. $USER_LOGIN . ', '. $USER_PWD . ', '. $USER_NAME . ', '. 
       $USER_MAIL . ', ' . $USER_LEVEL . ')'; 

if( $debug == 1 ){ echo( $sql . '<br>' ); } 



$result = $mysqli->query( $sql ); 
if( !$result === TRUE ){
    die( "Error:" . $mysqli -> error );
    $mysqli->close(); exit;
}else{ 
    //$result->free(); 
    $mysqli->close(); 
}   



// Mark the item as "updated":
$_SESSION['USER_ADDED'] = 1; 
$_SESSION['USER_ADDED_NAME'] = $USER_LOGIN; 

// Delete the item POST data:
unset( $_SESSION['post_add_user'] ); 



// Back to the edit page:
header( 'Location: ' . $manageUsers );  



?>

