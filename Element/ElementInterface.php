<?php

namespace TPN\EmailTemplatesComponent\Element;

/**
 * Interface ElementInterface.
 *
 * @property $allowedChildren
 */
interface ElementInterface
{
    /**
     * @param ElementInterface[]|string $children
     */
    public function __construct($children);

    /**
     * @return ElementInterface[]
     */
    public function getChildren();

    /**
     * @return mixed
     */
    public function dump();

    /**
     * @return bool
     */
    public function isLeaf();

    /**
     * @return array
     */
    public function getAllowedChildren();

    /**
     * @return string
     */
    public function getName();
}
