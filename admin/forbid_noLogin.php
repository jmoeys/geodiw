<?php 

// Script options:
$loginPage    = 'login_form.php'; 
if( !isset($debug) ){ $debug = 0; } 



// Set the correct path for login form
if( isset($rootDir) ){ 
    if( $rootDir == '' ){ 
        $loginPage = 'admin/' . $loginPage; 
    }else{ 
        $loginPage = $rootDir . '/admin/' . $loginPage; 
    }   
}   


// Start a Session:
session_start();



//check if the user is authentified: 

// - 1 -  Check that the session variable "isLogged" has been set
if( !isset( $_SESSION["isLogged"] ) ){
    // die("Error: isLogged is not set"); 
    header( 'Location: ' . $loginPage );
    exit;

// - 2 - If the session variable "isLogged" has been set, check that it is set to 1 = logged
}else{  

    // If the session variable isLogged is different from 1, then redirect to login:
    if( !($_SESSION["isLogged"] == 1) ){ 
        //die("Error: isLogged is not equal to 1"); 
        header( 'Location: ' . $loginPage );
        exit;
    }  
} 

?>

