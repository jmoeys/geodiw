<?php

// Script options:
$footer           = 'footer.php'; 
$rootDir          = ''; 
$configLocal      = 'config/config.local.php';  
$langFile         = 'lang/lang.php'; 
$login_check      = 'admin/login_check.php'; 
$manageUsers      = 'admin/users_manage.php'; 
$preferences      = 'admin/preferences.php'; 
$createKML        = 'kmz.php'; 
$getKMZ           = 'kmz/institutions.kmz'; 
$home             = 'index.php';
$login            = 'admin/login_form.php'; 
$logout           = 'admin/logout.php'; 
$editLab          = 'admin/inst_edit.php'; 
$addLab           = 'admin/inst_add_form.php'; 
$worldMap         = 'world.php'; 
$worldImage       = 'images/world.png'; 
// $defaultLang   = 'fr.php'; 
// $langDir       = 'lang/'; 
$geodiwcss        = 'geodiw.css'; 
$debug            = 0; 



if( $debug == 1 ){ 
    ini_set('display_errors',1);
	    error_reporting(E_ALL); 
    echo( 'Debug mode is on.<br>' ); 
}   



// Page generation timing: Start
// http://www.phpjabbers.com/measuring-php-page-load-time-php17.html 
$time  = microtime();
$time  = explode(' ', $time);
$time  = $time[1] + $time[0];
$start = $time;



// Load loacl config (database)
include_once($configLocal); 



// Set the language:
include_once($langFile); 

/* // Set the language:
if( isset( $_GET["lang"] ) ){ 

    // Sanitise the input ID:
    $getLang = $_GET["lang"]; 
    $getLang = filter_var( $_GET["lang"], FILTER_SANITIZE_STRING ); 
    if( !$getLang === $_GET["lang"] ){ 
        $getLang = $langDir . $defaultLang; 
    }else{ 
        $getLang = $langDir . $getLang . '.php';  
        
        if( !file_exists( $getLang ) ){
            $getLang = $langDir . $defaultLang; 
        }   
    }     
    // include_once( $langDir . $defaultLang ); 
    include_once( $getLang ); 

}else{ 
    include_once( $langDir . $defaultLang ); 
    // echo($langDir . $defaultLang . '<br>' ); 
}   */    




// Test if the user is authorised (logged in), 
include_once( $login_check ); 



//connect to server and select database
$mysqli = mysqli_connect( $dbHostX, $dbUserX, $dbPwdX, $dbNameX );



// Retrieve the list of countries:
$sql = 'SELECT DISTINCT `LAB_COUNTRY` FROM `ssld_labs` ORDER BY `LAB_COUNTRY` ASC'; 



$result = $mysqli->query( $sql ); 
if( !$result === TRUE ){
    die( "Error:" . $mysqli -> error );
    $mysqli->close(); exit;
}   



