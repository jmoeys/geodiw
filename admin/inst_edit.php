<?php




// Script options:
$login_check    = 'login_check.php'; 
$forbid_noLogin = 'forbid_noLogin.php'; 
$bottom         = '../bottom.php'; 
$rootDir        = '../';  
$manageUsers    = 'users_manage_users.php'; 
$preferences    = 'preferences.php'; 
$home           = '../index.php';
$logout         = 'logout.php'; 
$configLocal    = '../config/config.local.php'; 
$labUpdate      = 'inst_update.php'; 
$labDelete      = 'inst_delete.php'; 
$geodiwcss      = '../geodiw.css'; 
$debug          = 0; 



// Uncomment the code below to debug:
if( $debug == 1 ){ 
    ini_set('display_errors',1);
        error_reporting(E_ALL); 
}   



// Test if the user is authorised (logged in), 
include( $login_check ); 
include( $forbid_noLogin ); 



if( isset( $_GET["id"] ) ){ 

    // Sanitise the input ID:
    // $getID = $_GET["id"]; 
    $getID = filter_var( $_GET["id"], FILTER_SANITIZE_NUMBER_INT ); 
    if( !$getID === (int)$_GET["id"] ){ 
        die( 'Error: Invalid ID' ); 
        exit; 
    }      



    // Set the ID as a session variable (for use in dependent pages)
    $_SESSION['LAB_ID'] = $getID; 
    

    // Load loacl config (database)
    include($configLocal); 



    //connect to server and select database
    $mysqli = mysqli_connect( $dbHostX, $dbUserX, $dbPwdX, $dbNameX );



    // Retrieve the list of countries:
    $sql = 'SELECT * FROM `ssld_labs` WHERE `LAB_ID` = ' . $getID ; 



    $result = $mysqli->query( $sql ); 
    if( !$result === TRUE ){
        die( "Error:" . $mysqli -> error );
        $mysqli->close(); exit;
    }   

    if( $result ){

        if( $result->num_rows == 0 ){ 
            $formTable = '<p>No lab selected, or ID not found in the database</p>'; 
            $getID     = 'no id provided.';   
        }else{ 
 
            while( $newRow = $result->fetch_array(MYSQLI_ASSOC) ){ 
                $LAB_ID      = $newRow[ 'LAB_ID' ]; 
                $LAB_NAME    = $newRow[ 'LAB_NAME' ]; 
                $WGS_LAT     = $newRow[ 'WGS_LAT' ]; 
                $WGS_LONG    = $newRow[ 'WGS_LONG' ]; 
                $LAB_DESC    = $newRow[ 'LAB_DESC' ]; 
                $LAB_URL     = $newRow[ 'LAB_URL' ]; 
                $LAB_INST    = $newRow[ 'LAB_INST' ]; 
                $LAB_CITY    = $newRow[ 'LAB_CITY' ]; 
                $LAB_COUNTRY = $newRow[ 'LAB_COUNTRY' ]; 
            } 
            
            // Format the form table:
            $formTable = '<!-- Update form -->' . PHP_EOL. 
            '<form method="post" action="' . $labUpdate . '">' . PHP_EOL. 
            '    <table class="ghost">' . PHP_EOL. 

            '    <thead>' . PHP_EOL. 
            '        <th></th><th></th>' . PHP_EOL. 
            '    </thead>' . PHP_EOL. 

            '    <tbody>' . PHP_EOL. 

            '        <tr>' . PHP_EOL. 
            '            <td>ID*:</td><td> <input name="LAB_ID" readonly="readonly" value="' . $LAB_ID . '"> </td>' . PHP_EOL. 
            '        </tr>' . PHP_EOL. 

            '        <tr>' . PHP_EOL. 
            '            <td>Name*:</td><td> <input name="LAB_NAME" type="text" value="' . $LAB_NAME . '"> </td>' . PHP_EOL. 
            '        </tr>' . PHP_EOL. 

            '        <tr>' . PHP_EOL. 
            '            <td>Latitude (WGS):</td><td> <input name="WGS_LAT" type="text" value="' . $WGS_LAT . '"> </td>' . PHP_EOL. 
            '        </tr>' . PHP_EOL. 

            '        <tr>' . PHP_EOL. 
            '            <td>Longitude (WGS):</td><td> <input name="WGS_LONG" type="text" value="' . $WGS_LONG . '"> </td>' . PHP_EOL. 
            '        </tr>' . PHP_EOL. 

            '        <tr>' . PHP_EOL. 
            '            <td>Description:</td><td> <input name="LAB_DESC" type="text" value="' . $LAB_DESC . '"> </td>' . PHP_EOL. 
            '        </tr>' . PHP_EOL. 

            '        <tr>' . PHP_EOL. 
            '            <td>URL*:</td><td> <input name="LAB_URL" type="text" value="' . $LAB_URL . '"> </td>' . PHP_EOL. 
            '        </tr>' . PHP_EOL. 

            '        <tr>' . PHP_EOL. 
            '            <td>Institution:</td><td> <input name="LAB_INST" type="text" value="' . $LAB_INST . '"> </td>' . PHP_EOL. 
            '        </tr>' . PHP_EOL. 

            '        <tr>' . PHP_EOL. 
            '            <td>City*:</td><td> <input name="LAB_CITY" type="text" value="' . $LAB_CITY . '"> </td>' . PHP_EOL. 
            '        </tr>' . PHP_EOL. 

            '        <tr>' . PHP_EOL. 
            '            <td>Country*:</td><td> <input name="LAB_COUNTRY" type="text" value="' . $LAB_COUNTRY . '"> </td>' . PHP_EOL. 
            '        </tr>' . PHP_EOL. 

            '    </tbody></table>' . PHP_EOL . PHP_EOL. 
            '    <br>' . PHP_EOL . PHP_EOL. 
            '    <input name="update" value="update" type="submit" class="geodiwButton" style="width:10ex;">' . PHP_EOL. PHP_EOL.  
            '</form>' . PHP_EOL . PHP_EOL. 
            
            '<!-- Delete button -->' . PHP_EOL. 
            '<form method="post" action="' . $labDelete . '">' . PHP_EOL. 
            '   <input name="LAB_ID" type="hidden" value="' . $LAB_ID . '">'. 
            '   <input name="LAB_NAME" type="hidden" value="' . $LAB_NAME . '">'. 
            '   <input name="delete" value="delete" type="submit" class="geodiwButton geodiwButton2" style="width:10ex;">' . PHP_EOL. PHP_EOL. 
            '</form>' . PHP_EOL . PHP_EOL; 

        }   
    }else{ 
        $formTable = '<p>No lab selected, or ID not found in the database</p>'; 
        $getID     = 'invalid ID?'; 
    }   
}else{ 
    $formTable = '<p>No lab selected, or ID not found in the database</p>'; 
    $getID     = 'no id provided.';      
}   #



