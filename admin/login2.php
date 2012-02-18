<?php

if( !isset($_COOKIE[ $cookieName ]) ){ 

    sleep(2); 

    if( !isset($_COOKIE[ $cookieName ]) ){ 
    
        echo( $_COOKIE[ $cookieName ] . '<br>' );
        die( 'Error: the authentification cookie could not be set (2).<br>' );
        exit;

    }else{ 
        echo( "The login cookie was set.<br>" );
    }      
}else{ 
    echo( "The login cookie was set.<br>" );
    // sleep(2);
}

?>


