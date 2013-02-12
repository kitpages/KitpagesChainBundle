KitpagesChainBundle
===================

[![Build Status](https://travis-ci.org/kitpages/KitpagesChainBundle.png?branch=master)](https://travis-ci.org/kitpages/KitpagesChainBundle)

This bundle is used ton configure a workflow (a chain of commands) in
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

Now tell composer to download the bundle by running the command:

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


## create a command

Each command must implements CommandInterface or extend CommandAbstract. The DIC
is injected to the command with the method setContainer.

```php
<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\CommandAbstract;

class CommandSample extends CommandAbstract
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

The following configuration defines 2 chain command :

* kitpagesMep : a production start
* kitpagesCms : instantiate a KitpagesCms

Let's see the configuration in config.yml

``` yaml
kitpages_chain:
    command_list:
        CodeCopy:
            class: '\Kitpages\ChainBundle\Command\CodeCopy'
            parameter_list:
                src_dir: '/home/webadmin/htdocs/dev/www.kitpages.com'
                dest_dir: '/home/webadmin/htdocs/prod/www.kitpages.com'
        GitKitpages:
            class: '\Kitpages\ChainBundle\Command\GitKitpages'
            parameter_list:
                url: git.kitpages.com
    chain_list:
        kitpagesMep:
            command_list:
                CodeCopy: ~
                GitKitpages:
                    parameter_list:
                        url: git2.kitpages.com

        kitpagesCms:
            class: '\Kitpages\CmsBundle\Command\ChainCommand'
            command_list:
                CodeCopy:
                    parameter_list:
                        src_dir: '/home/webadmin/htdocs/dev/cms.kitpages.com'
                        dest_dir: '/home/webadmin/htdocs/prod/cms.kitpages.com'
                InstallCms:
                    class: '\Kitpages\CmsBundle\Command\Install'
                    parameter_list:
                        level: master
```

## using app/console
### run a command with app/console

``` bash
# lancer une commande avec les paramètres du config.yml
php app/console kitpages:chain:run-command CodeCopy

# lancer une commande en écrasant des paramètres du config.yml
php app/console kitpages:chain:run-command CodeCopy --p=src_dir:'/home/webadmin/src' --p=dest_dir:'/tmp/destDir'
```

### run a chain with app/console

``` bash
php app/console kitpages:chain:run-chain kitpagesMep
```

## run a chain or a command with PHP

### run a command with PHP

``` php
$commandKitpages = $this->get("kitpages_chain.command");
$codeCopyCommandKitpages = $commandKitpages->getCommand('CodeCopy');
$codeCopyCommandKitpages->setParameter('src_dir', '/home/webadmin/htdocs/dev/cms2.kitpages.com');

$codeCopyCommandKitpages->execute();
```

### run a chain with PHP

``` php
<?php
$chainManager = $this->get("kitpages_chain.chain");
$kitpagesMepChainKitpages = $chainManager->getChain('kitpagesMep');
$commandList = $kitpagesMepChainKitpages->getCommandList();
$commandList['GitKitpages']->setParameter('url', 'git2.kitpages.com');

$codeCopyCommandList = $kitpagesMepChainKitpages->getCommand('CodeCopy');
$codeCopyCommandList->setParameter('src_dir', '/home/webadmin/htdocs/dev/cms2.kitpages.com');
$kitpagesMepChainKitpages->execute();
```


