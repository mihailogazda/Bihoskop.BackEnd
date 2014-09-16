<?php

    /**
     * 	Skripta vraca nazad korisniku podatke koristeci cache vrijednosti.
     * 	Ako kesiranih vrijednosti nema onda se koristi live zahtjev na proxy servere (dataProxy.php)
     */

    include_once('cache.php');
    header('Content-Type: application/json');

    $idx = $_GET["idx"];
    $tip = $_GET["tip"];
    $datum = $_GET["d"];
    $dataProxy = "http://" . $_SERVER["HTTP_HOST"] . "/data/dataProxy.php";

    //  Check for cached response
    $cache = new CCache($idx, $tip, $datum);
    if ($cache->fileExists())
        echo $cache->readResponse();
    else {
        $url = $dataProxy . "?d=" . $datum . "&idx=" . $idx . "&tip=" . $tip;
        $response = file_get_contents($url);
        
        if ($response) {
            $cache->openFile();
            $cache->writeResponse($response);
            $cache->closeFile();
            echo $response;
        }
    }
?>