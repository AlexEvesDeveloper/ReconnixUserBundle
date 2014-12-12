Overriding Default ReconnixUserBundle Forms
======================================

## Why would I need to?

The ReconnixUserBundle provides a User class with several properties that are common to all of your projects. However, you may need to add an extra field to your User class, for example, `firstName` and `lastName`. Adding these to the class and updating the database table is simple enough, but we need to make these fields available as form inputs alongside the existing form inputs. To do this, we need to override the Form classes provided by ReconnixUserBundle. (Of course, if we are adding new fields to the User class which don't require User input, then overriding the forms is not necessary).

## A quick word on the User forms

There are 2 forms which display the User fields; the Registration form, and the Profile form. The Registration form is rendered when creating new Users (/register or /users/new). The Profile form is rendered when viewing an existing User (/profile/edit or /users/view/{id}). When adding a new field to the User, it is likely that both forms will need overriding, if you wish for the new fields to be controlled by user input.

## Overriding a Form Type

Suppose that you have created an ORM user class with the following class name,
`Acme\UserBundle\Entity\User`. In this class, you have added a `name` property
because you would like to save the user's name as well as their username and
email address. Now, when a user registers for your site they should enter in their
name as well as their username, email and password. Below is an example `$name`
property and its validators.

``` php
// src/Acme/UserBundle/Entity/User.php
<?php

use Reconnix\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\MinLength(limit="3", message="The name is too short.", groups={"Registration", "Profile"})
     * @Assert\MaxLength(limit="255", message="The name is too long.", groups={"Registration", "Profile"})
     */
    protected $name;

    // ...
}
```

**Note:**

> By default, the Registration validation group is used when validating a new
> user registration. Unless you have overridden this value in the configuration,
> make sure you add the validation group named Registration to your name property.

If you try and register using the default registration form you will find that
your new `name` property is not part of the form. You need to create a custom
form type and configure the bundle to use it.

The first step is to create a new form type in your own bundle. The following
class declares the parent as 'reconnix_user_registration', which is the name of the Form provided by the bundle. It is then a case of adding the new field to the class.

``` php
// src/Acme/UserBundle/Form/Type/RegistrationFormType.php
<?php

namespace Acme\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('name');
    }

    public function getParent()
    {
        return 'reconnix_user_registration';
    }

    public function getName()
    {
        return 'acme_user_registration';
    }
}
```

Now that you have created your custom form type, you must declare it as a service
and add a tag to it. The tag must have a `name` value of `form.type` and an `alias`
value that is the equal to the string returned from the `getName` method of your
form type class. The `alias` that you specify is what you will use in the FOSUserBundle
configuration to let the bundle know that you want to use your custom form.

Below is an example of configuring your form type as a service in XML:

``` xml
<!-- src/Acme/UserBundle/Resources/config/services.xml -->
<?xml version="1.0" encoding="UTF-8" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>

        <service id="acme_user.registration.form.type" class="Acme\UserBundle\Form\Type\RegistrationFormType">
            <tag name="form.type" alias="acme_user_registration" />
        </service>

    </services>

</container>
```

Or if you prefer YAML:

``` yaml
# src/Acme/UserBundle/Resources/config/services.yml
services:
    acme_user.registration.form.type:
        class: Acme\UserBundle\Form\Type\RegistrationFormType
        tags:
            - { name: form.type, alias: acme_user_registration }
```

**Note:**

> In the form type service configuration you have specified the `fos_user.model.user.class`
> container parameter as a constructor argument. Unless you have redefined the
> constructor in your form type class, you must include this argument as it is a
> requirement of the FOSUserBundle form type that you extended.

Finally, you must tell the ReconnixUserBundle that you want to use your new form from now on. Add the following to `config.yml`. 

``` yaml
# app/config/config.yml
reconnix_user:
    # ...
    registration:
        form:
            type: acme_user_registration
```

Note how the `alias` value used in your form type's service configuration tag
is used in the bundle configuration to tell the ReconnixUserBundle to use your custom
form type.

### Note

The above example will add the fields to the Registration form. To also add them to the Profile form, you repeat the steps, and in the Form class, you declare the parent as `reconnix_user_profile`, and you add the following to `config.yml`:

``` yaml
# app/config/config.yml
reconnix_user:
    # ...
    profile:
        form:
            type: acme_user_profile
```


