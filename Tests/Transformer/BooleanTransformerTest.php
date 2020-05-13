<?php

namespace Sherlockode\ConfigurationBundle\Tests\Transformer;

use PHPUnit\Framework\TestCase;
use Sherlockode\ConfigurationBundle\Transformer\BooleanTransformer;

class BooleanTransformerTest extends TestCase
{
    public function testTransform()
    {
        $transformer = new BooleanTransformer();
        $this->assertEquals(false, $transformer->transform(null));
        $this->assertEquals(false, $transformer->transform('0'));
        $this->assertEquals(false, $transformer->transform(0));
        $this->assertEquals(true, $transformer->transform(1));
        $this->assertEquals(true, $transformer->transform(true));
        $this->assertEquals(true, $transformer->transform('true'));
        $this->assertEquals(true, $transformer->transform('false'));
    }

    public function testReverseTransform()
    {
        $transformer = new BooleanTransformer();
        $this->assertEquals(0, $transformer->reverseTransform(false));
        $this->assertEquals(1, $transformer->reverseTransform(true));
        $this->assertEquals(0, $transformer->reverseTransform('0'));
        $this->assertEquals(1, $transformer->reverseTransform('1'));
        $this->assertEquals(0, $transformer->reverseTransform(0));
        $this->assertEquals(1, $transformer->reverseTransform(1));
    }
}
