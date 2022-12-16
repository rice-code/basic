<?php

namespace Rice\Basic\Console\Command;

use Rice\Basic\Enum\BaseEnum;
use Rice\Basic\Exception\ConsoleException;
use Rice\Basic\Support\Generate\FixGenerate;
use Rice\Basic\Support\Generate\FileGenerate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JsonToClassCommand extends Command
{
    protected static $defaultName = 'rice:json_to_class';

    // the command description shown when running "php bin/console list"
    protected static $defaultDescription = 'json to generate class files';

    // ...
    protected function configure(): void
    {
        $this->setDefinition([
            new InputArgument('json_file_path', InputArgument::REQUIRED, 'json file path.'),
            new InputArgument('dir_path', InputArgument::REQUIRED, 'generate file dir path.'),
        ])
            ->setHelp('This command allows you to create a setting getting function');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jsonFilePath = $input->getArgument('json_file_path');
        $dirPath      = $input->getArgument('dir_path');

        if (!file_exists($jsonFilePath)) {
            throw new ConsoleException(BaseEnum::FILE_NOT_EXISTS);
        }

        if (!file_exists($dirPath)) {
            throw new ConsoleException(BaseEnum::DIR_NOT_EXISTS);
        }

        (new FileGenerate($jsonFilePath, $dirPath))->handle();

        FixGenerate::handle($dirPath);

        $output->write('done.');

        return Command::SUCCESS;
    }
}
