<?php
/**
 * Created by Philippe Le Van.
 * Date: 18/02/13
 */

namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Step\StepEvent;

/**
 * This class is used to test events
 *
 * @example
 */
class StepListener
{
    public function onStepExecute(StepEvent $event)
    {
        $step = $event->getStep();
        if ($step->getParameter("isPropagationStopped") == true) {
            $event->stopPropagation();
        }
        if ($step->getParameter("isDefaultPrevented") == true) {
            $event->preventDefault();
        }
    }
    public function afterStepExecute(StepEvent $event)
    {
        $step = $event->getStep();
        if ($step->getParameter("isPropagationStopped") == true) {
            $event->stopPropagation();
        }
        if ($step->getParameter("isDefaultPrevented") == true) {
            $event->preventDefault();
        }
    }
}
