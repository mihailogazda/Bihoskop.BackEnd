<?php

require_once 'parser_cs.php';
require_once 'parser_cs_uskoro.php';

//Helper metode
function getRepertoarUrl($id, $datum) {
    if ($id > 5001)
        return "http://bihoskop.com/data/parser.php?idx=" . $id . "&d=" . $datum;
    else
        return "http://marvinoxy.appspot.com/servis/repertoar?idx=" . $id . "&d=" . $datum;
}

function getUskoroUrl($id) {
    return "http://marvinoxy.appspot.com/servis/uskoro?idx=" . $id;
}

//
//	Application code
//
try {
    //	Formiraj sebi URL			
    $tip = $_GET["tip"];
	$id = "";
	if (array_key_exists("idx",$_GET))
		$id = $_GET["idx"];
	
    $url = "";
    $content = "";

    switch ($tip) {
        case "bioskopi":
            $url = "bioskopi.json";
            break;
        case "rep":
            if ($id > 7000) {
                $a = new CParser($id);
                $content = $a->getFilmoviJSON();
            }
            else
                $url = getRepertoarUrl($_GET["idx"], $_GET["d"]);
            break;
        case "uskoro":
            if ($id > 7000) {
                $a = new CParserUskoro($id);
                $content = $a->getFilmoviJSON();
            }
            else
                $url = getUskoroUrl($_GET["idx"]);
            break;
    }

    //  Citaj sada ako je URL pun
    if (!empty($url))
        $content = file_get_contents($url);

    echo $content;
} catch (Exception $e) {
    
}
?>