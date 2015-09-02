<?php

namespace TPN\EmailTemplatesComponent\Element;

use TPN\EmailTemplatesComponent\ArrayConverter;
use TPN\EmailTemplatesComponent\ElementTypeNotSupportedException;

final class ElementFactory implements ElementFactoryInterface
{
    /**
     * @param $array
     *
     * @return ElementInterface
     */
    public function createFromArray($array)
    {
        $availableElements = ArrayConverter::$elements;
        $type = $array['type'];

        if (!array_key_exists($type, $availableElements)) {
            throw new ElementTypeNotSupportedException(
                sprintf(
                    'Element type \'%s\' is not supported, only [%s] supported',
                    $type,
                    implode(', ', array_keys($availableElements))
                )
            );
        }

        $element = new $availableElements[$type]();

        if(array_key_exists('contentId', $array) && method_exists($element, "setContentId")) {
            $element->setContentId($array['contentId']);
        }

        return $element;
    }
}
