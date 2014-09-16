<?php

/**
 * 	Skripta za parsiranje stranica online bioskopa 
 *
 *
 */

include_once('simple_html_dom.php');
include_once('film.php');

class CParser {

    var $idx = 0;
    var $filmArray;
    var $library = array(
        0 => "",
        3001 => "http://ba.idemoukino.com/repertoar-kina-banja-luka",
        4001 => "http://ba.idemoukino.com/repertoar-kina-zenica",
        5001 => "http://ba.idemoukino.com/repertoar-kina-sarajevo",
        6001 => "http://www.idemoukino.com/kinoprogram-zagreb_cineplexx-centar-kaptol",
    );
    var $baseResourceURL = "http://ba.idemoukino.com";

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

    public function parseFilmovi() {
        $url = $this->getParseURL();
        $html = file_get_html($url);

        if (!isset($html))
            return false;

        foreach ($html->find("div.scheduleItem") as $item) {
            $subitem = str_get_html($item);
            if (!isset($subitem))
                continue;

            //	Define vars
            $poster = $naslovSrp = $naslovEng = $sadrzaj = $vremenaPrikaza = $glumci = $zanr = $imdbLink = $imdbOcjena = $trejler = $trajanje = $reziser = $linkDoSadrzaja = "";
            $idx = rand(1, 100000);

            //	Fetch image
            $image = $subitem->find("#movieImage", 0);
            if (isset($image)) {
                $poster = $this->baseResourceURL . $image->children(0)->src;
            }

            //	Srpski title
            $title = $subitem->find("h2", 0);
            if (isset($title)) {
                $titLink = $title->find("a", 0);
                if (isset($titLink)) {
                    $naslovSrp = $titLink->innertext;
                    $linkDoSadrzaja = $titLink->href;
                }
                else
                    $naslovSrp = strip_tags($title->innertext);

                if (isset($title->children(2)->innertext))
                    $naslovEng = $title->children(2)->innertext;
            }

            //	Zanr
            $desc = $subitem->find("div.scheduleItemRow", 0);
            if (isset($desc)) {
                $desc->children(0)->outertext = "";
                $zanr = ltrim(rtrim($desc->innertext));
            }

            //	Reziser				
            $desc = $subitem->find("div.scheduleItemRow", 1);
            if (isset($desc)) {
                $desc->children(0)->outertext = "";
                $reziser = ltrim(rtrim($desc->innertext));
            }

            //	Glavne uloge
            $desc = $subitem->find("div.scheduleItemRow", 2);
            if (isset($desc)) {
                $desc->children(0)->outertext = "";
                $glumci = ltrim(rtrim($desc->innertext));
            }

            //	Vremena prikazivanja
            $table = $subitem->find("table", 0);
            if (isset($table)) {
                $vremenaPrikaza = array();
                $subitems = $table->getElementsByTagName("div");
                foreach ($subitems as $i) {
                	//echo $i;
                	$cls = $i->getAttribute("class");
                    if ($cls == "sheduleTime2" || $cls == "sheduleTime2 sheduleTimeLong"){
                    	$content = ltrim(rtrim($i->innertext));
                    	$cleaned = strip_tags($content);
                    	$removed = str_replace("[O]", "", $cleaned);
                        array_push($vremenaPrikaza, $removed);
                    }
                }
            }

            //	Ucitaj ostale podatke
            if (!empty($linkDoSadrzaja)) {
                $html2 = file_get_html($linkDoSadrzaja);
                if (isset($html2)) {
                    
                    //	Sadrzaj
                    $c = $html2->find("div.movieTag", 0);
                    if (isset($c))
                        $sadrzaj = str_replace("[kratko..]", "", strip_tags($c->innertext));

                    //	IMDB link
                    $i = $html2->find("td.trailer_rightCell");
                    $j = $html2->find("td.trailer_leftCell");
                    $ix = 0;
                    foreach ($i as $b) {
                        if ($j[$ix]->innertext == "Imdb: ")
                            $imdbLink = $i[$ix]->children(0)->href;
                        else if ($j[$ix]->innertext == "Trajanje: ")
                            $trajanje = $i[$ix]->innertext;
                        ++$ix;
                    }

                    //	Trejler
                    $t = $html2->find("iframe.movieFrame", 0);
                    if (isset($t)) {
                        $src = $t->src;
                        if (isset($src)) {
                            $html3 = file_get_html($src);
                            if (isset($html3)) {
                                $embed = $html3->find("embed", 0);
                                if (isset($embed))
                                    $trejler = $embed->src;
                            }
                        }
                    }
                }
            }

            //	Ocijena
            if (isset($imdbLink)) {
                $id = substr($imdbLink, strrpos($imdbLink, "/") + 1, strlen($imdbLink));
                $api = "http://www.imdbapi.com/?i={$id}";
                $apiResponse = file_get_html($api);
                if (isset($apiResponse)) {
                    $res = json_decode($apiResponse);
                    if (isset($res->{"imdbRating"}))
                        $imdbOcjena = $res->{"imdbRating"};
                }
            }

            $film = new CFilm($idx, $poster, $naslovSrp, $naslovEng, $sadrzaj, $vremenaPrikaza, $glumci, $zanr, $imdbLink, $imdbOcjena, $trejler, $trajanje, $reziser);
            array_push($this->filmArray, $film);
        }
        return true;
    }

};

//	IDX
$index = $_GET["idx"];

$parser = new CParser($index);
echo $parser->getFilmoviJSON();
//echo $parser->getUskoroJSON();
?>