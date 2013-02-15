<?php
namespace Kitpages\ChainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class executeProcessorCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kitpages:chain:run-processor')
            ->addArgument('processorName', InputArgument::REQUIRED, 'processor name')
            ->addOption('p', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'If set, no display message')
            ->setHelp(<<<EOT
The <info>kitpages:chain:run-processor</info> execute a processor  with as parameter --p=nameParameter:valueParameter
EOT
            )
            ->setDescription('run a kitpagesChainBundle processor')
            ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $processorName = $input->getArgument('processorName');
        $parameterStringList = $input->getOption('p');
        $parameterList = array();
        foreach($parameterStringList as $parameterString) {
            $parameterStringArray = explode(':', $parameterString);
            $parameterList[$parameterStringArray[0]] = $parameterStringArray[1];
        }
        $processorConfig = array(
            'parameter_list' => $parameterList
        );
        $processorManager = $this->getContainer()->get('kitpages_chain.processor');
        $processor = $processorManager->getProcessor($processorName, $processorConfig);
        $output->writeln("ProcessorName: $processorName; output=".$processor->execute()->getReturnValue()."\n");
    }



}