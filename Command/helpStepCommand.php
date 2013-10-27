<?php
namespace Kitpages\ChainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Kitpages\ChainBundle\Step\StepManager;

class helpStepCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kitpages:chain:help-step')
            ->addArgument('stepName', InputArgument::OPTIONAL, 'step name')
            ->setHelp(<<<EOT
The <info>kitpages:chain:help-step</info> display help
EOT
            )
            ->setDescription('Display the help of steps')
            ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var StepManager $stepManager */
        $stepManager = $this->getContainer()->get("kitpages_chain.step");

        $stepName = $input->getArgument('stepName');

        $stepConfigList = $this->getContainer()->getParameter("kitpages_chain.shared_step_list");
        if (!$stepName) {
            foreach($stepConfigList as $stepName => $stepConfig) {
                $resultingConfig = $stepManager->getResultingConfig($stepName);
                if ($resultingConfig['help']['private']) {
                    continue;
                }
                $help = $resultingConfig['help']['short'];
                $output->writeln("$stepName : ".$help);
            }
            return;
        }

        $resultingConfig = $stepManager->getResultingConfig($stepName);
        if (!$resultingConfig) {
            $output->writeln("no step with the name $stepName.");
            return;
        }

        $output->writeln("$stepName : ".$resultingConfig["help"]["short"]);
        $output->writeln("-- description --");
        $output->writeln($resultingConfig["help"]["complete"]);
        $output->writeln("-- default values --");
        $stepConfigStack = $stepManager->getStepConfigStack($stepName);
        $output->writeln("hierarchy: ".implode(" <- ", array_keys($stepConfigStack)));
        $output->writeln("stepClass: ".$resultingConfig["class"]);
        $output->writeln("default parameters:");
        foreach ($resultingConfig["parameter_list"] as $key=>$val) {
            $output->writeln("-> $key = $val");
        }
        $output->writeln("-- end --");
    }



}