if( $result ){ 
    
    if( $_SESSION["isLogged"] == 1 ){ 
        $editLabHeader = ' <th>Edit</th>'; 
    }else{ 
        $editLabHeader = ''; 
    }
    
    $rowNb = 0; $countryList = array(); $countryHTML = '';  

    while( $newRow = $result->fetch_array(MYSQLI_ASSOC) ){ 
        $rowNb = $rowNb + 1; 
        
        // Add the country to the country list:
        $country = $newRow[ 'LAB_COUNTRY' ];
        $countryList[ $rowNb ] = $country; 

        // Format the country HTML list:
        $countryHTML = $countryHTML . '<a href="#' . $country . '">' . $country . '</a>; ';
    }   
    // Free the results
    $result->free(); 

    // Close the database:
    // $mysqli->close();



    // Initiate the table list
    $tblList = ''; 


    
    // Now fetch the data country by country and format the table:
    foreach( $countryList as $country ){ 
        
        // Retrieve the user rows corresponding to the user login and password:
        $sql2 = "SELECT * FROM `ssld_labs` WHERE `LAB_COUNTRY` = '" . $country . "' ORDER BY `LAB_CITY` ASC, `LAB_NAME` ASC"; //  



        $result2 = $mysqli->query( $sql2 ); 
        if( !$result2 === TRUE ){
            die( "Error:" . $mysqli -> error );
             $mysqli->close(); exit;
        }    
        
        
 
        if( $result ){ 

            // Get the number of rows
            $nbRows = $result2->num_rows;

            // Initiate the table
            $tblBody = ""; 
            $tblHead = 
                    '        <tr>' . PHP_EOL. 
                    '            <th>' . $lang[ 'index_lab_table_id' ] . '</th> <th>' . $lang[ 'index_lab_table_name' ] . '</th> <th>' . $lang[ 'index_lab_table_location' ] . '</th> <th>' . $lang[ 'index_lab_table_desc' ] . '</th>'.
                    '            <th>' . $lang[ 'index_lab_table_url' ] . '</th> <th>' . $lang[ 'index_lab_table_inst' ] . '</th> <th>' . $lang[ 'index_lab_table_city' ] . '</th> <th>' . $lang[ 'index_lab_table_country' ] . '</th>' . $editLabHeader . PHP_EOL.
                    '        </tr>' . PHP_EOL; 


            // Loop over each rows
            while( $newRow = $result2->fetch_array(MYSQLI_ASSOC) ){ 
            //for( $rowNb = 1; $rowNb = 1; $rowNb++ ){ // $nbRows
            //    $newRow = $result->fetch_array(MYSQLI_ASSOC); 

                if( $_SESSION["isLogged"] == 1 ){ 
                        $editLabRow    = ' <td><a href="' . $editLab . '?id=' . $newRow[ 'LAB_ID' ] . '">edit</a></td>'; 
                }else{ 
                        $editLabRow    = ''; 
                }
                
                
                
                $tblBody = 
                $tblBody. 
                '        <tr>'. PHP_EOL . 
                '            <td>' . $newRow[ 'LAB_ID' ] . '</td>' . 
                            '<td><b>' . $newRow[ 'LAB_NAME' ] . '</b></td>' . 
                            '<td><a href="http://maps.google.com/maps?ie=UTF8&ll=' . $newRow[ 'WGS_LAT' ] . ',' . $newRow[ 'WGS_LONG' ] . '&z=14">' . $lang[ 'index_lab_table_map' ] . '</a>' .  '</td>' . 
                            //'<td>' . $newRow[ 'WGS_LAT' ] . '</td>' . 
                            //'<td>' . $newRow[ 'WGS_LONG' ] . '</td>' . 
                            '<td>' . $newRow[ 'LAB_DESC' ] . '</td>' . 
                            '<td><a href="' . $newRow[ 'LAB_URL' ] . '">' . $lang[ 'index_lab_table_url' ] . '</td>' . 
                            '<td>' . $newRow[ 'LAB_INST' ] . '</td>' . 
                            '<td>' . $newRow[ 'LAB_CITY' ] . '</td>' . 
                            '<td>' . $newRow[ 'LAB_COUNTRY' ] . '</td>' . 
                            $editLabRow . PHP_EOL.
                '        </tr>' . PHP_EOL;

                    // if( $newRow[ 'LAB_ID' ] == 10 ){ break; } 
            }

            $tbl = 
            '<h3><a name="' . $country . '">' . $country . '</a></h3>' . PHP_EOL . PHP_EOL.
            '<table class="geodiwTable">' . PHP_EOL. 
            '   <thead>' . PHP_EOL. 
            $tblHead. 
            '   </thead>' . PHP_EOL. 
            '   <tbody>' . PHP_EOL. 
            '       <?php echo( $tbl . PHP_EOL ) ?>' . PHP_EOL. 
            $tblBody. 
            '</table>' . PHP_EOL . PHP_EOL; 
            

            
            // Pile up to the existing table list:
            $tblList = $tblList . $tbl;  

            // Free the results
            $result2->free(); 
         }
    }    
    // Close the database:
    $mysqli->close();    
}else{ 
    $countryHTML = '';  
    $tblList     = ''; 
}



// Case when an item has just been added to the database:
if( isset($_SESSION['ADDED']) ){ 
    if( $_SESSION['ADDED'] == 1 ){ 
        $addMessage = '<p style="color:red;">The lab ' . $_SESSION['ADDED_NAME'] . ' has been successfully added.</p>'; 
        $_SESSION['ADDED'] = 0; 
        $_SESSION['ADDED_NAME'] = "0"; 
    }else{ 
        $addMessage = ''; 
    }      
}else{ 
    $addMessage = ''; 
}   



