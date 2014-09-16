<?php

/**
 * 	Film class for data storage.
 * 	@author:  mihailogazda
 */
class CFilm {

    var $idx;
    var $poster;
    var $naslovSrp;
    var $naslovEng;
    var $sadrzaj;
    var $vremenaPrikaza;
    var $glumci;
    var $zanr;
    var $imdbLink;
    var $imdbOcjena;
    var $trejler;
    var $trajanje;
    var $reziser;

    function __construct(
    $idx, $poster, $naslovSrp, $naslovEng, $sadrzaj, $vremenaPrikaza, $glumci, $zanr, $imdbLink, $imdbOcjena, $trejler, $trajanje, $reziser) {
        $this->idx = $idx;
        $this->poster = $poster;
        $this->naslovSrp = $naslovSrp;
        $this->naslovEng = $naslovEng;
        $this->sadrzaj = $sadrzaj;
        $this->vremenaPrikaza = $vremenaPrikaza;
        $this->glumci = $glumci;
        $this->zanr = $zanr;
        $this->imdbLink = $imdbLink;
        $this->imdbOcjena = $imdbOcjena;
        $this->trejler = $trejler;
        $this->trajanje = $trajanje;
        $this->reziser = $reziser;
    }

    public function toJSON() {
        return json_encode((array) $this);
    }

};
?>