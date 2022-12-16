<?php

declare(strict_types=1);

namespace Rice\Basic\Console;

use Rice\Basic\Console\Command\FixCommand;
use Rice\Basic\Console\Command\JsonToClassCommand;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Application as BaseApplication;

final class Application extends BaseApplication
{
    public const VERSION          = '1.0-dev';
    public const VERSION_CODENAME = 'Oliva';

    public function __construct()
    {
        parent::__construct('PHP CS Fixer', self::VERSION);

        // in alphabetical order
        $this->add(new FixCommand());
        $this->add(new JsonToClassCommand());
    }

    public static function getMajorVersion(): int
    {
        return (int) explode('.', self::VERSION)[0];
    }

    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output): int
    {
        $stdErr = $output instanceof ConsoleOutputInterface
            ? $output->getErrorOutput()
            : ($input->hasParameterOption('--format', true) && 'txt' !== $input->getParameterOption('--format', null, true) ? null : $output);

        $result = parent::doRun($input, $output);

        if (
            null !== $stdErr
            && $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE
        ) {
            $triggeredDeprecations = Utils::getTriggeredDeprecations();

            if (\count($triggeredDeprecations) > 0) {
                $stdErr->writeln('');
                $stdErr->writeln($stdErr->isDecorated() ? '<bg=yellow;fg=black;>Detected deprecations in use:</>' : 'Detected deprecations in use:');
                foreach ($triggeredDeprecations as $deprecation) {
                    $stdErr->writeln(sprintf('- %s', $deprecation));
                }
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands(): array
    {
        return [new HelpCommand(), new ListCommand()];
    }
}
