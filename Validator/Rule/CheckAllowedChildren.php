<?php

namespace TPN\EmailTemplatesComponent\Validator\Rule;

use TPN\EmailTemplatesComponent\Element\ElementInterface;
use TPN\EmailTemplatesComponent\Validator\ValidationError;

/**
 * Class CheckAllowedChildren.
 */
class CheckAllowedChildren implements RuleInterface
{
    /**
     * @var string
     */
    private $message = "Element '%s' can't have [%s] as child. Only [%s] allowed.";

    /**
     * @param ElementInterface $element
     *
     * @throws ValidationError
     */
    public function validate(ElementInterface $element)
    {
        $children = $element->getChildren();
        $allowed = $element->getAllowedChildren();
        $unallowed = [];

        foreach ($children as $child) {
            if (!in_array($child->getName(), $allowed)) {
                $unallowed[] = $child->getName();
            }
        }

        if (count($unallowed) > 0) {
            throw new ValidationError(sprintf(
                $this->message,
                $element->getName(),
                implode(', ', $unallowed),
                implode(', ', $element->getAllowedChildren())
            ));
        }
    }
}
