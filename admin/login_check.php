<?php 

// Script options:
// $loginPage    = 'login.html'; 
// $loginPage    = 'login.html'; 
if( !isset($debug) ){ $debug = 0; } 



// Start a Session:
if( (ini_get( 'session.auto_start' ) == "0") & !isset($_REQUEST['_SESSION']) ){ session_start(); }
//if( !isset($_REQUEST['_SESSION']) ){ session_start(); } 
//echo( ini_get( 'session.auto_start' ) . PHP_EOL ); 


//check if the user is authentified:

// - 1 - Check if a cookies has been registered for this session:
if( !isset( $_SESSION["cookieName"] ) ){ 
	$_SESSION["isLogged"] = 0; 

    if( $debug == 1 ){ echo("Cookie name not set in session<br>"); }

	//header( 'Location: ' . $loginPage );
	//exit;

// - 2 - If a cookie has been registered in the session, check that it exists 
}else{  
    $cookieName = $_SESSION["cookieName"]; 
    
    // If the cookie does not exists, then no login
    if( !isset( $_COOKIE[ $cookieName ] ) ){ 
	$_SESSION["isLogged"] = 0; 


    if( $debug == 1 ){ echo("The cookie was not found<br>"); }

	//header( 'Location: ' . $loginPage );
    //exit;

    // - 3 - If the cookie exists, check that the hashkey is the same as for the cession:
    }else{ 
        $hashKey = $_COOKIE[ $cookieName ]; 
        
        if( !($hashKey == $_SESSION['hashKey']) ){ 
	    $_SESSION["isLogged"] = 0; 

        if( $debug == 1 ){ echo("Hashkeys not identical<br>"); }

	    //header( 'Location: ' . $loginPage );
        //exit;
        
        // if the hashkeys are the same, then mark the user as "logged"
        }else{ 
            $_SESSION["isLogged"] = 1; 
        }    
    }   
}  

?>

