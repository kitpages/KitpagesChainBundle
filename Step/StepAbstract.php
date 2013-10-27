<?php
namespace Kitpages\ChainBundle\Step;

use Kitpages\ChainBundle\Step\StepInterface;

abstract class StepAbstract implements StepInterface
{
    protected $parameterList = array();
    protected $serviceList = array();

    public function setParameter($key, $value)
    {
        $this->parameterList[$key] = $value;
        return $this;
    }

    public function getParameter($key)
    {
        if (!array_key_exists($key, $this->parameterList)) {
            return null;
        }
        return $this->parameterList[$key];
    }

    public function setService($key, $service)
    {
        $this->serviceList[$key] = $service;
        return $this;
    }

    public function getService($key)
    {
        if (!isset($this->serviceList[$key])) {
            return null;
        }
        return $this->serviceList[$key];
    }

    /**
     * used to transform a value in a parameter.
     *
     * ex :
     * $parameterList['foo'] = 'ls {{fileName}} {{bar}}';
     * $parameterList['fileName'] = '/tmp/titi';
     * // $parameterList['bar'] is undefined
     *
     *  => $this->getRenderedParameter('foo', function ($str) { strtoupper($str); } );
     * Result : ls /TMP/TITI
     *
     * @param $key
     * @param callable $escaper
     * @return mixed|null
     */
    public function getRenderedParameter($key, $escaper = null)
    {
        $subject = $this->getParameter($key);
        preg_match_all('/{{([a-zA-Z0-9\.\-\_]+)}}/', $subject, $matches);
        $parameterList = $matches[1];
        foreach ($parameterList as $parameterKey) {
            $val = $this->getParameter($parameterKey);
            if ($val) {
                if (is_callable($escaper)) {
                    $val = $escaper($val);
                }
                $subject = str_replace('{{'.$parameterKey.'}}', $val, $subject);
            } else {
                $subject = str_replace('{{'.$parameterKey.'}}', '', $subject);
            }
        }
        return $subject;
    }

}