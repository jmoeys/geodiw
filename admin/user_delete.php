<?php



// Script options:
$login_check    = 'login_check.php'; 
$forbid_noLogin = 'forbid_noLogin.php'; 
$bottom         = '../bottom.php'; 
$rootDir        = '../';  
$home           = '../index.php';
$configLocal    = '../config/config.local.php'; 
$updateUser     = 'user_update_form.php'; 
$editUser       = 'user_edit.php'; 
$manageUsers    = 'users_manage.php'; 
$debug          = 0; 



// Uncomment the code below to debug:
if( $debug == 1 ){ 
    ini_set('display_errors',1);
        error_reporting(E_ALL); 
    echo( 'Debug mode is on.<br>' ); 
}   



// Test if the user is authorised (logged in), 
include( $login_check ); 
include( $forbid_noLogin ); 



if( !isset($_SESSION['USER_ID']) ){ 
    die( 'Error: no ID provided. <a href="' . $updateUser . '">Return to user page</a>.<br>' ); 
    exit; 
}else{ 
    $userForm = $editUser . '?id=' . $_SESSION['USER_ID'];  
}    



//check for required fields from the form
if(  (!isset($_POST["USER_ID"])) ){
    die("Error: Some input fields are missing. Try again. <a href='" . $userForm . "'>Return to the edit page</a>.<br>");
    exit;
}



// Load loacl config (database)
include($configLocal); 



//connect to server and select database
$mysqli = mysqli_connect( $dbHostX, $dbUserX, $dbPwdX, $dbNameX ); 



// Sanitise strings that will be passed to the database:
$USER_ID      = addslashes( stripslashes( $_POST["USER_ID"] ) ); 
$USER_ID      = mysqli_real_escape_string( $mysqli, $USER_ID ); 



// Check that the strings were not "altered"
if( !($USER_ID == $_POST["USER_ID"]) ){
  die("Error: forbidden character(s) detected in USER_ID. Try again. <a href='" . $updateUser . "'>Return to the edit page</a>.<br>");
  exit;
} 



// Retrieve the list of countries:
$sql = 'DELETE FROM `ssld_users` '. 
       'WHERE `USER_ID`=' . $USER_ID; 

if( $debug == 1 ){ echo( $sql . '<br>' ); }  



$result = $mysqli->query( $sql ); 
if( !$result === TRUE ){
    die( "Error:" . $mysqli -> error );
    $mysqli->close(); exit;
}  



// Mark the item as "updated":
$_SESSION['USER_DELETED'] = 1; 
$_SESSION['USER_DELETED_NAME'] = $_POST["USER_NAME"]; 



// Back to the edit page:
header( 'Location: ' . $manageUsers ); 



?>

