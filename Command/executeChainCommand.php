<?php
namespace Kitpages\ChainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

use Kitpages\ChainBundle\Step\StepEvent;

class executeChainCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kitpages:chain:run-chain')
            ->addArgument('chainName', InputArgument::REQUIRED, 'chain name')
            ->setHelp(<<<EOT
The <info>kitpages:chain:run-chain</info> execute a command chain
EOT
            )
            ->setDescription('Name of the chain')
            ;


    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $chainName = $input->getArgument('chainName');

        $chainManager = $this->getContainer()->get('kitpages_chain.chain');
        $chain = $chainManager->getChain($chainName);

        $event = new StepEvent();

        $output->writeln("ChainName: $chainName; output=".$chain->execute($event));
    }



}