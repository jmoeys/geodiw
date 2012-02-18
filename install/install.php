<?php

// Setup some options:
$configFile  = "../config/config.local.php"; 
$sqlUser     = "../sql/users.sql";
$sqlLabs     = "../sql/labs.sql";
$installForm = 'install_form.php';
$loginForm   = '../admin/login_form.php'; 
$debug       = 1; 



// Uncomment the code below to debug:
if( $debug == 1 ){ 
    ini_set('display_errors',1);
        error_reporting(E_ALL); 
    echo( 'Debug mode is on.<br>' ); 
}  


// test
printf( "Installation of the 'Soil Science Lab Directory' (SSLD) begins<br>" );



//check for required fields from the form
if(  (!isset($_POST["dbHost"]))  || (!isset($_POST["dbUser"]))   || (!isset($_POST["dbPwd"]))      || 
     (!isset($_POST["dbName"]))  || (!isset($_POST["admLogin"])) || (!isset($_POST["admPwd"]))     || 
     (!isset($_POST["admPwd2"])) || (!isset($_POST["admMail"]))  || (!isset($_POST["encryptKey"])) || 
     (!isset($_POST["admName"])) || (!isset($_POST["siteDomain"]))  ){
    die("Error: Some input fields are missing. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
    exit;
}
// (!isset($_POST["dbRoot"])) // database table name prefix


// Attributes the posted variables to PhP variables:
$dbHost     = filter_var( $_POST["dbHost"],     FILTER_SANITIZE_URL ); 
$dbUser     = filter_var( $_POST["dbUser"],     FILTER_SANITIZE_STRING ); 
$dbPwd      = filter_var( $_POST["dbPwd"],      FILTER_SANITIZE_STRING ); 
$dbName     = filter_var( $_POST["dbName"],     FILTER_SANITIZE_STRING ); 
$siteDomain = filter_var( $_POST["siteDomain"], FILTER_SANITIZE_URL ); 



// Check that all the provided variables are correct
if( !($dbHost == $_POST["dbHost"]) ){
  die("Error: forbidden character(s) detected in Database host address. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 
if( !($dbUser == $_POST["dbUser"]) ){
  die("Error: forbidden character(s) detected in Database user name. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
} 
if( !($dbPwd == $_POST["dbPwd"]) ){
  die("Error: forbidden character(s) detected in Database user password. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 
if( !($dbName == $_POST["dbName"]) ){
  die("Error: forbidden character(s) detected in Database name. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 
if( !($siteDomain == $_POST["siteDomain"]) ){
  die("Error: forbidden character(s) detected in site domain. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 



//connect to server and select database
$mysqli = new mysqli( $dbHost , $dbUser, $dbPwd, $dbName );
// $mysqli = mysqli_connect( $dbHost, $dbUser, $dbPwd, $dbName ); 

// check connection 
if( $mysqli->connect_errno ){
    $errors = sprintf("Connection to database failed: %s\n", $mysqli->connect_error);
    $mysqli->close(); 
    die( 'Error: ' . $errors );  
    exit;
}
// if (mysqli_connect_errno()) {
//     printf("Connect failed: %s\n", mysqli_connect_error());
//     exit();
// }



// Sanitise strings that will be passed to the database:
$dbRoot     = stripslashes( $_POST["dbRoot"] ); 
$dbRoot     = mysqli_real_escape_string( $mysqli, $dbRoot     ); // not used
$admLogin   = stripslashes( $_POST["admLogin"] ); 
$admLogin   = mysqli_real_escape_string( $mysqli, $admLogin   ); 
$admPwd     = stripslashes( $_POST["admPwd"] ); 
$admPwd     = mysqli_real_escape_string( $mysqli, $admPwd     ); 
$admPwd2    = stripslashes( $_POST["admPwd2"] ); 
$admPwd2    = mysqli_real_escape_string( $mysqli, $admPwd2    ); 
$admMail    = stripslashes( $_POST["admMail"] ); 
$admMail    = filter_var( $admMail, FILTER_SANITIZE_EMAIL );
$admMail    = mysqli_real_escape_string( $mysqli, $admMail    ); 
$encryptKey = stripslashes( $_POST["encryptKey"] ); 
$encryptKey = mysqli_real_escape_string( $mysqli, $encryptKey ); 
$admName    = stripslashes( $_POST["admName"] ); 
$admName    = mysqli_real_escape_string( $mysqli, $admName    ); 



// if( !($dbRoot = $_POST["dbRoot"]) ){
//   die("Error: forbidden character(s) detected in dbRoot. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
//   exit;
// } 
if( !($admLogin == $_POST["admLogin"]) ){
  die("Error: forbidden character(s) detected in administrator login. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 
if( !($admPwd == $_POST["admPwd"]) ){
  die("Error: forbidden character(s) detected in administrator password #1. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 
if( !($admPwd2 == $_POST["admPwd2"]) ){
  die("Error: forbidden character(s) detected in administrator password #2. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
} 
if( !($admMail == $_POST["admMail"]) ){
  die("Error: forbidden character(s) detected in administrator e-mail. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 
if( !($encryptKey == $_POST["encryptKey"]) ){
  die("Error: forbidden character(s) detected in the encryption string. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 
if( !($admName == $_POST["admName"]) ){
  die("Error: forbidden character(s) detected in the administrator name. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 



//Make sure the 2 provided passwords are identical
if( !($_POST["admPwd"] == $_POST["admPwd2"]) ){
  die("Error: the two passwords provided are not identical. Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 



// Convert the administrator password to crypt()
$admPwd = crypt( $admPwd, $encryptKey ); // consider using md5() if does not work



// Check that the file config.local.php does not exists yet: 
if( file_exists( $configFile ) ){
  die("Error: The file '".$configFile."' already exists. Please delete it or rename it.");
  exit;
} 



// Write a file containing the "database parameters"
// config.local.php
// that will be used by the application  
$string = '<?php' . PHP_EOL. 
    '$dbHostX     = "'. $dbHost.     '";' . PHP_EOL. 
    '$dbUserX     = "'. $dbUser.     '";' . PHP_EOL.
    '$dbPwdX      = "'. $dbPwd.      '";' . PHP_EOL.
    '$dbNameX     = "'. $dbName.     '";' . PHP_EOL.
    '$encryptKeyX = "'. $encryptKey. '";' . PHP_EOL.
    '$siteDomainX = "'. $siteDomain. '";' . PHP_EOL.
'?>' . PHP_EOL;
$fp = fopen($configFile, "w");
fwrite($fp, $string);
fclose($fp);



// Check that the file has been written
if( !file_exists( $configFile ) ){
	die("Error: The file '".$configFile."' (containing database specification) could not be created.");
	exit;
}



// Load the written php file
include $configFile;



// Check the the variables written are the same
if( !($dbHostX ==$dbHost) ){
  die("Error: Some variables were not written correctly in ".$configFile." (dbHostX). Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 
if( !($dbUserX == $dbUser) ){
  die("Error: Some variables were not written correctly in ".$configFile." (dbUserX). Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 
if( !($dbPwdX == $dbPwd) ){
  die("Error: Some variables were not written correctly in ".$configFile." (dbPwdX). Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 
if( !($dbNameX == $dbName) ){
  die("Error: Some variables were not written correctly in ".$configFile." (dbNameX). Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 
if( !($encryptKeyX == $encryptKey) ){
  die("Error: Some variables were not written correctly in ".$configFile." (dbHostX). Try again. <a href='" . $installForm . "'>Return to the install page</a>.<br>");
  exit;
} 



if( file_exists($sqlUser) ){ 
	$sqlUser = file_get_contents( $sqlUser ); 
}else{ 
	die("Error: Could not find the file " . $sqlUser ); 
	exit; 
}	#



if( $mysqli->multi_query( $sqlUser ) === TRUE ){
    printf("<dd>Table `ssld_users` successfully created<br>");
	while( $mysqli->next_result() );
}else{ 
    printf( "Error: Could not create the table `ssld_users`<br>");
    die( "Error:" . $mysqli -> error );
    $mysqli->close(); exit;
}   #

// if( mysqli_query($mysqli, "SOURCE ssld_users.sql") === TRUE ){
//     printf("Table ssld_users successfully created.\n");
// }



// Check that there is not already a user with the same login (just in case!)
// Check that the written password can be compared with the actual one
$getLogin = 'SELECT `USER_LOGIN` FROM `ssld_users` WHERE `USER_LOGIN` = "' . $admLogin . '"'; 
$result   = $mysqli->query( $getLogin, MYSQLI_USE_RESULT  ); 
$getLogin = $result->fetch_object(); 
//$getLogin = $getLogin->USER_LOGIN; 
//$getPwd = $result; 
$result->close();
$mysqli->next_result(); 
if( !empty($getLogin) ){ 
	die("Error: The administrator login chosen already exists in the database");
	exit; 
}	#



// Add the "admin" user to the database
$addAdmin = 
    'INSERT INTO `ssld_users` '.
    '( `USER_PWD`, `USER_MAIL`, `USER_LOGIN`, `USER_NAME`, `USER_LEVEL` )'. // `USER_ID`, 
    'VALUES ( "'. $admPwd   . '", "'. $admMail  . '", "'. $admLogin . '", "'. $admName  . '", "0" )'; 

// Delete that later
// printf( "SQL: " . $addAdmin . '<br>' ); 



if( $mysqli->query( $addAdmin ) === TRUE ){
    printf( "<dd>Admin data successfully added to `ssld_users`.<br>" );
}else{ 
    printf( "Error: Admin data could not be added to `ssld_users`<br>");
    die( "Error:" . $mysqli -> error );
    $mysqli->close(); exit;
}   #



// Fetch the admin ID in the database
$admID = $mysqli->insert_id; 
$mysqli->next_result(); 


// Check that the written password can be compared with the actual one
$getAdmin = 'SELECT `USER_PWD` FROM `ssld_users` WHERE `USER_ID` = ' . $admID; 

// Delete that later
// printf( "SQL: " . $getAdmin . "<br>" ); 



$result = $mysqli->query( $getAdmin, MYSQLI_USE_RESULT  ); 
$getPwd = $result->fetch_object(); 
$getPwd  = $getPwd->USER_PWD; 
//$getPwd = $result; 
$result->close();
$mysqli->next_result(); 


// Compare the passwords

//$admPwdIn = crypt( $admPwd, $encryptKey ); 
//printf( crypt( $admPwd, $encryptKeyX ) . '<br>'  ) ; 
//printf( $admPwdIn . '<br>'  ) ; 
//printf( $getPwd . '<br>'  ) ; 


if( !(  $admPwd == $getPwd ) ){
    die("Error: Actual password different from the one written in the database.");
    $mysqli->close(); exit;
}else{ 
	 printf( "<dd>Stored and input password are identical (good!)<br>" ); 
}	#



if( file_exists($sqlLabs) ){ 
	$sqlLabs = file_get_contents( $sqlLabs ); 
}else{ 
	die("Error: Could not find the file " . $sqlLabs ); 
	exit; 
}	#



// Create the "labs" table
if( $mysqli->multi_query( $sqlLabs ) === TRUE ){
    printf("<dd>Table `ssld_labs` successfully created<br>");
	while( $mysqli->next_result() );
}else{ 
    printf( "Error: Could not create the table `ssld_labs`<br>");
    die( "Error:" . $mysqli -> error );
    $mysqli->close(); exit;
}   #



// Close the database connection


print('End of installation.<a href="' . $loginForm . '">Go to the login page</a><br>');
?>

