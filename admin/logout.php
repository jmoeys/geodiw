<?php 

// parameters
$redirectPage = '../index.php';



// Use sessions
session_start();

// Smear the cookie:
$params = session_get_cookie_params();

setcookie( $_SESSION['cookieName'], '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"] );



// Destroy session variables:
$_SESSION = array(); 

// Further destroy the session
session_destroy();

if( isset($_SESSION["isLogged"]) ){ die('Error: we have a problem'); }

// Redirect to the homepage:
header( 'location:' . $redirectPage );

?>

