<?php

namespace Tworzenieweb\Model;


interface SearchObjectInterface
{
    public function getTerm();

    public function setTerm($term);

    public function getLocale();

    public function setLocale($locale);

    public function getUrl();

    public function setUrl($url);

    public function getIsFound();

    public function setIsFound($isFound);
    
    public function getPosition();    
    
    public function incrementPosition();
    
    public function getResultsUrl();
    
    public function getCurrentPage();
    
    public function incrementCurrentPage();
}
