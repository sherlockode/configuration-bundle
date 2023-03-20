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
                'default_value' => '42',
                'options' => ['opt' => 'val'],
                'translation_domain' => 'translation',
            ],
        ];

        $configurationManager = new ConfigurationManager($config);
        $parameterDefinition = new ParameterDefinition('path', 'string');
        $parameterDefinition->setLabel('test');
        $parameterDefinition->setOptions(['opt' => 'val']);
        $parameterDefinition->setTranslationDomain('translation');
        $parameterDefinition->setDefaultValue('42');

        $this->assertEquals($parameterDefinition, $configurationManager->get('path'));
        $this->assertFalse($configurationManager->has('foo'));
        $this->assertTrue(is_array($configurationManager->getDefinedParameters()));

        $this->expectException('Exception');
        $configurationManager->get('foo');
    }

    public function testTypeMissing()
    {
        $config = [
            'path' => [
                'label' => 'test',
                'options' => ['opt' => 'val'],
                'translation_domain' => 'translation',
            ],
        ];

        $this->expectException('LogicException');
        new ConfigurationManager($config);
    }
}
