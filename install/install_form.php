<?php 

// Options:
$footer    = '../footer.php'; 
$rootDir   = '../';  
$install   = 'install.php'; 
$geodiwcss = '../geodiw.css'; 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

<head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <?php echo( '<link href="' . $geodiwcss . '" rel="stylesheet" type="text/css">' ); ?> 
    <style type="text/css">
        input { width:200px; } 
        table { width:400px; table-layout:fixed; }
    </style>

    <title>Application installation</title>
</head>

<body>

<h1>Application installation</h1>


<? echo( '<form method="post" action="' . $install . '" name="installation">' ); ?>

    <h2>MySQL database</h2>

    <p>Information on the MySQL database that will host the application</p>

    <table class="ghost"><tbody><tr>
        <td>MySQL host:</td><td> <input name="dbHost" value="locahost"> </td> <!--  class="ssldForm" -->
    </tr></tbody></table>
    <i>MySQL database host (localhost or any host address, e.g. "sql.free.fr").</i>

    <br>
    <br>

    <table class="ghost"><tbody><tr>
        <td>Database user name:</td><td> <input name="dbUser"> </td>
    </tr></tbody></table>
    <i>MySQL database user name (e.g. "root" or any valid user name).</i>

    <br>
    <br>

    <table class="ghost"><tbody><tr>
        <td>Database user password:</td><td> <input name="dbPwd" type="password"> </td>
    </tr></tbody></table>

    <br>
    <br>

    <table class="ghost"><tbody><tr>
        <td>Database name:</td><td> <input value="ssld_db" name="dbName" type="text"> </td>
    </tr></tbody></table>
    <i>MySQL database name (name of the MySQL database to be used).</i>

    <br>
    <br>

    <table class="ghost"><tbody><tr>
        <td>Tables prefix:</td><td> <input readonly="readonly" value="ssld_" name="dbRoot"> </td>
    </tr></tbody></table>
    
    <br>
    <br>


    <h2>Webmaster informations</h2>

    <p>Information on the "webmaster" user that will manage the application. You chose the value that you want.</p>

    <table class="ghost"><tbody><tr>
        <td>Webmaster login:</td><td> <input name="admLogin" value="root"> </td>
    </tr></tbody></table>
    
    <br>
    <br>

    <table class="ghost"><tbody><tr>
        <td>Webmaster password:</td><td> <input name="admPwd" type="password"> </td>
    </tr></tbody></table>

    <br>
    <br>

    <table class="ghost"><tbody><tr>
        <td>Webmaster password (<i>confirm</i>):</td><td> <input name="admPwd2" type="password"> </td>
    </tr></tbody></table>
    
    <br>
    <br>

    <table class="ghost"><tbody><tr>
        <td>Encryption key:</td><td> <input name="encryptKey" value="oVZWXufNK470kXw"> </td>
    </tr></tbody></table>
    <i>Encryption key (key that will be used to encrypt passwords in the database. Needed only here).</i>

    <br>
    <br>

    <table class="ghost"><tbody><tr>
        <td>Webmaster name:</td><td> <input name="admName"> </td>
    </tr></tbody></table>
    <i>Webmaster name (full text, for display purpose only).</i>
    
    <br>
    <br>

    <table class="ghost"><tbody><tr>
        <td>Webmaster e-mail:</td><td> <input name="admMail"> </td>
    </tr></tbody></table>

    <br>
    <br>


    <h2>Website information</h2>

    <table class="ghost"><tbody><tr>
        <td>Site domain:</td><td> <input name="siteDomain" type="text" value="127.0.0.1"> </td>
    </tr></tbody></table>
    <i>Site domain (e.g. '127.0.0.1' or 'http://www.afes.fr/').</i>
    
    <br>
    <br>

    <input name="install" value="install" type="submit" class="geodiwButton" style="width:15ex;">

</form>

<br>
<br>

<?php include( $footer ); ?>

</body>
</html>

