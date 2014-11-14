<?php

namespace UlysseCounter;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GetWordListCommand extends Command{

  protected function configure(){
        $this
            ->setName('words:top')
            ->setDescription('Récupère le top des mots les plus utilisés')
            ->addArgument(
                'design',
                InputArgument::REQUIRED,
                'design name'
            )
            ->addArgument(
                'view',
                InputArgument::REQUIRED,
                'view name'
            )
            ->addOption(
               'min',
               null,
               InputOption::VALUE_REQUIRED,
               'Nombre minimal de caractères dans un mot (default 0)',
               0

            )
            ->addOption(
               'limit',
               null,
               InputOption::VALUE_REQUIRED,
               'Top maximum à extraire (default 10)',
               10
            )
            ->addOption(
               'host',
               null,
               InputOption::VALUE_REQUIRED,
               'The database Host'
            )
            ->addOption(
               'database',
               null,
               InputOption::VALUE_REQUIRED,
               'The database name'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $design = $input->getArgument('design');
        $view = $input->getArgument('view');

        $host = $input->getOption('host');
        $database = $input->getOption('database');
        $min = $input->getOption('min');
        $limit = $input->getOption('limit');
        
        $couch = new CouchDb(array("url" => $host, "database" => $database));
        $view_url = $couch->getViewUrl($design,
                                       $view, 
                               array("group"=> "true",
                                     "startkey" => '['.$min.',"*"]'
                                ));
        $raw = file_get_contents($view_url);
        $words = json_decode($raw, true);

        usort($words["rows"], function($a, $b){
          return $b["value"] - $a["value"];
        });

        $table = $this->getHelper('table');
        
        $table
            ->setHeaders(array('Mot', 'Occurence'))
            ->setRows(
              array_map(function($row){
                          return [$row["key"][1], $row["value"]];
                        },
                        array_slice($words["rows"],0,$limit)
              )
            )
        ;
        $table->render($output);

    }
  
}