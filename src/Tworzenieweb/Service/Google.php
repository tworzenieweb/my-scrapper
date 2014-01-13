<?php

namespace Tworzenieweb\Service;

use Goutte\Client;
use Tworzenieweb\Model\SearchObjectInterface;
use Symfony\Component\DomCrawler\Crawler;

class Google implements SearchableInterface
{

    const NODE_SELECTOR = 'li.g .s cite';
    const PAGINATION_SELECTOR = '#nav td a';

    protected $client;
    protected $userAgentProvider;

    public function __construct(Client $client, UserAgentProvider $userAgentProvider)
    {
        $this->client = $client;
        $this->userAgentProvider = $userAgentProvider;
    }

    /**
     * 
     * @param \Tworzenieweb\Model\SearchObjectInterface $searchableObject
     * @return \Tworzenieweb\Model\Google
     */
    public function search(SearchObjectInterface $searchableObject)
    {

        $this->client->setHeader('User-Agent', $this->userAgentProvider->getAgent());
        $crawler = $this->client->request('GET', $searchableObject->getResultsUrl());

        while (!$searchableObject->getIsFound())
        {

            try
            {
                $crawler = $this->processResults($crawler, $searchableObject);
            } catch (\Exception $e)
            {
                return $searchableObject;
            }
        }

        return $searchableObject;
    }

    protected function processResults(Crawler $crawler, SearchObjectInterface &$searchableObject)
    {

        $crawler->filter(self::NODE_SELECTOR)->reduce(function (Crawler $node) use (&$searchableObject)
        {

            if (false !== strstr($this->filterUrl($node->html()), $searchableObject->getUrl()))
            {
                $searchableObject->setIsFound(true);
                return true;
            }

            $searchableObject->incrementPosition();
            
            return false;
            
        });
        
        if($searchableObject->getIsFound())
        {
            return $searchableObject;
        }

        return $this->nextPage($crawler, $searchableObject);
    }

    protected function nextPage(Crawler $crawler, SearchObjectInterface $searchableObject)
    {
        
        $link = null;

        $crawler->filter(self::PAGINATION_SELECTOR)->each(
            function(Crawler $node) use ($searchableObject, &$link)
            {
            
                if ($node->text() == ($searchableObject->getCurrentPage() + 1))
                {
                    
                    $link = $node->attr('href');
                    
                }

            }
        );
        
        if(null !== $link)
        {
            
            
            $url = explode('/search', $searchableObject->getResultsUrl());
            
            $href = $url[0] . $link;

            $this->client->setHeader('User-Agent', $this->userAgentProvider->getAgent());
            
            sleep(60);
            
            $crawler = $this->client->request('GET', $href);
            $searchableObject->incrementCurrentPage();
            
            return $crawler;
        }

        throw new \OutOfRangeException('No more results');
    }

    private function filterUrl($url)
    {
        return str_replace(' ', '', strip_tags($url));
    }

}
