<?php

namespace TPN\EmailTemplatesComponent\Element;

use TPN\EmailTemplatesComponent\ArrayConverter;

final class BoldText extends AbstractText
{
    /**
     * @var string
     */
    protected $markup = '<b>%s</b>';

    /**
     * @var string
     */
    protected $name = ArrayConverter::ELEMENT_BOLDTEXT;
}
