<?php

namespace TPN\EmailTemplatesComponent\Element;

use TPN\EmailTemplatesComponent\ArrayConverter;

final class Header extends AbstractElement implements ElementInterface
{
    /**
     * @var string
     */
    protected $markup = <<<EOL
<!-- start header element -->
<h1 style="color: #000 !important; line-height: 1.15; margin-bottom: 0.4em; font-weight: 200; font-size: 36px;" class="featured__content__title">
    %s
</h1>
<!-- end header element -->
EOL;

    /**
     * @var string[]
     */
    public static $allowedChildren = [
        ArrayConverter::ELEMENT_NORMALTEXT,
    ];

    /**
     * @var string
     */
    protected $name = ArrayConverter::ELEMENT_HEADER;
}
