<?

// Script options:
$login_check    = 'login_check.php'; 
$forbid_noLogin = 'forbid_noLogin.php'; 
$footer         = '../footer.php'; 
$rootDir        = '../';  
$manageUsers    = 'users_manage.php'; 
$preferences    = 'preferences.php'; 
$home           = '../index.php';
$logout         = 'logout.php'; 
$addUser        = 'user_add_form.php'; 
$editUser       = 'user_edit.php'; 
$configLocal    = '../config/config.local.php';  
$geodiwcss      = '../geodiw.css'; 
$debug          = 0



if( $debug == 1 ){ 
    // Uncomment the code below to debug:
    ini_set('display_errors',1);
        error_reporting(E_ALL); 
    echo( 'Debug mode is on<br>' ); 
}    



// Test if the user is authorised (logged in), 
include( $login_check ); 
include( $forbid_noLogin ); 

if( !($_SESSION['userLevel'] == 0) ){ 
    die( 'Error: You don\'t have the right access to modify users. <a href="' . $home . '>Return to start page</a>">' ); 
    exit; 
}   



// Load loacl config (database)
include($configLocal); 



//connect to server and select database
$mysqli = mysqli_connect( $dbHostX, $dbUserX, $dbPwdX, $dbNameX );



// Retrieve the list of countries:
$sql = 'SELECT * FROM `ssld_users` ORDER BY `USER_NAME` ASC'; //  



// Fetch the results:
$result = $mysqli->query( $sql ); 
if( !$result === TRUE ){
    die( "Error:" . $mysqli -> error );
    $mysqli->close(); exit;
}   



// Format user table head:
$tblHead = 
'        <tr>' . PHP_EOL. 
'            <th>ID</th> <th>Name</th> <th>e-mail</th> <th>Login</th><th>Level*</th><th>Edit</th>'. PHP_EOL.
'        </tr>' . PHP_EOL; 



// Format user table body:
$tblBody = ''; 
while( $newRow = $result->fetch_array(MYSQLI_ASSOC) ){ // Loop over each rows
    $tblBody = $tblBody. 
    '        <tr>'. PHP_EOL . 
    '            <td>' . $newRow[ 'USER_ID' ] . '</td>' . 
    '<td><b>' . $newRow[ 'USER_NAME' ] . '</b></td>' . 
    '<td><a href="mailto:' . $newRow[ 'USER_MAIL' ] . '">' . $newRow[ 'USER_MAIL' ] . '</a>' . '</td>' . 
    '<td>' . $newRow[ 'USER_LOGIN' ] . '</td>' . 
    '<td>' . $newRow[ 'USER_LEVEL' ] . '</td>' . 
    '<td><a href="' . $editUser . '?id=' . $newRow[ 'USER_ID' ] . '">edit</a></td>' . 
    '        </tr>' . PHP_EOL; 
}



$tbl = 
'<table class="geodiwTable">' . PHP_EOL. 
'   <thead>' . PHP_EOL. 
$tblHead. 
'   </thead>' . PHP_EOL. 
'   <tbody>' . PHP_EOL. 
'       <?php echo( $tbl . PHP_EOL ) ?>' . PHP_EOL. 
$tblBody. 
'</table>' . PHP_EOL . PHP_EOL; 



// Free the results
$result->free(); 



// Close the database:
$mysqli->close(); 



// Case when a user has just been added to the database:
if( isset($_SESSION['USER_ADDED']) ){ 
    if( $_SESSION['USER_ADDED'] == 1 ){ 
        $addMessage = '<p style="color:red;">The user ' . $_SESSION['USER_ADDED_NAME'] . ' has been successfully added.</p>'; 
        $_SESSION['USER_ADDED'] = 0; 
        $_SESSION['USER_ADDED_NAME'] = "0"; 
    }else{ 
        $addMessage = ''; 
    }      
}else{ 
    $addMessage = ''; 
}  



// Case when a user has just been added to the database:
if( isset($_SESSION['USER_DELETED']) ){ 
    if( $_SESSION['USER_DELETED'] == 1 ){ 
        $addMessage = '<p style="color:red;">The user ' . $_SESSION['USER_DELETED_NAME'] . ' has been successfully deleted.</p>'; 
        $_SESSION['USER_DELETED'] = 0; 
        $_SESSION['USER_DELETED'] = "0"; 
    }else{ 
        $addMessage = ''; 
    }      
}else{ 
    $addMessage = ''; 
}  



// Format the bottom menu:
$menu = '<ul class="geodiwMenu">' . PHP_EOL. 
'    <li><a href="' . $home . '">Home</a></li>' . PHP_EOL. 
'    <li><a href="' . $addUser . '">Add user</a></li>' . PHP_EOL. 
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
    <title>Soil Science Lab Directory (SSLD) -- Manage users</title>
</head>

<body>

<h1>Soil Science Lab Directory (SSLD)</h1>

<h2>Manage users</h2>

<?php echo($welcome) ?> 

<?php echo($addMessage); ?>

<br>

<?php echo($tbl) ?> 

<br>

<p><i>*: User levels: 0 = superadmin (can change users and preferences), 1 = editor 
    (can only change directory entries).</i></p>

<br>

<?php echo( $menu ); ?>

<?php include($footer); ?>



</body>

</html>

