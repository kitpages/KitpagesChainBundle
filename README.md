KitpagesChainBundle
===================

This is a Symfony2 bundle that executes the classes one after the other.

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


## create command

* each command must inherit of kitpagesCommand
* each chain command must inherit of kitpagesChain

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
                src_dest: '/home/webadmin/htdocs/prod/www.kitpages.com'
        GitKitpages:
            class: '\Kitpages\ChainBundle\Command\GitKitpages'
            parameter_list:
                url: git.kitpages.com
    chain_list:
        kitpagesMep:
            command_list:
                CodeCopy:
                GitKitpages:
                    parameter_list:
                        url: git2.kitpages.com

        kitpagesCms:
            class: '\Kitpages\CmsBundle\Command\ChainCommand'
            command_list:
                CodeCopy:
                    parameter_list:
                        src_dir: '/home/webadmin/htdocs/dev/cms.kitpages.com'
                        src_dest: '/home/webadmin/htdocs/prod/cms.kitpages.com'
                InstallCms:
                    class: '\Kitpages\CmsBundle\Command\Install'
                    parameter_list:
                        level: master
```

## launch command in app/console

* for chain command
``` bash
php app/console kitChain:launchChain --chain=kitpagesMep
```

* for a command
``` bash
php app/console kitChain:launchCommand --command=CodeCopy --parameters_src_dir='/home/webadmin/htdocs/dev/cms2.kitpages.com'
```

## launch command in php

* for chain command

``` php
<?php
$chainKitpages = $this->get("kitpages_chain.chain");
$kitpagesMepChainKitpages = $chainKitpages->getChain('KitpagesMep');
$commandList = $kitpagesMepChainKitpages->getCommandList;
$commandList['GitKitpages']->setParameter('url', 'git2.kitpages.com');

$codeCopyCommandList = $kitpagesMepChainKitpages->getCommand('CodeCopy');
$codeCopyCommandList->setParameter('src_dir', '/home/webadmin/htdocs/dev/cms2.kitpages.com');
$kitpagesMepChainKitpages->execute();
```

* for a command

``` php
$commandKitpages = $this->get("kitpages_chain.command");
$codeCopyCommandKitpages = $commandKitpages->getCommand('CodeCopy');
$codeCopyCommandKitpages->setParameter('src_dir', '/home/webadmin/htdocs/dev/cms2.kitpages.com');

$codeCopyCommandKitpages->execute();
```

