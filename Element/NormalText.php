<?php

namespace TPN\EmailTemplatesComponent\Element;

use TPN\EmailTemplatesComponent\ArrayConverter;

final class NormalText extends AbstractText
{
    /**
     * @var string
     */
    protected $name = ArrayConverter::ELEMENT_NORMALTEXT;

    /**
     * {@inheritdoc}
     */
    public function dump()
    {
        $text = parent::dump();

        return nl2br($text);
    }
}
