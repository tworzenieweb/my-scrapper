<?php

use Mparaiso\Provider\ConsoleServiceProvider;
use Tworzenieweb\Commands\SearchCommand;

$app = include_once 'bootstrap.php';

$app['debug'] = true;

$app->register(new ConsoleServiceProvider);

$app['console']->add(new SearchCommand());

$app["console"]->run();
