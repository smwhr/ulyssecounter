<?php

namespace UlysseCounter;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DataSendCommand extends Command{

  protected function configure(){
        $this
            ->setName('data:send')
            ->setDescription('Envoie les donnnées en base')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'Which file do you want to send ?'
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
        $file = $input->getArgument('file');
        $host = $input->getOption('host');
        $database = $input->getOption('database');
        
        $progress = $this->getHelper('progress');
        $iter = new TextIterator($file);
        $couch = new CouchDb(array("url" => $host, "database" => $database));

        $max = $iter->maxcursor;
        $progress->start($output, $max);

        foreach($iter as $position => $sentence){
            try{
                $ok = $couch->addDocument(array("sentence"=>$sentence));
                $progress->setCurrent($position);

            }catch(CouchDbException $ce){
                $output->writeln('<error>Error '.$ce->getMessage().'</error>');
                return 1;
            }
        }

        $progress->finish();


        $output->writeln("<info>Done !</info>");
        $output->writeln("Your final data should be ready in a minute or two.");
    }
  
}