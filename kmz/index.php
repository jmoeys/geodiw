<?php 

// Recursive call :: taken from Piwigo
$url = '../';
header( 'Request-URI: '.$url );
header( 'Content-Location: '.$url );
header( 'Location: '.$url );
exit();

?>

