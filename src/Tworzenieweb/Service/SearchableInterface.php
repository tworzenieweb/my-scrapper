<?php

namespace Tworzenieweb\Service;

use \Tworzenieweb\Model\SearchObjectInterface;

interface SearchableInterface
{
    
    public function search(SearchObjectInterface $searchableObject);
    
}
