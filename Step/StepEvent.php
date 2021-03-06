<?php
/**
 * Created by Philippe Le Van.
 * Date: 15/02/13
 */

namespace Kitpages\ChainBundle\Step;

use Symfony\Component\EventDispatcher\Event;
use Kitpages\ChainBundle\Step\StepInterface;

/**
 * This class is an event transmitted from steps to steps
 */
class StepEvent
    extends Event
{
    protected $data = array();
    protected $isDefaultPrevented = false;
    protected $isPropagationStopped = false;
    protected $returnValue = null;

    protected $previousReturnValue = null;
    protected $currentReturnValue = null;
    protected $step = null;

    public function preventDefault()
    {
        $this->isDefaultPrevented = true;
    }

    public function isDefaultPrevented()
    {
        return $this->isDefaultPrevented;
    }

    public function stopPropagation()
    {
        $this->isPropagationStopped = true;
    }

    public function isPropagationStopped()
    {
        return $this->isPropagationStopped;
    }

    public function set($key, $val)
    {
        $this->data[$key] = $val;
    }


    public function get($key)
    {
        if (!array_key_exists($key, $this->data)) {
            return null;
        }
        return $this->data[$key];
    }

    ////
    // Step specific methods
    ////
    public function setPreviousReturnValue($ret)
    {
        $this->previousReturnValue = $ret;
    }

    public function getPreviousReturnValue()
    {
        return $this->previousReturnValue;
    }
    public function setReturnValue($ret)
    {
        $this->currentReturnValue = $ret;
    }

    public function getReturnValue()
    {
        return $this->currentReturnValue;
    }

    public function setStep(StepInterface $step = null)
    {
        $this->step = $step;
    }

    /**
     * @return StepInterface
     */
    public function getStep()
    {
        return $this->step;
    }
}
