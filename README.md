Sherlockode ConfigurationBundle
===============================

This bundle gives ability to define userland configuration options, useful in admin panels.


## Installation

The best way to install this bundle is to rely on [Composer](https://getcomposer.org/):

```bash
$ composer require sherlockode/configuration-bundle
```

## Setup

Enable the bundle in the kernel

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Sherlockode\ConfigurationBundle\SherlockodeConfigurationBundle(),
    ];
}
```

You will need a Parameter entity in order to store the configuration values in the database.

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sherlockode\ConfigurationBundle\Model\Parameter as BaseParameter;

/**
 * @ORM\Entity
 * @ORM\Table(name="parameter")
 */
class Parameter extends BaseParameter
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string")
     */
    protected $path;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string")
     */
    protected $value;
}
```

## Configuration

The entity class you just created must be set in the bundle's configuration:

```yaml
sherlockode_configuration:
    entity_class:
        parameter: App\Entity\Parameter
```

Now you are free to define any configuration entry you'd like by using the "parameters" key:
```yaml
sherlockode_configuration:
    entity_class:
        parameter: App\Entity\Parameter
    parameters:
        contact_email:
            label: My customer service contact email
            type: text
        max_user_login_attempts:
            label: Max login attemps before account blocking
            type: text
```

## Usage

This bundles provides the ParametersType, a FormType dedicated to editing the parameters.
The Model data for the form is an associative array of the paths and existing values.
You can get the existing parameters from the DB using the `ParameterManager`:

```php
<?php
use Sherlockode\ConfigurationBundle\Form\Type\ParametersType;

// $parameterManager has been injected
$data = $parameterManager->getAll();
// or using an associative array:
// $data = ['contact_email' => 'me@example.com', 'max_user_login_attempts' => 5];

$form = $this->createForm(ParametersType::class, $data);
// handle form submission
$form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) {
    $params = $form->getData();
    foreach ($params as $path => $value) {
        $this->parameterManager->set($path, $value);
    }
    $parameterManager->save();
}
//...
```

You are now able to retrieve any configuration value by using the ParameterManager service.
It is possible to provide a default value to return if the entry has not been set.

```php
$email = $parameterManager->get('contact_email');
$maxAttempts = $parameterManager->get('max_user_login_attempts', 5);
```

## Field types

Out of the box, the bundle provides several field types located in the namespace Sherlockode\ConfigurationBundle\FieldType.
The `getName()` method is the alias to use in your configuration (like `text` or `textarea`).

In order to add custom field types, you should create a service implementing the FieldTypeInterface interface and tag it with `sherlockode_configuration.field`.
