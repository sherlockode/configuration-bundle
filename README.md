Sherlockode ConfigurationBundle
===============================

This bundle gives ability to define userland configuration options, useful in admin panels.

[![CircleCI](https://circleci.com/gh/sherlockode/configuration-bundle.svg?style=shield)](https://circleci.com/gh/sherlockode/configuration-bundle)
[![Total Downloads](https://poser.pugx.org/sherlockode/configuration-bundle/downloads)](https://packagist.org/packages/sherlockode/configuration-bundle)
[![Latest Stable Version](https://poser.pugx.org/sherlockode/configuration-bundle/v/stable)](https://packagist.org/packages/sherlockode/configuration-bundle)

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
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="path", type="string")
     */
    protected $path;

    /**
     * @ORM\Column(name="value", type="text", nullable=true)
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

Now you are free to define any configuration entry you'd like by using the `parameters` key:
```yaml
sherlockode_configuration:
    entity_class:
        parameter: App\Entity\Parameter
    parameters:
        contact_email:
            label: My customer service contact email
            type: simple # the "simple" type renders by default as a TextType form element
        max_user_login_attempts:
            label: Max login attemps before account blocking
            type: simple
            options:
                # it is possible to customize the form type to use for a "simple" parameter type
                subtype: Symfony\Component\Form\Extension\Core\Type\IntegerType
        sales_date:
            label: Sales start date
            type: datetime
            options:
                required: false
```

### Translations

By default parameters labels are not translated in the form provided by the bundle.
If you want to use translations you can define a `translation_domain` key for each parameter
and use your translation key as the label.

```yaml
sherlockode_configuration:
    parameters:
        contact_email:
            label: customer.contact_email
            type: text
            translation_domain: my_app
```

### Options

Each type of field may accept a various range of options that can be defined under the `options` key.

Every field may have a `required` option to define if the input field will be required (it defaults to true).
The other options are up to the field and its needs. For instance, the choice field allow to define
`multiple` and `choices` options in order to customize the form.

```yaml
sherlockode_configuration:
    parameters:
        guess_access:
            label: Allow guest access
            type: choice
            options:
                required: true
                multiple: true
                choices:
                    yes: 1
                    no: 0
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

### Default types

Here are the field types provided in the bundle, located in the namespace `Sherlockode\ConfigurationBundle\FieldType` :

* simple
* checkbox
* choice
* datetime
* entity

### Custom Field types

In order to add custom field types, you should create a service implementing the `FieldTypeInterface` interface
and tag it with `sherlockode_configuration.field` (or use autoconfiguration).

The `getName()` return value is the alias of the field type to use in the configuration (like `simple` or `choice`).

### Using transformers

Due to the format of the Parameter entity in the database (the value is stored as a string, whatever the parameter type),
complex values cannot be stored directly.
For instance, we can serialize an array to fit the string type, or we may store the ID of a database entity.
The process may vary depending on your needs and the value to store, but the application needs to be aware of the process
to transform the PHP data into a string and the opposite process. This is done through transformers.

A transformer is an object implementing the `Sherlockode\ConfigurationBundle\Transformer\TransformerInterface`.
The interface has two methods `transform` and `reverseTransform`, similarly to the transformers used by Symfony in the Form Component.

The `transform` method takes the string representation and returns the PHP value,
when the `reverseTransform` takes your PHP value and returns back the corresponding scalar value.

In order to be used, an instance of the transformer should be returned by the `getModelTransformer`
method of the corresponding field type. If this method returns `null`, the bundle considers that no transformation is needed.

The bundle also provides a `CallbackTransformer` that can be used for faster implementations.
For instance handling an array can be done like this :

```php
public function getModelTransformer(ParameterDefinition $definition)
{
    return new CallbackTransformer(
        function ($data) {
            if (!$data) {
                return null;
            }
            if (false !== ($unserialized = @unserialize($data))) {
                return $unserialized;
            }
            return $data;
        },
        function ($data) {
            if (is_array($data)) {
                return serialize($data);
            }
            return $data;
        }
    );
}
```
