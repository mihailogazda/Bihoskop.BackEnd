<?php

/*
 * Cache controller za aplikaciju, provjerava prvo da li odgovor postoji kesiran
 * i tek ako ne postoji poziva tudje servere ili ucitava potrebne podatke.
 */    

class CCache
{
    var $idx;
    var $tip;
    var $datum;
            
    var $cacheDir;
    
    var $filename;
    var $handle;
    
    function generateFilename(){
        $val = $this->cacheDir;
        
        if ($this->idx)
            $val .= $this->idx . ".";
        if ($this->datum)
            $val .= $this->datum . ".";
        if ($this->tip)
            $val .= $this->tip . ".";
        
        $val .= "cache";
        return $val;        
    }
    function openFile(){
        $this->handle = fopen($this->filename, "w");
    }
    function closeFile(){
        return fclose($this->handle);
    }
    function fileExists(){
        return file_exists($this->filename);
    }
    function writeResponse($response){
        fwrite($this->handle, $response);        
        return true;
    }
    function readResponse(){
        return file_get_contents($this->filename);
    }
    
    function __construct($idx, $tip, $datum){
        
        $this->idx = $idx;
        $this->tip = $tip;
        $this->datum = $datum;
        
        $this->cacheDir = "./cache/";
        $this->filename = $this->generateFilename();
    }
}

?>
