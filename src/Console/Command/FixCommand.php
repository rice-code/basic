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

    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

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
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        // outputs a message followed by a "\n"
        $output->writeln('Whoa!');
        $path = $input->getArgument('path')[0];
        (new AccessorGenerator($path))->apply();
        // outputs a message without adding a "\n" at the end of the line
        $output->write('You are about to ');
        $output->write('create a user.');

        return Command::SUCCESS;
    }
}