// Message in case the item has just been updated:
if( isset( $_SESSION[ 'UPDATED' ] ) ){ 
    if( $_SESSION[ 'UPDATED' ] == 1 ){ 
        $updateMessage = '<p style="color:red;">The item has been successfully updated</p>'; 
        // Reset the UPDATED statement:
        $_SESSION[ 'UPDATED' ] = 0; 
    }else{ 
        $updateMessage = ''; 
    }   
}else{ 
    $updateMessage = ''; 
}   



// Prepare the bottom menu:
$menu = '<ul class="geodiwMenu">' . PHP_EOL. 
'    <li><a href="' . $home . '">Home</a></li>' . PHP_EOL. 
'    <li><a href="' . $manageUsers . '">Manage users</a></li>' . PHP_EOL. 
'    <li><a href="' . $preferences . '">Preferences</a></li>' . PHP_EOL. 
'    <li><a href="' . $logout . '">Logout</a></li>' . PHP_EOL. 
'</ul>' . PHP_EOL;



// Format the displayed user name:
$welcome = '<p>Welcome ' . $_SESSION['userName'] . ' (' . $_SESSION['userMail'] . ').</p>';



?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <?php echo( '<link href="' . $geodiwcss . '" rel="stylesheet" type="text/css">' ); ?> 
    <style type="text/css">
        input { width:600px; } 
        table.ghost { width:400px; table-layout:fixed; }
    </style>
    <title>Soil Science Lab Directory (SSLD) -- Edit directory entry</title>
</head>

<body>

<h1>Soil Science Lab Directory (SSLD)</h1>

<h2>Edit directory entry</h2>

<?php echo($welcome); ?> 

<?php echo($updateMessage); ?>

<!-- <?php echo( '<code>' . $LAB_DESC . '</code><code></code><br>' ); ?> -->

<!-- <p>You are editing lab ID = <?php echo($getID); ?></p> --> 

<?php echo($formTable); ?> 

<p><i>*: Items marked with a star (*) are required.</i></p>

<p>See the <a href="http://en.wikipedia.org/wiki/Wikipedia:Obtaining_geographic_coordinates#OpenStreetMap" target="_blank">Wikipedia page</a> 
on WGS 1984 coordinates retrieval.</p>

<br>

<?php echo( $menu ); ?>

<?php include($bottom); ?>



</body>

</html>

