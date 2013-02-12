<?php
namespace Kitpages\ChainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class executeCommandCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kitpages:chain:run-command')
            ->addArgument('commandName', InputArgument::REQUIRED, 'command name')
            ->addOption('p', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'If set, no display message')
            ->setHelp(<<<EOT
The <info>kitpages:chain:run-command</info> execute a command  with as parameter --p=nameParameter:valueParameter
EOT
            )
            ->setDescription('run a kitpagesChainBundle command')
            ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commandName = $input->getArgument('commandName');
        $parameterStringList = $input->getOption('p');
        $parameterList = array();
        foreach($parameterStringList as $parameterString) {
            $parameterStringArray = explode(':', $parameterString);
            $parameterList[$parameterStringArray[0]] = $parameterStringArray[1];
        }
        $commandConfig = array(
            'parameter_list' => $parameterList
        );
        $commandManager = $this->getContainer()->get('kitpages_chain.command');
        $command = $commandManager->getCommand($commandName, $commandConfig);
        $output->writeln("CommandName: $commandName; output=".$command->execute()."\n");
    }



}