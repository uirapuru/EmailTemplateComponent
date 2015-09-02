<?php

namespace TPN\BaseMainBundle\Tests\EmailTemplates\Elements;

use TPN\EmailTemplatesComponent\ArrayConverter;
use TPN\EmailTemplatesComponent\Element\ElementFactory;
use TPN\EmailTemplatesComponent\Element\ElementInterface;
use TPN\EmailTemplatesComponent\ElementTypeNotSupportedException;

class ElementFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array            $array
     * @param ElementInterface $elementClass
     * @dataProvider testCreateFromArrayDataProvider
     */
    public function testCreateFromArray($array, $elementClass)
    {
        $factory = new ElementFactory();
        $element = $factory->createFromArray($array);

        $this->assertInstanceOf($elementClass, $element);
    }

    /**
     * @expectedException TPN\EmailTemplatesComponent\ElementTypeNotSupportedException
     * @expectedExceptionMessage Element type 'unsupported' is not supported, only [whiteBlock, paragraph, header, normalText, boldText, readOnly] supported
     */
    public function testCreateFromArrayException()
    {
        $factory = new ElementFactory();
        $factory->createFromArray(['type' => 'unsupported']);
    }

    public function testCreateFromArrayDataProvider()
    {
        return [
            'whiteBlock' => [
                'array' => [
                    'type' => 'whiteBlock',
                ],
                'elementClass' => ArrayConverter::$elements['whiteBlock'],
            ],
            'paragraph' => [
                'array' => [
                    'type' => 'paragraph',
                ],
                'elementClass' => ArrayConverter::$elements['paragraph'],
            ],
            'header' => [
                'array' => [
                    'type' => 'header',
                ],
                'elementClass' => ArrayConverter::$elements['header'],
            ],
            'normalText' => [
                'array' => [
                    'type' => 'normalText',
                ],
                'elementClass' => ArrayConverter::$elements['normalText'],
            ],
            'boldText' => [
                'array' => [
                    'type' => 'boldText',
                ],
                'elementClass' => ArrayConverter::$elements['boldText'],
            ],
            'readOnly' => [
                'array' => [
                    'type' => 'readOnly',
                ],
                'elementClass' => ArrayConverter::$elements['readOnly'],
            ],
        ];
    }
}
