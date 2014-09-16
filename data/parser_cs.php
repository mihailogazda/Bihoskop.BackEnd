<?php

/**
 *  Skripta za parsiranje stranica online bioskopa.
 *  
 *  
 */
include_once('simple_html_dom.php');
include_once('film.php');

class CParser {

    var $idx = 0;
    var $filmArray;
    var $library = array(        
        7001 => "http://www.blitz-cinestar-bh.ba/cinestar-mostar",      //mostar
        8001 => "http://www.blitz-cinestar.hr/zagreb",                  //svi centri
        8002 => "http://www.blitz-cinestar.hr/cinestar-zagreb",         //Branimir
        8003 => "http://www.blitz-cinestar.hr/cinestar-novi-zagreb",    //Zagreb Avenue Mall
        8004 => "http://www.blitz-cinestar.hr/cinestar-arena-imax",     //Zagreb Arena IMAX
        8010 => "http://www.blitz-cinestar.hr/cinestar-rijeka",         //Rijeka
        8011 => "http://www.blitz-cinestar.hr/cinestar-zadar",          //Zadar
        8012 => "http://www.blitz-cinestar.hr/cinestar-sibenik",        //Sibenik
        8013 => "http://www.blitz-cinestar.hr/cinestar-split",          //Split
        8014 => "http://www.blitz-cinestar.hr/cinestar-osijek",         //Sibenik
        8015 => "http://www.blitz-cinestar.hr/cinestar-varazdin",       //Varazdin        
        8016 => "http://www.blitz-cinestar.hr/cinestar-dubrovnik",      //Dubrovnik
        null
    );
    
    var $baseResourceURL = "http://www.blitz-cinestar-bh.ba";

    /** METHODS */
    function __construct($index) {
        $this->idx = $index;
        $this->filmArray = array();
    }

    function getParseURL() {
        if (isset($this->library[$this->idx])) {
            $urlToParse = $this->library[$this->idx];
            return $urlToParse;
        }
        return false;
    }

    function getFilmoviJSON() {
        if (!$this->parseFilmovi())
            return false;
        return json_encode(array("filmovi" => $this->filmArray));
    }

    function getUskoroJSON() {
        return $this->getFilmoviJSON();
    }

