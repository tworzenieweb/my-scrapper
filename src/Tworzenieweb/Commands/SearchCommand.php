<?php

namespace Tworzenieweb\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SearchCommand extends Command
{
    
    const DEFAULT_LOCALE = 'pl';
    
    protected function configure()
    {
        $this
            ->setName('tworzenieweb:search')
            ->setDescription('Search Results on google')
            ->addArgument(
                'term',
                InputArgument::REQUIRED,
                'The phrase used for search'
            )
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'URL to find in results'
            )
            ->addArgument(
                'locale',
                InputArgument::OPTIONAL,
                'Language version',
                self::DEFAULT_LOCALE
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $app = $this->getHelper('app')->getApplication();
        
        $model = new \Tworzenieweb\Model\Google();
        $model->setLocale($input->getArgument('locale'))
              ->setTerm($input->getArgument('term'))
              ->setUrl($input->getArgument('url'));
        
        
        $app['google_scrapper']->search($model);

        $output->writeln(var_export($model, true));
    }
}