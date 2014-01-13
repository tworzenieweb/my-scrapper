<?php

namespace Tworzenieweb\Service;

class UserAgentProvider
{
    protected $filename;
    protected $resource;
    
    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->resource = (array) file($filename);
        
    }
    
    public function getAgent()
    {
        return $this->resource[array_rand($this->resource)];
    }
}
