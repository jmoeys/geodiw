<?php

// Uncomment the code below to debug:
// ini_set('display_errors',1);
//	error_reporting(E_ALL); 



// Script options:
$configLocal  = '../config/config.local.php'; 
$redirectPage = '../index.php';
$loginPage    = 'login_form.php';
$loginPage2   = 'login2.php'; 
$cookieExpire = time()+60*60*2; 
$cookiePath   = "/"; 
//$siteDomain = "77.235.246.202"; 
$cookieSecure = "0"; 
$cookieHTTP   = "1"; 




// Inspiration:
// http://phpeasystep.com/phptu/6.html 
// http://stackoverflow.com/questions/686481/combining-cookies-and-sessions 
// http://stackoverflow.com/questions/549/the-definitive-guide-to-forms-based-website-authentication/477585#477585 



// Start a Session:
session_start();



//check for required fields from the form
if ((!isset($_POST["userLogin"])) || (!isset($_POST["userPwd"]))) {
    echo("Login and/or password not provided");
    //sleep(2); 
	header( 'Location: ' . $loginPage );
	exit;
}



// Count the number of time this page has been viewed: 
if( isset($_SESSION['nbView']) ){
    $_SESSION['nbView'] = $_SESSION['nbView'] + 1;
}else{
    $_SESSION['nbView'] = 1;
    $_SESSION['randSeed'] = rand(1,10^6); 
}   



// Added a timer if that page has already been viewed, 
// in order to slow down brute force password search
if( $_SESSION['nbView'] > 2 ){
    $sleepTime = pow( $_SESSION['nbView'] - 2, 2); 
    printf( 'You already had ' . $_SESSION['nbView'] . ' login attempts. Please wait for ' . $sleepTime . 'secs before you can try again.<br>' ); 
    sleep( $sleepTime );
} 



// Load the local config:
if( file_exists( $configLocal ) ){ 
    include( $configLocal ); 
}else{ 
    die('Error: the local configuration file could not be loaded.');
    exit; 
}   


// Setup site domain
//$siteDomain = $siteDomainX; 


//connect to server and select database
$mysqli = mysqli_connect( $dbHostX, $dbUserX, $dbPwdX, $dbNameX );



// Sanitise the input:
$userLogin  = addslashes( stripslashes( $_POST["userLogin"] ) ); 
$userLogin  = mysqli_real_escape_string( $mysqli, $userLogin ); 
$userPwd    = addslashes( stripslashes( $_POST["userPwd"] ) ); 
//$siteDomain = "77.235.246.202";  
$userPwd    = mysqli_real_escape_string( $mysqli, $userPwd   ); 




// Check that there are no differences:
if( !($userLogin == $_POST["userLogin"]) ){
  die('Error: forbidden character(s) detected in user login. Try again. <a href="'.$loginPage.'">Return to the login page</a>.<br>');
  exit;
} 
if( !($userPwd == $_POST["userPwd"]) ){
  die('Error: forbidden character(s) detected in user password. Try again. <a href="'.$loginPage.'">Return to the login page</a>.<br>');
  exit;
} 



// Crypt the password, so it can be compared to what is in the database:
$userPwd = crypt( $userPwd, $encryptKeyX ); // consider using md5() if does not work



// Retrieve the user rows corresponding to the user login and password:
$sql = 'SELECT `USER_LOGIN`, `USER_NAME`, `USER_MAIL`, `USER_ID`, `USER_LEVEL` '.
    'FROM `ssld_users` '. 
    'WHERE `USER_LOGIN` = "' . $userLogin . '" AND `USER_PWD` = "'. $userPwd . '"'; 



$result = $mysqli->query( $sql ); 
if( !$result === TRUE ){
    die( "Error:" . $mysqli -> error );
    $mysqli->close(); exit;
}    


//get the number of rows in the result set; should be 1 if a match
if( mysqli_num_rows($result) == 1 ){

	//if authorized, get the values of f_name l_name
	while( $info = mysqli_fetch_array($result) ){
		$userName  = stripslashes( $info['USER_NAME']  ); 
		$userMail  = stripslashes( $info['USER_MAIL']  ); 
        $userID    = stripslashes( $info['USER_ID']    );
        $userLevel = stripslashes( $info['USER_LEVEL'] );
	}
    
    // Close the database:
    $mysqli->close();
    
    // Set the session additional parameters:
    $_SESSION['userLogin']  = $userLogin; 
    $_SESSION['userName']   = $userName; 
    $_SESSION['userMail']   = $userMail; 
    $_SESSION['userID']     = $userID; 
    $_SESSION['userLevel']  = $userLevel; 
    $hashKey = $userLogin . strval( $_SESSION['randSeed'] ) . strval($userID); 
    $hashKey = md5( $hashKey ); 
    $_SESSION['hashKey']    = $hashKey; 
    //$cookieName = 'ssld_auth'; 
    $cookieName = 'ssld_auth_'.$userLogin; 
    //$cookieDomain = $siteDomainX; 
    $_SESSION['cookieName'] = $cookieName;  

    //set authorization cookie
    $cookieOut = setcookie( $cookieName, $hashKey, $cookieExpire, $cookiePath,  $siteDomainX, $cookieSecure , $cookieHTTP ); 

     
    /* echo( "cookieName = " . $cookieName . "<br>" ); 
    echo( "hashKey = " . $hashKey . "<br>" ); 
    echo( "cookieExpire = " . $cookieExpire . "<br>" ); 
    echo( "cookiePath = " . $cookiePath . "<br>" ); 
    echo( "cookieDomain = " .  $siteDomainX . "<br>" ); 
    echo( "cookieSecure = " . $cookieSecure . "<br>" ); 
    echo( "cookieHTTP = " . $cookieHTTP . "<br>" ); */
    
    //sleep(10); 

    //if( !isset( $_COOKIE[ $cookieName ] ) ){ // $cookieName
    if( !($cookieOut == 1) ){ // $cookieName
	// echo( $cookieOut . "<br><br>" ); 
        die( 'Error: the authentification cookie could not be set (1).<br>' );
        exit;
    } /* else{ 
        header( 'Location: ' . $loginPage2 ); 
    } */

    
    // Reset the login counter
    $_SESSION['nbView'] = 0; 



    // Go to the administration page:
    echo( '<META HTTP-EQUIV="Refresh" Content="0; URL=' . $redirectPage . '">' );    
    //header( 'location:' . $redirectPage );
}else{
	//redirect back to login form if not authorised
	die( 'Error: Login failed. Either user login or user password are wrong (or both). Try again. <a href="'.$loginPage.'">Return to the login page</a>.<br>');
	exit;
}



?>

