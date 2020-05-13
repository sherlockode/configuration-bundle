<?php

namespace Sherlockode\ConfigurationBundle\Tests\Transformer;

use PHPUnit\Framework\TestCase;
use Sherlockode\ConfigurationBundle\Transformer\CallbackTransformer;

class CallbackTransformerTest extends TestCase
{
    public function testTransform()
    {
        $transformer = new CallbackTransformer(function($value) {
            return $value + 1;
        }, function() {});
        $this->assertEquals(2, $transformer->transform(1));
        $this->assertEquals(6, $transformer->transform(5));
    }

    public function testReverseTransform()
    {
        $transformer = new CallbackTransformer(function() {}, function($value) {
            return $value - 1;
        });
        $this->assertEquals(2, $transformer->reverseTransform(3));
        $this->assertEquals(9, $transformer->reverseTransform(10));
    }
}
