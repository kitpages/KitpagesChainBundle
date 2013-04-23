KitpagesChainBundle
===================

[![Build Status](https://travis-ci.org/kitpages/KitpagesChainBundle.png?branch=master)](https://travis-ci.org/kitpages/KitpagesChainBundle)

This bundle is used ton configure a workflow (a chain of steps) in
the config.yml in order to execute this workflow from app/console or
from PHP.

## Versions

04/23/2013 : v1.4.0 help system
04/18/2013 : v1.3.0 step inheritance with the optional
02/19/2013 : v1.2.0 step parameter template rendering
02/18/2013 : v1.1.0 steps are not container aware anymore. Services are injected in config.yml
02/18/2013 : v1.0.0 first stable version

## Actual state

This bundle is stable, tested and under travis-ci.

## Installation

Add KitpagesChainBundle in your composer.json

```js
{
    "require": {
        "kitpages/chain-bundle": "*"
    }
}
```

Now tell composer to download the bundle by running the step:

``` bash
$ php composer.phar update kitpages/chain-bundle
```

AppKernel.php

``` php
$bundles = array(
    ...
    new Kitpages\ChainBundle\KitpagesChainBundle(),
);
```


## create a step

Each step must implements StepInterface or extend StepAbstract. The DIC
is injected to the step with the method setContainer.

```php
<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Step\StepAbstract;

class StepSample extends StepAbstract
{
    public function execute() {
        // do whatever you want
        return $whatever;
    }
}
```

## create a chain

You generaly don't have to create a chain. You can use the default chain class
located in Model/Chain.php. Just look at this class if you need to change the
default behavior.

## Configuration example

The following configuration defines 2 chain step :

* kitpagesMep : a production start
* kitpagesCms : instantiate a KitpagesCms

Let's see the configuration in config.yml

``` yaml
kitpages_chain:
    shared_step_list:
        CodeCopy:
            class: '\Kitpages\ChainBundle\Step\CodeCopy'
            parameter_list:
                src_dir: '/home/webadmin/htdocs/dev/www.kitpages.com'
                dest_dir: '/home/webadmin/htdocs/prod/www.kitpages.com'
            help:
                short: copy a directory to another
                complete: |
                    This step copies a directory to another
                    @param string return string returned by the step
                    @service listener service used for xxx
                    @event:returnValue string
                    @return boolean true if ok or false

        CodeCopyPreProd:
            parent_shared_step: CodeCopy
            parameter_list:
                dest_dir: '/home/webadmin/htdocs/pre-prod/www.kitpages.com'
        GitKitpages:
            class: '\Kitpages\ChainBundle\Step\GitKitpages'
            parameter_list:
                url: git.kitpages.com
            service_list:
                logger: logger
    chain_list:
        kitpagesMep:
            step_list:
                CodeCopyLabel:
                    parent_shared_step: CodeCopy
                GitKitpagesLabel:
                    parent_shared_step: GitKitpages
                    parameter_list:
                        url: git2.kitpages.com

        kitpagesCms:
            class: '\Kitpages\CmsBundle\Step\ChainStep'
            step_list:
                CodeCopyLabel:
                    parent_shared_step: CodeCopy
                    parameter_list:
                        src_dir: '/home/webadmin/htdocs/dev/cms.kitpages.com'
                        dest_dir: '/home/webadmin/htdocs/prod/cms.kitpages.com'
                InstallCmsLabel:
                    parent_shared_step: InstallCms
                    class: '\Kitpages\CmsBundle\Step\Install'
                    parameter_list:
                        level: master
```

## using app/console
### run a step with app/console

``` bash
# lancer une stepe avec les paramètres du config.yml
php app/console kitpages:chain:run-step CodeCopy

# lancer une stepe en écrasant des paramètres du config.yml
php app/console kitpages:chain:run-step CodeCopy --p=src_dir:'/home/webadmin/src' --p=dest_dir:'/tmp/destDir'
```

### run a chain with app/console

``` bash
php app/console kitpages:chain:run-chain kitpagesMep
```

## run a chain or a step with PHP

### run a step with PHP

``` php
$stepKitpages = $this->get("kitpages_chain.step");
$codeCopyStepKitpages = $stepKitpages->getStep('CodeCopy');
$codeCopyStepKitpages->setParameter('src_dir', '/home/webadmin/htdocs/dev/cms2.kitpages.com');

$codeCopyStepKitpages->execute();
```

### run a chain with PHP

``` php
<?php
$chainManager = $this->get("kitpages_chain.chain");
$kitpagesMepChainKitpages = $chainManager->getChain('kitpagesMep');
$stepList = $kitpagesMepChainKitpages->getStepList();
$stepList['GitKitpagesLabel']->setParameter('url', 'git2.kitpages.com');
```

## Using events

With events, you can alter the way each step is executed. You can :

* prevent the step from running the execute() method. $event->preventDefault()
* in a chain you can stop the processing of the chain by using $event->stopPropagation()
* alter the step before or after the execution
* change return value
* ...

Create a listener :

```php
namespace Foo\Bar;
class StepListener
{
    public function onStepExecute(StepEvent $event)
    {
        $step = $event->getStep();
        // do whatever you want with the current step
        // $event->preventDefault();
        // $event->stopPropagation();
        // log something ?
    }
}
```

register listener :

```yaml
services:
    stepListener:
        class: Foo\Bar\StepListener
        tags:
            - { name: kernel.event_listener, event: kitpages_chain.on_step_execute, method: onStepExecute }
```

``` php
use Kitpages\ChainBundle\Step\StepEvent;
[...]

$event = new StepEvent();

$stepKitpages = $this->get("kitpages_chain.step");
$codeCopyStepKitpages = $stepKitpages->getStep('CodeCopy');
$codeCopyStepKitpages->setParameter('src_dir', '/home/webadmin/htdocs/dev/cms2.kitpages.com');

$codeCopyStepKitpages->execute($event);
```

