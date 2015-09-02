<?php

namespace TPN\BaseMainBundle\Tests\EmailTemplates;

use TPN\EmailTemplatesComponent\ArrayConverter;
use Mockery as m;
use TPN\EmailTemplatesComponent\Element\NormalText;
use TPN\EmailTemplatesComponent\Element\Paragraph;

class ArrayConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @throws \Exception
     * @expectedException \Exception
     * @expectedExceptionMessage No elements found
     */
    public function testCreateFromEmptyArray()
    {
        $elementFactoryMock = m::mock('TPN\EmailTemplatesComponent\Element\ElementFactoryInterface');

        $converter = new ArrayConverter($elementFactoryMock);
        $converter->createFromArray([]);
    }

    public function testCreateFromArray()
    {
        $data1 = ['type' => 'normalText', 'content' => 'someText'];
        $data2 = ['type' => 'paragraph', 'children' => []];

        $element1Mock = m::mock('TPN\EmailTemplatesComponent\Element\ElementInterface');
        $element1Mock->shouldReceive('isLeaf')->once()->andReturn(true);
        $element1Mock->shouldReceive('setContent')->once();

        $element2Mock = m::mock('TPN\EmailTemplatesComponent\Element\ElementInterface');
        $element2Mock->shouldReceive('isLeaf')->once()->andReturn(false);
        $element2Mock->shouldReceive('setChildren')->once();

        $elementFactoryMock = m::mock('TPN\EmailTemplatesComponent\Element\ElementFactoryInterface');
        $elementFactoryMock->shouldReceive('createFromArray')->with($data1)->once()->andReturn($element1Mock);
        $elementFactoryMock->shouldReceive('createFromArray')->with($data2)->once()->andReturn($element2Mock);

        $converter = new ArrayConverter($elementFactoryMock);
        $result = $converter->createFromArray([$data1, $data2]);

        $this->assertCount(2, $result);
        $this->assertInstanceOf('TPN\EmailTemplatesComponent\Element\ElementInterface', $result[0]);
        $this->assertInstanceOf('TPN\EmailTemplatesComponent\Element\ElementInterface', $result[1]);
    }

    public function tearDown()
    {
        m::close();
    }
}
