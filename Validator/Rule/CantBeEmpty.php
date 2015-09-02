<?php

namespace TPN\EmailTemplatesComponent\Validator\Rule;

use TPN\EmailTemplatesComponent\Element\ElementInterface;
use TPN\EmailTemplatesComponent\Validator\ValidationError;

/**
 * Class CantBeEmpty.
 */
final class CantBeEmpty implements RuleInterface
{
    /**
     * @var string
     */
    private $message = "Element property '%s' can't be empty";

    /**
     * @param ElementInterface $element
     *
     * @throws ValidationError
     */
    public function validate(ElementInterface $element)
    {
        if ($element->isLeaf()) {
            if (strlen($element->getContent()) === 0) {
                throw new ValidationError(sprintf($this->message, 'content'));
            }
        } else {
            if (count($element->getChildren()) === 0) {
                throw new ValidationError(sprintf($this->message, 'children[]'));
            }
        }
    }
}
