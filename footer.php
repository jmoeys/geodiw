<?php 

// Script options:  
$langFile         = 'lang/lang.php'; 
if( !isset($rootDir) ){ $rootDir = '..'; } 



include_once( $rootDir . $langFile ); 



?>

<div class="footer">

    <br>

    <?php echo( $lang[ 'footer_line1' ] ); ?>

    <br>
    <br>

    <?php echo( $lang[ 'footer_line2' ] ); ?>

    <br>
    <br>
    
    <?php
        $images = 
        /* '<a href="http://kompozer.net/"><img style="border: 0px solid ; width: 80px; height: 15px;" alt="Document made with KompoZer" title="Document made with KompoZer" src="'.$rootDir.'images/kompozer_80x15.png"></a> '. */ 
        '<a href="http://www.php.net/"><img style="border: 0px solid ; width: 80px; height: 15px;" alt="PhP Powered" title="PhP Powered" src="'.$rootDir.'images/php-power-micro2.png"></a> '. 
        '<a href="http://www.mysql.com/"><img style="border: 0px solid ; width: 80px; height: 15px;" alt="MySQL Powered" title="MySQL Powered" src="'.$rootDir.'images/mysql_powered.png"></a> '; 
        
        echo( $images );
    ?>

</div>


