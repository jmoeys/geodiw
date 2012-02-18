<?php

// Options
$footer           = 'footer.php'; 
$rootDir          = ''; 
$geodiwcss        = 'geodiw.css'; 
$langFile         = 'lang/lang.php'; 
$debug            = 0; 



// Set the language:
include_once($langFile); 



if( $debug == 1 ){ 
    ini_set('display_errors',1);
	    error_reporting(E_ALL); 
    echo( 'Debug mode is on.<br>' ); 
}   

?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <?php echo( '<link href="' . $geodiwcss . '" rel="stylesheet" type="text/css">' ); ?> 
    <title><?php echo( $lang[ 'h1_main' ] ); ?></title>
    <!-- <script type = 'text/javascript' src="lib/sorttable.js"></script> -->
</head>

<body>



<h1><?php echo( $lang[ 'h1_main' ] ); ?></h1>

<p align="right"><?php echo( $lang[ 'index_p_lang' ] . '<a href="' . basename($_SERVER['PHP_SELF']) . '?lang=fr">fr</a>, <a href="' . basename($_SERVER['PHP_SELF']) . '?lang=en">en</a>' ); ?></p>

<h2><?php echo( $lang[ 'world_h2_intro' ] ); ?></h2>

<p><i>Map to be included later</i></p>

<?php include($footer) ?> 

</body>
</html>

