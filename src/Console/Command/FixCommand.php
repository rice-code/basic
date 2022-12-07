<?php


namespace Rice\Basic\Console\Command;


use Rice\Basic\Support\Generate\Documentation\AccessorGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixCommand extends Command
{
    protected static $defaultName = 'rice:accessor';

    // the command description shown when running "php bin/console list"
    protected static $defaultDescription = 'Creates a new setting getting function doc.';

    // ...
    protected function configure(): void
    {
        $this->setDefinition([
            new InputArgument('path', InputArgument::IS_ARRAY, 'The path.'),
        ])
            ->setHelp('This command allows you to create a setting getting function');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path')[0];
        (new AccessorGenerator($path))->apply();

        $output->write('done.');

        return Command::SUCCESS;
    }
}