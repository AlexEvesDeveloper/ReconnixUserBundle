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

#### FOSUserBundle configuration

The following three parameters are required. `firewall_name` must match the name of the firewall that you specify later. The `user_class` must refer to the namespace of the User Entity that you will create in your project.

```yaml
# app/config/config.yml

fos_user:
    db_driver: orm
    firewall_name: main
    user_class: MyProject\UserBundle\Entity\User 
```

#### Routing configuration

The `reconnix_user` configuration will enable access to a /users area. To see the routes the are available within /users, run `php app/console router:debug`

```yaml
# app/config/routing.yml

reconnix_user:
    resource: "@ReconnixUserBundle/Controller/"
    type:     annotation
    prefix:   /users

fos_user_security:
    resource: "@FOSUserBundle/Resources/config/routing/security.xml"

fos_user_profile:
    resource: "@FOSUserBundle/Resources/config/routing/profile.xml"
    prefix: /profile

fos_user_register:
    resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
    prefix: /register

fos_user_resetting:
    resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
    prefix: /resetting

fos_user_change_password:
    resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"
    prefix: /profile
```

#### Security configuration

**Important:** The roles configured under `role_hierarchy` determine which roles can be assigned and managed. Below is a good default option. The `access_control` configuration dictates that only users with ROLE_SUPER_ADMIN can access the user manager area

```yaml
# app/config/security.yml
security:
    encoders:
        FOS\UserBundle\Model\UserInterface: sha512

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: ROLE_ADMIN

    providers:
        fos_userbundle:
            id: fos_user.user_provider.username

    firewalls:
        main:
            pattern: ^/
            form_login:
                provider: fos_userbundle
                csrf_provider: form.csrf_provider
            logout:       true
            anonymous:    true

    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/, role: ROLE_ADMIN }
        - { path: ^/users/, role: ROLE_SUPER_ADMIN }
```

