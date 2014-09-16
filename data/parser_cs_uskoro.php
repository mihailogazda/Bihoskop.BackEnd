<?php

/**
 *  Skripta za parsiranje stranica online bioskopa.
 *  
 *  
 */
include_once('parser_cs.php');

class CParserUskoro extends CParser {
    
    var $library = array(        
        7001 => "http://www.blitz-cinestar-bh.ba/najave-filmova/1",      //mostar
        8001 => "http://www.blitz-cinestar.hr/najave-filmova/1",                  //svi centri
        8002 => "http://www.blitz-cinestar.hr/najave-filmova/1",         //Branimir
        8003 => "http://www.blitz-cinestar.hr/najave-filmova/1",    //Zagreb Avenue Mall
        8004 => "http://www.blitz-cinestar.hr/najave-filmova/1",     //Zagreb Arena IMAX
        8010 => "http://www.blitz-cinestar.hr/najave-filmova/1",         //Rijeka
        8011 => "http://www.blitz-cinestar.hr/najave-filmova/1",          //Zadar
        8012 => "http://www.blitz-cinestar.hr/najave-filmova/1",        //Sibenik
        8013 => "http://www.blitz-cinestar.hr/najave-filmova/1",          //Split
        8014 => "http://www.blitz-cinestar.hr/najave-filmova/1",         //Sibenik
        8015 => "http://www.blitz-cinestar.hr/najave-filmova/1",       //Varazdin        
        8016 => "http://www.blitz-cinestar.hr/najave-filmova/1",      //Dubrovnik
        null
    );
};

//	IDX
//$index = $_GET["idx"];

//$parser = new CParserUskoro($index);
//echo $parser->getFilmoviJSON();

?>