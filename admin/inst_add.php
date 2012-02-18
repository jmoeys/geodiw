<?php



// Script options:
$login_check    = 'login_check.php'; 
$forbid_noLogin = 'forbid_noLogin.php'; 
$footer         = '../footer.php'; 
$rootDir        = '../';  
$home           = '../index.php';
$configLocal    = '../config/config.local.php'; 
$addForm        = 'inst_add_form.php'; 
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



// Save the $_POST values as $_SESSION values for eventual reuse.
if( isset( $_POST ) ){ 
    foreach( $_POST as $key => $value ){
        $_SESSION['post_add_val'][$key] = $value;
    }   
}



//check for required fields from the form
if( (!isset($_POST["LAB_NAME"])) || (!isset($_POST["WGS_LAT"]))  || (!isset($_POST["WGS_LONG"]))    || 
    (!isset($_POST["LAB_DESC"])) || (!isset($_POST["LAB_CITY"])) || (!isset($_POST["LAB_COUNTRY"])) || 
    (!isset($_POST["LAB_URL"]))  || (!isset($_POST["LAB_CITY"])) ){
    die("Error: Some input fields are missing. Try again. <a href='" . $addForm . "'>Return to the edit page</a>.<br>");
    exit;
}



//check if required fields are not empty:
if( ($_POST["LAB_NAME"] == "") || ($_POST["LAB_CITY"] == "") || ($_POST["LAB_COUNTRY"] == "") || 
    ($_POST["LAB_URL"] == "" ) ){
    die("Error: Some input fields are empty. Try again. <a href='" . $addForm . "'>Return to the edit page</a>.<br>");
    exit;
} 



// Load loacl config (database)
include($configLocal); 



//connect to server and select database
$mysqli = mysqli_connect( $dbHostX, $dbUserX, $dbPwdX, $dbNameX ); 



// Sanitise strings that will be passed to the database:
$LAB_NAME    = addslashes( stripslashes( htmlentities( $_POST["LAB_NAME"], ENT_QUOTES ) ) ); 
$LAB_NAME    = mysqli_real_escape_string( $mysqli, $LAB_NAME ); 

$WGS_LAT     = addslashes( stripslashes( htmlentities( $_POST["WGS_LAT"], ENT_QUOTES ) ) ); 
$WGS_LAT     = mysqli_real_escape_string( $mysqli, $WGS_LAT ); 

$WGS_LONG    = addslashes( stripslashes( htmlentities( $_POST["WGS_LONG"], ENT_QUOTES ) ) ); 
$WGS_LONG    = mysqli_real_escape_string( $mysqli, $WGS_LONG ); 

$LAB_DESC    = addslashes( stripslashes( htmlentities( $_POST["LAB_DESC"], ENT_QUOTES ) ) ); 
$LAB_DESC    = mysqli_real_escape_string( $mysqli, $LAB_DESC ); 

$LAB_URL     = addslashes( stripslashes( htmlentities( $_POST["LAB_URL"], ENT_QUOTES ) ) ); 
$LAB_URL     = mysqli_real_escape_string( $mysqli, $LAB_URL ); 

$LAB_INST    = addslashes( stripslashes( htmlentities( $_POST["LAB_INST"], ENT_QUOTES ) ) ); 
$LAB_INST    = mysqli_real_escape_string( $mysqli, $LAB_INST ); 

$LAB_CITY    = addslashes( stripslashes( htmlentities( $_POST["LAB_CITY"], ENT_QUOTES ) ) ); 
$LAB_CITY    = mysqli_real_escape_string( $mysqli, $LAB_CITY ); 

$LAB_COUNTRY = addslashes( stripslashes( htmlentities( $_POST["LAB_COUNTRY"], ENT_QUOTES ) ) ); 
$LAB_COUNTRY = mysqli_real_escape_string( $mysqli, $LAB_COUNTRY ); 



