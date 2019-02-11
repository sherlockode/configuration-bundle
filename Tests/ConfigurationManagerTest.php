<?php

namespace Sherlockode\ConfigurationBundle\Tests;

use PHPUnit\Framework\TestCase;
use Sherlockode\ConfigurationBundle\Manager\ConfigurationManager;
use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;

class ConfigurationManagerTest extends TestCase
{
    public function testConstructor()
    {
        $config = [
            'path' => [
                'type'  => 'string',
                'label' => 'test',
                'translation_domain' => false,
            ],
        ];

        $configurationManager = new ConfigurationManager($config);
        $parameterDefinition = new ParameterDefinition('path', 'string', ['label' => 'test']);

        $this->assertEquals($configurationManager->get('path'), $parameterDefinition);
    }
}