    function readURLContents($url) {     
        $userAgent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.13 (KHTML, like Gecko) Chrome/9.0.597.107 Safari/534.13";
        $ch = curl_init();
        $timeout = 60;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    // nl2p_html
    // This function will add paragraph tags around textual content of an HTML file, leaving
    // the HTML itself intact
    // This function assumes that the HTML syntax is correct and that the '<' and '>' characters
    // are not used in any of the values for any tag attributes. If these assumptions are not met,
    // mass paragraph chaos may ensue. Be safe.
    function nl2p_html($str) {

        $out = "";
        
        // If we find the end of an HTML header, assume that this is part of a standard HTML file. Cut off everything including the
        // end of the head and save it in our output string, then trim the head off of the input. This is mostly because we don't
        // want to surrount anything like the HTML title tag or any style or script code in paragraph tags. 
        if (strpos($str, '</head>') !== false) {
            $out = substr($str, 0, strpos($str, '</head>') + 7);
            $str = substr($str, strpos($str, '</head>') + 7);
        }

        // First, we explode the input string based on wherever we find HTML tags, which start with '<'
        $arr = explode('<', $str);

        // Next, we loop through the array that is broken into HTML tags and look for textual content, or
        // anything after the >
        for ($i = 0; $i < count($arr); $i++) {
            if (strlen(trim($arr[$i])) > 0) {
                // Add the '<' back on since it became collateral damage in our explosion as well as the rest of the tag
                $html = '<' . substr($arr[$i], 0, strpos($arr[$i], '>') + 1);

                // Take the portion of the string after the end of the tag and explode that by newline. Since this is after
                // the end of the HTML tag, this must be textual content.
                $sub_arr = explode("\n", substr($arr[$i], strpos($arr[$i], '>') + 1));

                // Initialize the output string for this next loop
                $paragraph_text = '';

                // Loop through this new array and add paragraph tags (<p>...</p>) around any element that isn't empty
                for ($j = 0; $j < count($sub_arr); $j++) {
                    if (strlen(trim($sub_arr[$j])) > 0)
                        $paragraph_text.='<p>' . trim($sub_arr[$j]) . '</p>';
                }

                // Put the text back onto the end of the HTML tag and put it in our output string
                $out.=$html . $paragraph_text;
            }
        }

        // Throw it back into our program
        return $out;
    }

    public function parseFilmovi() {
        //  get contents first
        $contents = $this->readURLContents($this->getParseURL());

        if (!isset($contents))
            return false;

        $html = str_get_html($contents);
        if (!isset($html))
            return false;

        foreach ($html->find("div.allMovieItem") as $item) {

            $poster = $naslovSrp = $naslovEng = $sadrzaj = $vremenaPrikaza = $glumci = $zanr = $imdbLink = $imdbOcjena = $trejler = $trajanje = $reziser = $linkDoSadrzaja = "";
            $idx = rand(1, 1000000);

            //  Title
            $elTitleLink = null;
            $elTitle = $item->find(".movieItemTitle", 0);
            if (isset($elTitle)) {
                $naslovSrp = strip_tags($elTitle->innertext);
                $elTitleLink = $elTitle->href;//nadji odmah link
            }

            //  Slika
            $elSlika = $item->find(".slika", 0);
            if (isset($elSlika)) {
                $img = $elSlika->find("img", 0);
                if ($img){
                    $po = str_replace(" ", "%20", $img->src);
                    $slika = "./images/{$idx}.png";
                    file_put_contents($slika, $this->readURLContents($po));
                    $poster = "http://www.bihoskop.com/data/images/{$idx}.png";
                }
            }

            //  Opis
            $elOpis = $item->find(".opis", 0);            
            if (isset($elOpis)) {
                $pgr = $this->nl2p_html((string) $elOpis); //  fix missing <p></p> tags
                if (isset($pgr)) {                    
                    $p = str_get_html($pgr);
                    $elOpis = $p->childNodes(0);
                    if (isset($elOpis)){
                        
                        $naslovEng = $elOpis->childNodes(4)->innertext;
                        
                        $strong = $elOpis->find("strong", 0);
                        
                        $reziser = substr($elOpis->childNodes($strong ? 10: 8)->innertext, strlen("Redatelj: "));
                        $glumci = strip_tags($elOpis->childNodes($strong ? 13: 11)->innertext);
                        $zanr = substr($elOpis->childNodes($strong ? 15: 13)->innertext, strlen("Žanr: "));                                                
                        $trajanje = intval(substr($elOpis->childNodes($strong ? 17: 15)->innertext, strlen("Trajanje: ") ));
                        
                        //  Nadji prikazivanja
                        $prikazivanja = $elOpis->find("div.scheduleItem2", 0);
                        if (isset($prikazivanja)){
                            $vremenaPrikaza = array();
                            foreach ($prikazivanja->childNodes() as $prikaz)
                            {                                                                
                                $v = str_replace(".", ":", strip_tags($prikaz->innertext));
                                array_push($vremenaPrikaza, $v);
                                
                            }
                        }                                                                        
                    }
                }
            }
            
            //  Now load part2 (movie contents)
            $url = $this->baseResourceURL . $elTitleLink;
            $con2 = $this->readURLContents($url);
            
            //  Convert to DOM
            $item2 = str_get_html($con2);
            if ($item2){
                $movieInfo = $item2->find("div.movieInfo", 0);
                if (isset($movieInfo)){
                    foreach ($movieInfo->childNodes() as $node){
                        if (strpos($node->innertext, "SADRŽAJ FILMA:")){
                            $sadrzaj = substr(strip_tags($node->innertext), strlen("SADRŽAJ FILMA:"));
                        }
                    }
                }
            }
            
            //  Step 3 - load IMDB data            
            $api = "http://www.deanclatworthy.com/imdb/?q={$naslovEng}";
            $con3 = $this->readURLContents($api);
            
            //  Check if API has error
            $errorMsg = "{\"code\":2,\"error\":\"Exceeded API usage limit\"}";
            if (strpos($con3, "Exceeded API usage limit")){
                $con3 = substr($con3, strlen($errorMsg));                
            }
            
            if (isset($con3)){
                $con3 = json_decode($con3);
                //var_dump($con3);
                
                if ($con3){
                    $imdbLink = $con3->{"imdburl"};                
                    $imdbOcjena = $con3->{"rating"};
                }
            }            
            
            //  Fill final array and go to next iteration
            $film = new CFilm($idx, $poster, $naslovSrp, $naslovEng, $sadrzaj, $vremenaPrikaza, $glumci, $zanr, $imdbLink, $imdbOcjena, $trejler, $trajanje, $reziser);
            array_push($this->filmArray, $film);
        }
        return true;
    }

};

//	IDX
//$index = $_GET["idx"];
//$parser = new CParser($index);
//echo $parser->getFilmoviJSON();
//echo $parser->getUskoroJSON();
?>