// Case when an item has just been deleted from the database:
if( isset( $_SESSION['DELETED'] ) ){ 
    if( $_SESSION['DELETED'] == 1 ){ 
        $deleteMessage = '<p style="color:red;">The item ' . $_SESSION['DELETED_NAME'] . ' has been deleted.</p>'; 
        $_SESSION['DELETED'] = 0; 
        $_SESSION['DELETED_NAME'] = "0"; 
    }else{ 
        $deleteMessage = ''; 
    }   
}else{ 
    $deleteMessage = ''; 
}   



// Bottom-page menu, different if the user is logged in or nor:
if( $_SESSION["isLogged"] == 1 ){ 
    
    if( $_SESSION['userLevel'] == 0 ){ 
        $moreMenu = 
        '    <li><a href="' . $manageUsers . '">' . $lang[ 'menu_manage_user' ] . '</a></li>' . PHP_EOL. 
        '    <li><a href="' . $preferences . '">' . $lang[ 'menu_pref' ] . '</a></li>' . PHP_EOL; 
    }else{ 
        $moreMenu = ''; 
    }   
    
    $menu = '<ul class="geodiwMenu">' . PHP_EOL. 
    '    <li><a href="' . $home . '">' . $lang[ 'menu_home' ] . '</a></li>' . PHP_EOL. 
    '    <li><a href="' . $addLab . '">' . $lang[ 'menu_add_entry' ] . '</a></li>' . PHP_EOL. 
    $moreMenu. 
    '    <li><a href="' . $createKML . '">' . $lang[ 'menu_renew_kmz' ] . '</a></li>' . PHP_EOL. 
    '    <li><a href="' . $logout . '">' . $lang[ 'menu_logout' ] . '</a></li>' . PHP_EOL. 
    '</ul>' . PHP_EOL;
    
    // Format the displayed user name:
    $welcomeUser = $_SESSION['userName'] . ' (' . $_SESSION['userMail'] . ')'; 
    $welcome = '<p>' . sprintf( $lang[ 'index_welcome' ], $welcomeUser ) . '.</p>';
}else{ 
    $menu = '<ul class="geodiwMenu">' . PHP_EOL. 
    '    <li><a href="' . $home . '">' . $lang[ 'menu_home' ] . '</a></li>' . PHP_EOL. 
    '    <li><a href="' . $login . '">' . $lang[ 'menu_login' ] . '</a></li>' . PHP_EOL. 
    '</ul>' . PHP_EOL;
    
    // Format the displayed user name:
    $welcome = '';
}     



// Page generation timing: end
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);

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

<?php echo($welcome); ?>

<?php echo($addMessage); ?>

<?php echo($deleteMessage); ?>

<p align="right"><?php echo( $lang[ 'index_p_lang' ] . '<a href="' . basename($_SERVER['PHP_SELF']) . '?lang=fr">fr</a>, <a href="' . basename($_SERVER['PHP_SELF']) . '?lang=en">en</a>' ); ?></p>

<h2><?php echo( $lang[ 'index_h2_about' ] ); ?></h2>

<p><?php echo( $lang[ 'index_p_about' ] ); ?></p>


<h2><?php echo( $lang[ 'index_h2_world_map' ] ); ?></h2>

<?php echo( '<a href="' . $worldMap . '"><img src="' . $worldImage . '" alt="World Map of Institutions"/></a>' ); ?> 

<p><?php sprintf( $lang[ 'index_p_world_map' ], $getKMZ ); ?></p>

<h2><?php echo( $lang[ 'index_h2_countries_list' ] ); ?></h2>

<?php echo( $countryHTML . PHP_EOL . PHP_EOL ); ?>

<br>

<h2><?php echo( $lang[ 'index_h2_lab_country_tables' ] ); ?></h2>

<!-- <script type = 'text/javascript' src="lib/sorttable.js"></script> -->

<!-- BIG LIST OF TABLES COUNTRY BY COUNTRY -->

<?php echo( $tblList . PHP_EOL . PHP_EOL ); ?>

<br>

<?php echo( $menu ); ?>

<?php include($footer) ?> 

<small><font color="gray">
    <?php 
        echo( 'Page generated in ' . $total_time . ' seconds. '); 
        $time    = microtime();
        $time    = explode(' ', $time);
        $time    = $time[1] + $time[0];
        $finish2 = $time;
        $total_time2 = round(($finish2 - $start), 4); 
    echo( 'php + html ' . $total_time2 . ' seconds.' . PHP_EOL ); 
    ?>
</font></small>

</body>
</html>

