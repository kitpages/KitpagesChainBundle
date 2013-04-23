<?php
namespace Kitpages\ChainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

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
        $stepName = $input->getArgument('stepName');
        $stepConfigList = $this->getContainer()->getParameter("kitpages_chain.shared_step_list");
        if (!$stepName) {
            foreach($stepConfigList as $stepName => $stepConfig) {
                $help = "no help";
                if (isset($stepConfig['help']) && isset($stepConfig['help']['short'])) {
                    if ($stepConfig['help']['private']) {
                        continue;
                    }
                    $help = $stepConfig['help']['short'];
                }
                $output->writeln("$stepName : ".$help);
            }
            return;
        }

        if (!isset($stepConfigList[$stepName])) {
            $output->writeln("no step with the name $stepName.");
            return;
        }

        $stepConfig = $stepConfigList[$stepName];
        $shortHelp = "no help";
        if (isset($stepConfig['help']) && isset($stepConfig['help']['short'])) {
            $shortHelp = $stepConfig['help']['short'];
        }
        $completeHelp = "no complete help";
        if (isset($stepConfig['help']) && isset($stepConfig['help']['complete'])) {
            $completeHelp = $stepConfig['help']['complete'];
        }

        $output->writeln("$stepName : ".$shortHelp);
        $output->writeln("--");
        $output->writeln($completeHelp);
        $output->writeln("--");

    }



}