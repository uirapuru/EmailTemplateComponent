<?php

namespace TPN\EmailTemplatesComponent\Element;

use TPN\EmailTemplatesComponent\ArrayConverter;

class Paragraph extends AbstractElement implements ElementInterface
{
    /**
     * @var string
     */
    protected $markup = <<<EOL
<!-- start paragraph element -->
<p style="color: #676c78; font-size: 12px; line-height: 1.5; margin: 1em 0;">
    %s
</p>
<!-- end paragraph element -->
EOL;

    /**
     * @var string[]
     */
    public static $allowedChildren = [
        ArrayConverter::ELEMENT_BOLDTEXT,
        ArrayConverter::ELEMENT_NORMALTEXT,
    ];

    /**
     * @var string
     */
    protected $name = ArrayConverter::ELEMENT_PARAGRAPH;
}
