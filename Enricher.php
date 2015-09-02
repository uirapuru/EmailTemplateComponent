<?php

namespace TPN\EmailTemplatesComponent;

use TPN\EmailTemplatesComponent\Command\CommandInterface;
use TPN\EmailTemplatesComponent\Model\EmailTemplate;

/**
 * Class Enricher
 * @package TPN\EmailTemplatesComponent
 */
final class Enricher
{
    /**
     * @var CommandInterface[]
     */
    private $commands;

    /**
     * @param CommandInterface[] $commands
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;
    }

    /**
     * @param CommandInterface $command
     * @param $index
     */
    public function addCommand(CommandInterface $command, $index)
    {
        $this->commands[$index] = $command;
    }

    /**
     * @param EmailTemplate $template
     */
    public function processTemplate(EmailTemplate $template)
    {
        ksort($this->commands);

        foreach ($this->commands as $command) {
            $command->execute($template);
        }
    }
}
