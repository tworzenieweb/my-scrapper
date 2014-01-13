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
        $client->shouldReceive('setHeader')->withAnyArgs();
        
        $hostProvider = m::mock('\Tworzenieweb\Service\UserAgentProvider');
        $hostProvider->shouldReceive('getAgent')->andReturn('Opera/9.80 (X11; Linux x86_64; U; en) Presto/2.9.168 Version/11.50');
        
        $service = new Google($client, $hostProvider);
        
        $model = new Tworzenieweb\Model\Google();
        $model->setTerm('phpunit')
              ->setUrl('https://github.com/sebastianbergmann/phpunit');
        
        $service->search($model);
        
        $this->assertEquals(4, $model->getPosition());
        $this->assertEquals(true, $model->getIsFound());
        
        
    }
}