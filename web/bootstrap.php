<?php


/* @var $loader \Composer\Autoload\ClassLoader() */
$loader = require_once __DIR__.'/../vendor/autoload.php'; 
$loader->add('Tworzenieweb', __DIR__.'/../src');

$app = new Silex\Application(); 

$app['user_agent_provider_file'] = __DIR__ . '/../data/user-agents.txt';

$app['google_scrapper'] = $app->share(function($app) {
    return new \Tworzenieweb\Service\Google(new Goutte\Client(), $app['user_agent_provider']);
});

$app['user_agent_provider'] = $app->share(function($app) {
    return new \Tworzenieweb\Service\UserAgentProvider($app['user_agent_provider_file']);
});

return $app;