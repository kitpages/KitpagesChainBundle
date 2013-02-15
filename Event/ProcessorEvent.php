<?php
/**
 * Created by Philippe Le Van.
 * Date: 15/02/13
 */

namespace Kitpages\ChainBundle\Event\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * This class is an event transmitted from processors to processors
 */
class ProcessorEvent
    extends Event
{
    protected $data = array();
    protected $isDefaultPrevented = false;
    protected $isPropagationStopped = false;

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
}
