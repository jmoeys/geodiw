<?php



// Script options:
$login_check    = 'login_check.php'; 
$forbid_noLogin = 'forbid_noLogin.php'; 
$bottom         = '../bottom.php'; 
$rootDir        = '../';  
$home           = '../index.php';
$configLocal    = '../config/config.local.php'; 
$updateForm     = 'inst_update_form.php'; 
$editLab        = 'inst_edit.php'; 
$home           = '../index.php'; 
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



if( !isset($_SESSION['LAB_ID']) ){ 
    die( 'Error: no ID provided. <a href="' . $home . '">Return to homepage</a>.<br>' ); 
    exit; 
}else{ 
    $editForm = $editLab . '?id=' . $_SESSION['LAB_ID'];  
}    



//check for required fields from the form
if(  (!isset($_POST["LAB_ID"])) ){
    die("Error: Some input fields are missing. Try again. <a href='" . $editForm . "'>Return to the edit page</a>.<br>");
    exit;
}



// Load loacl config (database)
include($configLocal); 



//connect to server and select database
$mysqli = mysqli_connect( $dbHostX, $dbUserX, $dbPwdX, $dbNameX ); 



// Sanitise strings that will be passed to the database:
$LAB_ID      = addslashes( stripslashes( $_POST["LAB_ID"] ) ); 
$LAB_ID      = mysqli_real_escape_string( $mysqli, $LAB_ID ); 



// Check that the strings were not "altered"
if( !($LAB_ID == $_POST["LAB_ID"]) ){
  die("Error: forbidden character(s) detected in LAB_ID. Try again. <a href='" . $updateForm . "'>Return to the edit page</a>.<br>");
  exit;
} 



// Retrieve the list of countries:
$sql = 'DELETE FROM `ssld_labs` '. 
       'WHERE `LAB_ID`=' . $LAB_ID; 

if( $debug == 1 ){ echo( $sql . '<br>' ); }  



$result = $mysqli->query( $sql ); 
if( !$result === TRUE ){
    die( "Error:" . $mysqli -> error );
    $mysqli->close(); exit;
}  



// Mark the item as "updated":
$_SESSION['DELETED'] = 1; 
$_SESSION['DELETED_NAME'] = $_POST["LAB_NAME"]; 



// Back to the edit page:
header( 'Location: ' . $home ); 



?>

