<?php

namespace TPN\EmailTemplatesComponent\Validator\Rule;

use TPN\EmailTemplatesComponent\Element\ElementInterface;

/**
 * Interface RuleInterface.
 *
 * @property string $message Keeps error message for rule
 */
interface RuleInterface
{
    /**
     * @param ElementInterface $element
     */
    public function validate(ElementInterface $element);
}
