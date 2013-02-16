KitpagesChainBundle
===================

[![Build Status](https://travis-ci.org/kitpages/KitpagesChainBundle.png?branch=master)](https://travis-ci.org/kitpages/KitpagesChainBundle)

This bundle is used ton configure a workflow (a chain of steps) in
the config.yml in order to execute this workflow from app/console or
from PHP.

## Versions



## Actual state

This bundle is beta.

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
    step_list:
        CodeCopy:
            class: '\Kitpages\ChainBundle\Step\CodeCopy'
            parameter_list:
                src_dir: '/home/webadmin/htdocs/dev/www.kitpages.com'
                dest_dir: '/home/webadmin/htdocs/prod/www.kitpages.com'
        GitKitpages:
            class: '\Kitpages\ChainBundle\Step\GitKitpages'
            parameter_list:
                url: git.kitpages.com
    chain_list:
        kitpagesMep:
            step_list:
                CodeCopy: ~
                GitKitpages:
                    parameter_list:
                        url: git2.kitpages.com

        kitpagesCms:
            class: '\Kitpages\CmsBundle\Step\ChainStep'
            step_list:
                CodeCopy:
                    parameter_list:
                        src_dir: '/home/webadmin/htdocs/dev/cms.kitpages.com'
                        dest_dir: '/home/webadmin/htdocs/prod/cms.kitpages.com'
                InstallCms:
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
$stepList['GitKitpages']->setParameter('url', 'git2.kitpages.com');

$codeCopyStepList = $kitpagesMepChainKitpages->getStep('CodeCopy');
$codeCopyStepList->setParameter('src_dir', '/home/webadmin/htdocs/dev/cms2.kitpages.com');
$kitpagesMepChainKitpages->execute();
```


