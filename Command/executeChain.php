<?php
namespace Kitpages\ChainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class executeChain extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kitChain:launchChain')
            ->addArgument('chainSlug', InputArgument::REQUIRED, 'chain slug')
            ->setHelp(<<<EOT
The <info>kitChain:launchChain</info> execute a command chain
EOT
            )
            ->setDescription('')
            ;


    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $chainSlug = $input->getArgument('chainSlug');

        $chainManager = $this->getContainer()->get('kitpages_chain.chain');
        $chain = $chainManager->getChain($chainSlug);
        $chain->execute();

    }



}