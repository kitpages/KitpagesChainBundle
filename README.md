KitpagesChainBundle
===================

[![Build Status](https://travis-ci.org/kitpages/KitpagesChainBundle.png?branch=master)](https://travis-ci.org/kitpages/KitpagesChainBundle)

This bundle is used ton configure a workflow (a chain of processors) in
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

Now tell composer to download the bundle by running the processor:

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


## create a processor

Each processor must implements ProcessorInterface or extend ProcessorAbstract. The DIC
is injected to the processor with the method setContainer.

```php
<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\ProcessorAbstract;

class ProcessorSample extends ProcessorAbstract
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

The following configuration defines 2 chain processor :

* kitpagesMep : a production start
* kitpagesCms : instantiate a KitpagesCms

Let's see the configuration in config.yml

``` yaml
kitpages_chain:
    processor_list:
        CodeCopy:
            class: '\Kitpages\ChainBundle\Processor\CodeCopy'
            parameter_list:
                src_dir: '/home/webadmin/htdocs/dev/www.kitpages.com'
                dest_dir: '/home/webadmin/htdocs/prod/www.kitpages.com'
        GitKitpages:
            class: '\Kitpages\ChainBundle\Processor\GitKitpages'
            parameter_list:
                url: git.kitpages.com
    chain_list:
        kitpagesMep:
            processor_list:
                CodeCopy: ~
                GitKitpages:
                    parameter_list:
                        url: git2.kitpages.com

        kitpagesCms:
            class: '\Kitpages\CmsBundle\Processor\ChainProcessor'
            processor_list:
                CodeCopy:
                    parameter_list:
                        src_dir: '/home/webadmin/htdocs/dev/cms.kitpages.com'
                        dest_dir: '/home/webadmin/htdocs/prod/cms.kitpages.com'
                InstallCms:
                    class: '\Kitpages\CmsBundle\Processor\Install'
                    parameter_list:
                        level: master
```

## using app/console
### run a processor with app/console

``` bash
# lancer une processore avec les paramètres du config.yml
php app/console kitpages:chain:run-processor CodeCopy

# lancer une processore en écrasant des paramètres du config.yml
php app/console kitpages:chain:run-processor CodeCopy --p=src_dir:'/home/webadmin/src' --p=dest_dir:'/tmp/destDir'
```

### run a chain with app/console

``` bash
php app/console kitpages:chain:run-chain kitpagesMep
```

## run a chain or a processor with PHP

### run a processor with PHP

``` php
$processorKitpages = $this->get("kitpages_chain.processor");
$codeCopyProcessorKitpages = $processorKitpages->getProcessor('CodeCopy');
$codeCopyProcessorKitpages->setParameter('src_dir', '/home/webadmin/htdocs/dev/cms2.kitpages.com');

$codeCopyProcessorKitpages->execute();
```

### run a chain with PHP

``` php
<?php
$chainManager = $this->get("kitpages_chain.chain");
$kitpagesMepChainKitpages = $chainManager->getChain('kitpagesMep');
$processorList = $kitpagesMepChainKitpages->getProcessorList();
$processorList['GitKitpages']->setParameter('url', 'git2.kitpages.com');

$codeCopyProcessorList = $kitpagesMepChainKitpages->getProcessor('CodeCopy');
$codeCopyProcessorList->setParameter('src_dir', '/home/webadmin/htdocs/dev/cms2.kitpages.com');
$kitpagesMepChainKitpages->execute();
```


