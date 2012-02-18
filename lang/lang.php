<?php 

// Options:
$defaultLang      = 'fr.php'; 
$langDir          = 'lang/'; 
if( !isset($rootDir) ){ $rootDir = '..'; }



$lang = array(); // Initiate the variable array



// Set the language:
if( isset( $_GET["lang"] ) ){ 

    // Sanitise the input ID:
    $getLang = $_GET["lang"]; 
    $getLang = filter_var( $_GET["lang"], FILTER_SANITIZE_STRING ); 
    if( !$getLang === $_GET["lang"] ){ 
        $getLang = $rootDir . $langDir . $defaultLang; 
    }else{ 
        $getLang = $rootDir . $langDir . $getLang . '.php';  
        
        if( !file_exists( $getLang ) ){
            $getLang = $rootDir . $langDir . $defaultLang; 
        }   
    }     
    // include_once( $langDir . $defaultLang ); 
    // Load the default language, so if other language file 
    // are incomplete, something is displayed
    include_once( $rootDir . $langDir . $defaultLang ); 

    // Load chosen language:
    include_once( $getLang ); 

}else{ 
    include_once( $rootDir . $langDir . $defaultLang ); 
    // echo($langDir . $defaultLang . '<br>' ); 
}  
?>
