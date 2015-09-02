<?php

namespace TPN\EmailTemplatesComponent;

use TPN\EmailTemplatesComponent\Element\AbstractElement;

interface ArrayConverterInterface
{
    /**
     * @param $array
     *
     * @return AbstractElement[]
     */
    public function createFromArray($array);
}
