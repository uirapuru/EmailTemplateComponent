<?php

namespace TPN\EmailTemplatesComponent\Element;

interface TextInterface
{
    /**
     * @param string $string
     */
    public function __construct($string);

    /**
     * @return string
     */
    public function getContent();
}
