<?php

$footer         = 'footer.php'; 
$configLocal    = 'config/config.local.php';  
$rootDir        = ''; 
$login_check    = 'admin/login_check.php'; 
$forbid_noLogin = 'admin/forbid_noLogin.php'; 
$home           = 'index.php';
$kmlFile        = 'kmz/institutions.kml'; 
$kmzFile        = 'kmz/institutions.kmz'; 
$geodiwcss      = 'geodiw.css'; 
$debug          = 0; 



if( $debug == 1 ){ 
    ini_set('display_errors',1);
	    error_reporting(E_ALL); 
    echo( 'Debug mode is on.<br>' ); 
}   



// Load loacl config (database)
include($configLocal); 



// Test if the user is authorised (logged in), 
include( $login_check ); 
include( $forbid_noLogin ); 



/* Source of inspiration: http://code.google.com/apis/kml/articles/phpmysqlkml.html#outputkml */ 
// Format KML file header
$kml   = array( 
             '<?xml version="1.0" encoding="UTF-8"?>' );
$kml[] = '<kml xmlns="http://earth.google.com/kml/2.2">';
$kml[] = '<Document>';
$kml[] = '  <name>Soil Science Labs</name>'; 
$kml[] = '  <Style id="soilLabs">'; 
$kml[] = '      <scale>1</scale>'; 
$kml[] = '      <IconStyle id="poiIcon"><Icon>';
$kml[] = '          <href>http://maps.google.com/mapfiles/kml/shapes/poi.png</href>';
$kml[] = '      </Icon></IconStyle>';
$kml[] = '  </Style>';



//connect to server and select database
$mysqli = mysqli_connect( $dbHostX, $dbUserX, $dbPwdX, $dbNameX );



// Fetch the list of countries
$sql = "SELECT DISTINCT `LAB_COUNTRY` FROM `ssld_labs` ORDER BY `LAB_COUNTRY` ASC"; //  



$result = $mysqli->query( $sql ); // Check that some results have been returned
if( !$result === TRUE ){
    die( "Error:" . $mysqli -> error );
    $mysqli->close(); exit;
}    



if( $result ){ 
    


    $countryList = array(); 

    // Retrieve the list of countries in an array
    while( $newRow = $result->fetch_array(MYSQLI_ASSOC) ){ 
        // Add the country to the country list:
        $countryList[] = $newRow[ 'LAB_COUNTRY' ];
    }   
    // Free the results
    $result->free(); 



    // Now fetch the data country by country and list cities
    foreach( $countryList as $country ){ 



        // Create a new folder for the country:
        $kml[] = '    <Folder id="' . $country . '">'; 
        $kml[] = '        <name>' . $country .  '</name>'; 

        // Retrieve the labs in this country
        $sql2 = "SELECT DISTINCT `LAB_CITY` FROM `ssld_labs` WHERE `LAB_COUNTRY` = '" . $country . "' ORDER BY `LAB_CITY` ASC";   

        $result2 = $mysqli->query( $sql2 ); // Check if some 
        if( !$result2 === TRUE ){
            die( "Error:" . $mysqli -> error );
             $mysqli->close(); exit;
        }    



        if( $result2 ){ 

            $cityList = array(); 

            // Retrieve the list of countries in an array
            while( $newRow2 = $result2->fetch_array(MYSQLI_ASSOC) ){ 
                // Add the country to the country list:
                $cityList[] = $newRow2[ 'LAB_CITY' ];
            } 
            // Free the results
            $result2->free(); 

        } // End if( $result2 ) 



        // Now fetch the data city by city and list labs
        foreach( $cityList as $city ){ 

            // Start to format the city placemark:
            $kml[] = '    <Placemark id="' . $city . '">';
            $kml[] = '        <name>'; 
            $kml[] = '            <![CDATA['; 
            $kml[] = '                ' . htmlentities( $city, ENT_QUOTES );
            $kml[] = '            ]]>';  
            $kml[] = '        </name>'; // htmlentities()
            $kml[] = '        <description>'; 
            $kml[] = '            <![CDATA['; 



            // List that will contain all coordinates
            $cityLong = array();
            $cityLat  = array();



            // Retrieve the labs in this city
            $sql3 = "SELECT * FROM `ssld_labs` WHERE `LAB_COUNTRY` = '" . $country . "' AND `LAB_CITY` = '" . $city . "' ORDER BY `LAB_NAME` ASC";   

            $result3 = $mysqli->query( $sql3 ); // Check if some 
            if( !$result3 === TRUE ){
                die( "Error:" . $mysqli -> error );
                $mysqli->close(); exit;
            }   



            if( $result3 ){ 
                // Loop over each rows
                while( $newRow3 = $result3->fetch_array(MYSQLI_ASSOC) ){ 

                    $kml[] = '                <p><a href="' . $newRow3[ 'LAB_URL' ] . '" target="_blank">' . htmlentities( $newRow3[ 'LAB_NAME' ] ) .  '</a>' . 
                                              ' <a href="http://maps.google.com/maps?ie=UTF8&ll=' . $newRow3[ 'WGS_LAT' ] . ',' . $newRow3[ 'WGS_LONG' ] . '&z=14" target="_blank">[map]</a></p>';

                    $cityLong[] = $newRow3[ 'WGS_LONG' ];  
                    $cityLat[]  = $newRow3[ 'WGS_LAT' ];
                }

                // Free the results
                $result3->free(); 
            }   


            // Average coordinates
            $meanLong = array_sum($cityLong) / count($cityLong); 
            $meanLat  = array_sum($cityLat) / count($cityLat); 

            // Close the city placemark
            $kml[] = '            ]]>'; 
            $kml[] = '        </description>'; // htmlentities() 
            $kml[] = '        <styleUrl>#soilLabs</styleUrl>';
            $kml[] = '        <Point>';
            $kml[] = '            <coordinates>' . $meanLong . ','  . $meanLat . '</coordinates>';
            $kml[] = '        </Point>';
            $kml[] = '    </Placemark>';

        } // End foreach( $cityList as $city ) 

        $kml[] = '    </Folder>'; // Close the country folder


    } // End foreach( $countryList as $country )



    // End XML file
    $kml[] = '</Document>';
    $kml[] = '</kml>';
    $kml = join( PHP_EOL, $kml ); 



    // Export as kml file:
    //$kml = join( "\n", $kml );
    $fh  = fopen($kmlFile, 'w' ) or die("can't open file");
    fwrite($fh, $kml);
    fclose($fh);



    // Export as a kmz file:
    $zip = new ZipArchive;
    $res = $zip->open( $kmzFile, ZipArchive::CREATE);
    if ($res === TRUE) {
        $zip->addFromString( $kmlFile, $kml );
        $zip->close();
    }else{
        die( 'Error: KMZ creation failed.<br>' );
    }




    // Final success message: 
    $success = '<p>KMZ file written.</p><p>Return to the <a href="' . $home . '">main page</a>.</p>'; 

}else{ // if( $result ) 
    die( 'Error: KML file could NOT be writen because no results were retrieved.<br>Return to the <a href="' . $home . '">main page</a>.<br>' );
}   

?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <?php echo( '<link href="' . $geodiwcss . '" rel="stylesheet" type="text/css">' ); ?> 
    <title>Soil Science Lab Directory (SSLD) -- Generate KMZ file</title>
</head>

<body>


<h1 style="font-family: sans-serif;">Soil Science Lab Directory (SSLD)</h1>

<h2 style="font-family: sans-serif;">Generate KMZ file</h2>

<?php echo( $success ); ?>

<?php include( $footer ); ?>

</body>
</html>

