<?php
namespace Kitpages\ChainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class executeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kitChain:launchCommand')
            ->addArgument('commandSlug', InputArgument::REQUIRED, 'command slug')
            ->addOption('p', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'If set, no display message')
            ->setHelp(<<<EOT
The <info>kitChain:launchCommand</info> execute a command  with as parameter --p=nameParameter:valueParameter
EOT
            )
            ->setDescription('')
            ;


    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $commandSlug = $input->getArgument('commandSlug');
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
        $command = $commandManager->getCommand($commandSlug, $commandConfig);
        $command->execute();

    }



}