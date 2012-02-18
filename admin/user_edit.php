<?php




// Script options:
$login_check    = 'login_check.php'; 
$forbid_noLogin = 'forbid_noLogin.php'; 
$footer         = '../footer.php'; 
$rootDir        = '../';  
$manageUsers    = 'users_manage.php'; 
$preferences    = 'preferences.php'; 
$home           = '../index.php';
$logout         = 'logout.php'; 
$configLocal    = '../config/config.local.php'; 
$userUpdate     = 'user_update.php'; 
$userPwdUpdate  = 'user_update_pwd.php'; 
$userDelete     = 'user_delete.php'; 
$geodiwcss      = '../geodiw.css'; 
$debug          = 0; 



// Uncomment the code below to debug:
if( $debug == 1 ){ 
    ini_set('display_errors',1);
        error_reporting(E_ALL); 
    echo( 'Debug mode is on.<br>' ); 
}   



// Test if the user is authorised (logged in), 
include( $login_check ); 
include( $forbid_noLogin ); 

if( !($_SESSION['userLevel'] == 0) ){ 
    die( 'Error: You don\'t have the right access to modify users. <a href="' . $home . '>Return to start page</a>">' ); 
    exit; 
} 



if( isset( $_GET["id"] ) ){ 

    // Sanitise the input ID:
    $getID = $_GET["id"]; 
    $getID = filter_var( $_GET["id"], FILTER_SANITIZE_NUMBER_INT ); 
    if( !$getID === (int)$_GET["id"] ){ 
        die( 'Error: Invalid ID' ); 
        exit; 
    }      



    // Set the ID as a session variable (for use in dependent pages)
    $_SESSION['USER_ID'] = $getID; 
    


    // Load loacl config (database)
    include($configLocal); 



    //connect to server and select database
    $mysqli = mysqli_connect( $dbHostX, $dbUserX, $dbPwdX, $dbNameX );



    // Retrieve the list of countries:
    $sql = 'SELECT * FROM `ssld_users` WHERE `USER_ID` = ' . $getID ; 
    if( $debug == 1 ){ echo( $sql . '<br>' ); }



    $result = $mysqli->query( $sql ); 
    if( !$result === TRUE ){
        die( "Error:" . $mysqli -> error );
        $mysqli->close(); exit;
    }   

    if( $result && ($result->num_rows == 1) ){ 

        while( $newRow = $result->fetch_array(MYSQLI_ASSOC) ){ 

            $USER_ID     = $newRow[ 'USER_ID' ]; 
            $USER_LOGIN  = $newRow[ 'USER_LOGIN' ]; 
            $USER_NAME   = $newRow[ 'USER_NAME' ]; 
            $USER_MAIL   = $newRow[ 'USER_MAIL' ]; 

            
            if( $newRow[ 'USER_LEVEL' ] == 1 ){ 
                $USER_LEVEL1 = ' selected="selected"'; 
                $USER_LEVEL0 = '';        
            }
            if( $newRow[ 'USER_LEVEL' ] == 0 ){ 
                $USER_LEVEL0 = ' selected="selected"'; 
                $USER_LEVEL1 = ''; 
            }
        } 


        // Format the form table:
        $formTable = '<!-- Update form -->' . PHP_EOL. 
        '<form method="post" action="' . $userUpdate . '">' . PHP_EOL. 
        '    <table class="ghost">' . PHP_EOL. 

        '    <tbody>' . PHP_EOL. 

        '        <tr>' . PHP_EOL. 
        '            <td>ID:</td><td> <input name="USER_ID" readonly="readonly" value="' . $USER_ID . '" /> </td>' . PHP_EOL. 
        '        </tr>' . PHP_EOL. 

        '        <tr>' . PHP_EOL. 
        '            <td>Login:</td><td> <input name="USER_LOGIN" type="text" value="' . $USER_LOGIN . '" /> </td>' . PHP_EOL. 
        '        </tr>' . PHP_EOL. 

        '        <tr>' . PHP_EOL. 
        '            <td>Name:</td><td> <input name="USER_NAME" type="text" value="' . $USER_NAME . '" /> </td>' . PHP_EOL. 
        '        </tr>' . PHP_EOL. 

        '        <tr>' . PHP_EOL. 
        '            <td>e-mail:</td><td> <input name="USER_MAIL" type="text" value="' . $USER_MAIL . '" /> </td>' . PHP_EOL. 
        '        </tr>' . PHP_EOL. 


        '        <tr>' . PHP_EOL. 
        '            <td>Level*:</td><td> <select name="USER_LEVEL"> '. PHP_EOL. 
        '                                    <option value="1" ' . $USER_LEVEL1 . '>Editor (1)</option>' . PHP_EOL. 
        '                                    <option value="0" ' . $USER_LEVEL0 . '>Superadmin (0)</option>' . PHP_EOL. 
        '                                 </select></td>'. PHP_EOL. 
        '        </tr>' . PHP_EOL. 

        '    </tbody></table>' . PHP_EOL . PHP_EOL. 
        '    <br>' . PHP_EOL . PHP_EOL. 
        '    <input name="update" value="update" type="submit" class="geodiwButton" style="width:10ex;" />' . PHP_EOL. PHP_EOL.  
        '</form>' . PHP_EOL . PHP_EOL. 

        '<!-- Delete button -->' . PHP_EOL. 
        '<form method="post" action="' . $userDelete . '">' . PHP_EOL. 
        '   <input name="USER_ID" type="hidden" value="' . $USER_ID . '">'. 
        '   <input name="USER_LOGIN" type="hidden" value="' . $USER_LOGIN . '">'. 
        '   <input name="delete" value="delete" type="submit" class="geodiwButton geodiwButton2" style="width:10ex;">' . PHP_EOL. PHP_EOL. 
        '</form>' . PHP_EOL . PHP_EOL; 
       


        // Format the form table:
        $formTable2 = '<!-- Update password form -->' . PHP_EOL. 
        '<form method="post" action="' . $userPwdUpdate . '">' . PHP_EOL. 
        '    <input name="USER_ID" readonly="readonly" type="hidden" value="' . $USER_ID . '" />' . PHP_EOL. 
        '    <table class="ghost">' . PHP_EOL. 

        '    <tbody>' . PHP_EOL. 

        '        <tr>' . PHP_EOL. 
        '            <td>Old password:</td><td> <input name="USER_PWDo" type="password" /> </td>' . PHP_EOL. 
        '        </tr>' . PHP_EOL. 

        '        <tr>' . PHP_EOL. 
        '            <td>New password (1):</td><td> <input name="USER_PWDn" type="password" /> </td>' . PHP_EOL. 
        '        </tr>' . PHP_EOL. 

        '        <tr>' . PHP_EOL. 
        '            <td>New password (2):</td><td> <input name="USER_PWD2n" type="password" /> </td>' . PHP_EOL. 
        '        </tr>' . PHP_EOL. 

        '    </tbody></table>' . PHP_EOL . PHP_EOL. 
        '    <br>' . PHP_EOL . PHP_EOL. 
        '    <input name="update password" value="updatePwd" type="submit" class="geodiwButton" style="width:20ex;">' . PHP_EOL. PHP_EOL.  
        '</form>' . PHP_EOL . PHP_EOL; 

    }else{ 
        $formTable  = '<p>No user selected, or ID not found in the database</p>'; 
        $formTable2 = ''; 
        $getID      = 'invalid ID?'; 
    }   

}else{ 
    $formTable  = '<p>No user selected, or ID not found in the database</p>'; 
    $formTable2 = ''; 
    $getID      = 'no id provided.';      
}   



// Message in case the item has just been updated:
if( isset( $_SESSION[ 'USER_UPDATED' ] ) ){ 
    if( $_SESSION[ 'USER_UPDATED' ] == 1 ){ 
        $updateMessage = '<p style="color:red;">The user has been successfully updated</p>'; 
        // Reset the UPDATED statement:
        $_SESSION[ 'USER_UPDATED' ] = 0; 
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

<!-- <?php echo( '<code>' . $USER_LEVEL . '</code><code></code><br>' ); ?> -->

<!-- <p>You are editing lab ID = <?php echo($getID); ?></p> --> 

<?php echo($formTable); ?> 

<br>

<?php echo($formTable2); ?> 

<br>

<?php echo( $menu ); ?>

<?php include($footer); ?>



</body>

</html>

