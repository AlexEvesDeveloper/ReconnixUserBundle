Getting Started With ReconnixUserBundle
=======================================

This bundle extends the [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle) by adding Role management via an admin interface. Therefore, if you are attempting anything that is not covered by this documentation, you should fall back to the FOSUserBundle to find your solution.

The bundle requires a series of configuration steps. This page describes the steps in their required order.

## Prerequisites

A fresh install of Symfony 2 must be configured and ready to go

## Installation and configuration

### Step 1: Download FOSUserBundle and ReconnixUserBundle using composer

As this is a wrapper for FOSUserBundle, that will also need installing, alongside this bundle. Let's install that first:

From the root of the project, (where your `composer.json` file lives), run the following command:

``` bash
$ php composer.phar require friendsofsymfony/user-bundle
```

To install this bundle, we need to do a little extra work, as it has not been added as an offical Packagist bundle. Open your `composer.json` file and add the following:

```js
{
	"require": {
		// ...
		"reconnix/userbundle": "dev-master"
	},
    "repositories": [{
        "type": "vcs",
        "url": "https://github.com/AlexEvesDeveloper/ReconnixUserBundle.git"
    }],
}
```

Now run the update command:

``` bash
$ php composer.phar update reconnix/userbundle
```

### Step 2: Enable the bundles

Add both bundles to the `AppKernel`:

``` php
# app/AppKernel.php

<?php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FOS\UserBundle\FOSUserBundle(),
        new Reconnix\UserBundle\ReconnixUserBundle(),
    );
}
```

### Step 3: Set up some necessary configuration

```js
# app/config/config.yml

fos_user:
    db_driver: orm
    firewall_name: main
    user_class: MyProject\UserBundle\Entity\User 
````