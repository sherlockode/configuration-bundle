<?php

namespace Sherlockode\ConfigurationBundle\Tests;

use PHPUnit\Framework\TestCase;
use Sherlockode\ConfigurationBundle\FieldType\FieldTypeInterface;
use Sherlockode\ConfigurationBundle\Manager\FieldTypeManager;

class FieldTypeManagerTest extends TestCase
{
    public function testAddFieldType()
    {
        $fieldTypeText = $this->createConfiguredMock(FieldTypeInterface::class, [
            'getName' => 'text',
        ]);

        $fieldTypeNumber = $this->createConfiguredMock(FieldTypeInterface::class, [
            'getName' => 'number',
        ]);

        $fieldTypeManager = new FieldTypeManager();
        $fieldTypeManager->addFieldType($fieldTypeText);
        $fieldTypeManager->addFieldType($fieldTypeNumber);

        $this->assertEquals($fieldTypeManager->getField('text'), $fieldTypeText);
        $this->assertEquals($fieldTypeManager->getField('number'), $fieldTypeNumber);

        $this->expectExceptionMessage('Unknown parameter type "date"');
        $fieldTypeManager->getField('date');
    }
}
