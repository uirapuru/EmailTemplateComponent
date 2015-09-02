<?php

namespace TPN\EmailTemplatesComponent;

use TPN\EmailTemplatesComponent\Element\AbstractElement;
use TPN\EmailTemplatesComponent\Element\ElementFactoryInterface;

/**
 * Class ArrayConverter.
 */
final class ArrayConverter implements ArrayConverterInterface
{
    const ELEMENT_WHITEBLOCK = 'whiteBlock';
    const ELEMENT_PARAGRAPH = 'paragraph';
    const ELEMENT_HEADER = 'header';
    const ELEMENT_NORMALTEXT = 'normalText';
    const ELEMENT_BOLDTEXT = 'boldText';
    const ELEMENT_READONLY = 'readOnly';

    /**
     * @var string[]
     */
    public static $elements = [
        self::ELEMENT_WHITEBLOCK => 'TPN\EmailTemplatesComponent\Element\WhiteBlock',
        self::ELEMENT_PARAGRAPH => 'TPN\EmailTemplatesComponent\Element\Paragraph',
        self::ELEMENT_HEADER => 'TPN\EmailTemplatesComponent\Element\Header',
        self::ELEMENT_NORMALTEXT => 'TPN\EmailTemplatesComponent\Element\NormalText',
        self::ELEMENT_BOLDTEXT => 'TPN\EmailTemplatesComponent\Element\BoldText',
        self::ELEMENT_READONLY => 'TPN\EmailTemplatesComponent\Element\ReadOnly',
    ];

    /**
     * @var ElementFactoryInterface
     */
    private $elementFactory;

    /**
     * @param ElementFactoryInterface $elementFactory
     */
    public function __construct(ElementFactoryInterface $elementFactory)
    {
        $this->elementFactory = $elementFactory;
    }

    /**
     * @param $array
     *
     * @throws \Exception
     *
     * @return AbstractElement[]
     */
    public function createFromArray($array)
    {
        if (count($array) === 0) {
            throw new EmptyChildrenException('No elements found in array passed to ArrayConverter::createFromArray()');
        }

        return $this->convert($array);
    }

    /**
     * Recursively build an array of ElementInterface objects from associated array.
     *
     * @param $array
     *
     * @return array
     */
    private function convert($array)
    {
        $result = [];

        foreach ($array as $arrayElement) {
            $element = $this->elementFactory->createFromArray($arrayElement);

            if ($element->isLeaf()) {
                $element->setContent($arrayElement['content']);
            } else {
                $element->setChildren($this->convert($arrayElement['children']));
            }

            $result[] = $element;
        }

        return $result;
    }
}
