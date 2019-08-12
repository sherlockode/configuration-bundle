<?php

namespace Sherlockode\ConfigurationBundle\Tests\Transformer;

use PHPUnit\Framework\TestCase;
use Sherlockode\ConfigurationBundle\Transformer\ArrayTransformer;

class ArrayTransformerTest extends TestCase
{
    public function testTransform()
    {
        $data = [
            'entry1' => [
                'entry1-1'  => 'foo',
                'entry1-2'  => 'bar',
            ],
            'entry2' => 'baz',
        ];

        $transformer = new ArrayTransformer();
        $this->assertEquals(null, $transformer->transform(null));
        $this->assertEquals(null, $transformer->transform('invalid serialized'));
        $this->assertEquals($data, $transformer->transform(serialize($data)));
    }

    public function testReverseTransform()
    {
        $data = [
            'entry1' => [
                'entry1-1'  => 'foo',
                'entry1-2'  => 'bar',
            ],
            'entry2' => 'baz',
        ];

        $transformer = new ArrayTransformer();
        $this->assertEquals(null, $transformer->reverseTransform(null));
        $this->assertEquals(serialize($data), $transformer->reverseTransform($data));
    }
}
