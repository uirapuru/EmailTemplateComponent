<?php

namespace TPN\EmailTemplatesComponent\Element;

use TPN\EmailTemplatesComponent\ArrayConverter;

final class ReadOnly extends AbstractElement implements ElementInterface
{
    /**
     * @var string
     */
    private $contentId;

    /**
     * @var string
     */
    protected $markup = <<<EOL
<!-- start readonly element -->
                        %s
<!-- end readonly element -->
EOL;

    /**
     * @var string[]
     */
    public static $allowedChildren = [
        ArrayConverter::ELEMENT_PARAGRAPH,
        ArrayConverter::ELEMENT_HEADER,
    ];

    /**
     * @var string
     */
    protected $name = ArrayConverter::ELEMENT_READONLY;

    /**
     * @param string $contentId
     */
    public function setContentId($contentId)
    {
        $this->contentId = $contentId;
    }

    /**
     * @return string
     */
    public function getContentId()
    {
        return $this->contentId;
    }
}