// Check that the strings were not "altered"
/* if( !($LAB_NAME == $_POST["LAB_NAME"]) ){
  die("Error: forbidden character(s) detected in LAB_NAME. Try again. <a href='" . $addForm . "'>Return to the add page</a>.<br>");
  exit;
}  */ 
if( !($WGS_LAT == $_POST["WGS_LAT"]) ){
  die("Error: forbidden character(s) detected in WGS_LAT. Try again. <a href='" . $addForm . "'>Return to the add page</a>.<br>");
  exit;
} 
if( !($WGS_LONG == $_POST["WGS_LONG"]) ){
  die("Error: forbidden character(s) detected in WGS_LONG. Try again. <a href='" . $addForm . "'>Return to the add page</a>.<br>");
  exit;
} 
/* if( !($LAB_DESC == $_POST["LAB_DESC"]) ){
  die("Error: forbidden character(s) detected in LAB_DESC. Try again. <a href='" . $addForm . "'>Return to the add page</a>.<br>");
  exit;
} */ 
if( !($LAB_URL == $_POST["LAB_URL"]) ){
  die("Error: forbidden character(s) detected in LAB_URL. Try again. <a href='" . $addForm . "'>Return to the add page</a>.<br>");
  exit;
} 
/* if( !($LAB_INST == $_POST["LAB_INST"]) ){
  die("Error: forbidden character(s) detected in LAB_INST. Try again. <a href='" . $addForm . "'>Return to the add page</a>.<br>");
  exit;
} 
if( !($LAB_CITY == $_POST["LAB_CITY"]) ){
  die("Error: forbidden character(s) detected in LAB_CITY. Try again. <a href='" . $addForm . "'>Return to the add page</a>.<br>");
  exit;
}   
if( !($LAB_COUNTRY == $_POST["LAB_COUNTRY"]) ){
  die("Error: forbidden character(s) detected in LAB_COUNTRY. Try again. <a href='" . $addForm . "'>Return to the add page</a>.<br>");
  exit;
} */  



if( !isset($_POST["WGS_LAT"]) ){
    $WGS_LAT = 'VALUE(NULL)'; 
}else{ 
    $WGS_LAT = '"' . $WGS_LAT . '"'; 
}      
if( !isset($_POST["WGS_LONG"]) ){
    $WGS_LONG = 'VALUE(NULL)'; 
}else{ 
    $WGS_LONG = '"' . $WGS_LONG . '"'; 
} 
if( !isset($_POST["LAB_DESC"]) ){
    $LAB_DESC = 'VALUE(NULL)'; 
}else{ 
    $LAB_DESC = '"' . $LAB_DESC . '"'; 
}   
if( !isset($_POST["LAB_INST"]) ){
    $LAB_INST = 'VALUE(NULL)'; 
}else{ 
    $LAB_INST = '"' . $LAB_INST . '"'; 
}    
$LAB_NAME = '"' . $LAB_NAME . '"'; 
$LAB_URL = '"' . $LAB_URL . '"'; 
$LAB_CITY = '"' . $LAB_CITY . '"'; 
$LAB_COUNTRY = '"' . $LAB_COUNTRY . '"'; 



// Insert the value in the database
$sql = 'INSERT INTO `ssld_labs` '. 
       '(`LAB_NAME`, `WGS_LAT`, `WGS_LONG`, `LAB_DESC`, `LAB_URL`, `LAB_INST`, `LAB_CITY`, `LAB_COUNTRY`) '. 
       'VALUES( '. $LAB_NAME . ', '. $WGS_LAT . ', '. $WGS_LONG . ', '. $LAB_DESC . ', '. $LAB_URL . ', '. 
       $LAB_INST . ', '. $LAB_CITY . ', '. $LAB_COUNTRY . ')'; 

if( $debug == 1 ){ echo( $sql . '<br>' ); } 



$result = $mysqli->query( $sql ); 
if( !$result === TRUE ){
    die( "Error:" . $mysqli -> error );
    $mysqli->close(); exit;
}else{ 
    $mysqli->close(); 
}   



// Mark the item as "updated":
$_SESSION['ADDED'] = 1; 
$_SESSION['ADDED_NAME'] = $LAB_NAME; 

// Delete the item POST data:
unset( $_SESSION['post_add_val'] ); 



// Back to the edit page:
header( 'Location: ' . $home );  



?>

