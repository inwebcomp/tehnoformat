<?php

namespace Console\Command;

use Console\Command;
use Hex\Helpers\Generator;
use Hex\Helpers\XMLModelGenerator;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class CreateModelCommand extends Command
{
    protected function configure()
    {
        //  {class} {--c|controller} {--d|db_table} {--dml|db_table_ml} {--f|force}
        $this
		->setName('create:model')

        ->setDescription('Creates a new model')

        ->setHelp('This command allows you to create a model with controller')
        
        ->addArgument('class', InputArgument::REQUIRED)
        ->addOption('controller', 'c', InputOption::VALUE_NONE, 'Create controller')
        ->addOption('db_table', 'd', InputOption::VALUE_NONE, 'Create database table')
        ->addOption('db_table_ml', 'l', InputOption::VALUE_NONE, 'Create multilang database table')
        ->addOption('force', 'f', InputOption::VALUE_NONE, 'Overwrite existed');

		$this->include_app();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $class = $input->getArgument('class');
        
        $ifCreateController = $input->getOption('controller');
        $ifCreateDBTable = $input->getOption('db_table');
        $ifCreateDBTable_ml = $input->getOption('db_table_ml');
        $force = $input->getOption('force');

        // ---
        Generator::createModel($class, null, $force);
        $output->writeln('Model `' . $class . '` created');

        XMLModelGenerator::create($class, true, null, $force);
        $output->writeln('XML Model `' . $class . '` created');

        if ($ifCreateController) {
            $controller = Generator::getControllerName($class);
            Generator::createController($controller, $class, $force);
            $output->writeln('Controller `' . $controller . '` created');
        }

        if ($ifCreateDBTable or $ifCreateDBTable_ml) { echo $class;
            Generator::createTable($class, true, true, $ifCreateDBTable_ml);
            $output->writeln('Table `' . $class . '` created');

            if ($ifCreateDBTable_ml) {
                $output->writeln('Table `' . $class . '_ml` created');
            }
        }
    }
}