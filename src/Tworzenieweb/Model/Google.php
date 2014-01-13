<?php

namespace Tworzenieweb\Model;

class Google implements SearchObjectInterface
{
    
    const GOOGLE_URL_PATTERN = 'https://www.google.%s/search?%s';
    
    private $term;
    private $locale;
    private $url;
    private $isFound = false;
    private $position = 1;
    private $currentPage = 1;
    
    public function getTerm()
    {
        return $this->term;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setTerm($term)
    {
        $this->term = $term;
        return $this;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }
    
    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getIsFound()
    {
        return $this->isFound;
    }

    public function setIsFound($isFound)
    {
        $this->isFound = $isFound;
        return $this;
    }
    
    public function incrementPosition()
    {
        if(false === $this->isFound)
        {
            $this->position++;
        }
        
        return $this;
    }
    
    public function getPosition()
    {
        return $this->position;
    }
    
    public function getResultsUrl()
    {
        return sprintf(
            self::GOOGLE_URL_PATTERN, 
            $this->getLocale(), http_build_query(array('q' => $this->getTerm()))
        );
    }
    
    public function getCurrentPage()
    {
        return $this->currentPage;
    }
    
    public function incrementCurrentPage()
    {
        $this->currentPage++;
        return $this;
    }


}
