<?php

use Tworzenieweb\Service\Google;
use \Mockery as m;
use Symfony\Component\DomCrawler\Crawler;

class GoogleTest extends \PHPUnit_Framework_TestCase
{
    
    public function testSearchStatic()
    {
        
        $content = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'response.html');
        
        $crawler = new Crawler(null, 'https://www.google.pl/?q=phpunit');
        $crawler->addContent($content, 'text/html');
        
        $client = m::mock('\Goutte\Client');
        $client->shouldReceive('request')->withAnyArgs()->andReturn($crawler);
        
        $service = new Google($client);
        
        $model = new Tworzenieweb\Model\Google();
        $model->setTerm('phpunit')
              ->setMaxPages(20)
              ->setUrl('https://github.com/sebastianbergmann/phpunit');
        
        $service->search($model);
        
        $this->assertEquals(4, $model->getPosition());
        $this->assertEquals(true, $model->getIsFound());
        
        
    }
}