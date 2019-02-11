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
                'translation_domain' => 'translation',
            ],
        ];

        $configurationManager = new ConfigurationManager($config);
        $parameterDefinition = new ParameterDefinition('path', 'string');
        $parameterDefinition->setLabel('test');
        $parameterDefinition->setTranslationDomain('translation');

        $this->assertEquals($configurationManager->get('path'), $parameterDefinition);

        $this->assertTrue(is_array($configurationManager->getDefinedParameters()));
    }
}
