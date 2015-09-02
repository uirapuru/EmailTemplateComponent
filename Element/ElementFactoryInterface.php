<?php

namespace TPN\EmailTemplatesComponent\Element;

/**
 * Interface ElementFactoryInterface.
 */
interface ElementFactoryInterface
{
    /**
     * @param $array
     *
     * @return ElementInterface
     */
    public function createFromArray($array);
}
