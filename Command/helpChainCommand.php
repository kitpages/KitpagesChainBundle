<?php
namespace Kitpages\ChainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Kitpages\ChainBundle\Chain\ChainManager;

class helpChainCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kitpages:chain:help-chain')
            ->addArgument('chainName', InputArgument::OPTIONAL, 'chain name')
            ->setHelp(<<<EOT
The <info>kitpages:chain:help-chain</info> display help
EOT
            )
            ->setDescription('Display the help of a chain')
            ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ChainManager $stepManager */
        $chainManager = $this->getContainer()->get("kitpages_chain.chain");

        $chainName = $input->getArgument('chainName');

        $chainConfigList = $this->getContainer()->getParameter("kitpages_chain.chain_list");

        if (!$chainName) {
            foreach($chainConfigList as $chainName => $chainConfig) {
                $resultingConfig = $chainManager->getChainConfig($chainName);
                if ($resultingConfig['help']['private']) {
                    continue;
                }
                $help = $resultingConfig['help']['short'];
                $output->writeln("$chainName : ".$help);
            }
            return;
        }

        $resultingConfig = $chainManager->getChainConfig($chainName);

        if (!$resultingConfig) {
            $output->writeln("no chain with the name $chainName.");
            return;
        }

        $output->writeln("$chainName : ".$resultingConfig["help"]["short"]);
        $output->writeln("-- description --");
        $output->writeln($resultingConfig["help"]["complete"]);
        $output->writeln("-- stepList --");
        foreach ($resultingConfig["step_list"] as $key => $val) {
            $output->writeln("-> $key");
        }
        $output->writeln("-- end --");
    }



}