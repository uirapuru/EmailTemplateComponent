<?php

namespace TPN\EmailTemplatesComponent\Element;

abstract class AbstractText extends AbstractElement implements ElementInterface, TextInterface
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @param string $content
     * @param string $markup
     *
     * @throws ImroperDataPassedToConstructorException
     */
    public function __construct($content = '', $markup = null)
    {
        if (!is_string($content)) {
            throw new ImroperDataPassedToConstructorException('Only string is allowed as argument passed to AbstractText object');
        }

        if (!is_null($markup)) {
            $this->markup = $markup;
        }

        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function dump()
    {
        return sprintf(
            $this->markup,
            $this->getContent()
        );
    }

    /**
     * @return bool
     */
    public function isLeaf()
    {
        return true;
    }

    /**
     * @param $children
     *
     * @throws \BadMethodCallException
     */
    public function setChildren($children)
    {
        throw new \BadMethodCallException('Text Element could only have text as content!');
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return [];
    }
}
