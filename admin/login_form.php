<?php

// Options:
$footer    = '../footer.php'; 
$rootDir   = '../';  
$geodiwcss = '../geodiw.css'; 
$login     = 'login.php'; 

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <?php echo( '<link href="' . $geodiwcss . '" rel="stylesheet" type="text/css">' ); ?> 
    <style type="text/css">
        input { width:200px; } 
        table { width:300px; table-layout:fixed; }
    </style>
    <title>Soil Science Lab Directory (SSLD) -- Login page</title>
</head>

<body>


<h1 style="font-family: sans-serif;">Soil Science Lab Directory (SSLD)</h1>

<h2 style="font-family: sans-serif;">Login page</h2>


<?php echo( '<form method="post" action="' . $login . '">' ); ?>

    <table class="ghost"><tbody>
        <tr>
            <td>User login:</td><td> <input name="userLogin"> </td>
        </tr>
        <tr>
            <td>User password:</td><td> <input name="userPwd" type="password"> </td>
        </tr>
    </tbody></table>
    
    <br>
    <br>

    <input name="login" value="login" type="submit" class="geodiwButton" style="width:10ex;">

</form>


<?php include( $footer ); ?>


</body>
</html>

