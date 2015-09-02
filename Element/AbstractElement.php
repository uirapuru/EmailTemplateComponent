<?php

namespace TPN\EmailTemplatesComponent\Element;

abstract class AbstractElement implements ElementInterface
{
    /**
     * @var ElementInterface[]
     */
    protected $children = [];

    /**
     * @var string
     */
    protected $markup = '%s';

    /**
     * @var string[]
     */
    public static $allowedChildren = [];

    /**
     * @var string
     */
    protected $name = 'abstract';

    /**
     * @param ElementInterface[] $children
     * @param string             $markup
     */
    public function __construct($children = [], $markup = null)
    {
        $this->children = $children;

        if (!is_null($markup)) {
            $this->markup = $markup;
        }
    }

    /**
     * @return ElementInterface[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @var ElementInterface[]
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return string
     */
    public function dump()
    {
        $result = [];

        foreach ($this->children as $child) {
            $result[] = $child->dump();
        }

        return sprintf($this->markup, implode('', $result));
    }

    /**
     * @return bool
     */
    public function isLeaf()
    {
        return false;
    }

    /**
     * @return \string[]
     */
    public function getAllowedChildren()
    {
        return static::$allowedChildren;
    }

    /**
     * @param string|ElementInterface $elementName
     *
     * @return bool
     */
    public function isAllowed($elementName)
    {
        if ($elementName instanceof ElementInterface) {
            $elementName = $elementName->getName();
        }

        return in_array($elementName, static::$allowedChildren);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
