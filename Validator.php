<?php

namespace TPN\EmailTemplatesComponent;

use TPN\EmailTemplatesComponent\Element\ElementInterface;
use TPN\EmailTemplatesComponent\Model\EmailTemplate;
use TPN\EmailTemplatesComponent\Validator\ErrorTrace;
use TPN\EmailTemplatesComponent\Validator\Rule\RuleInterface;
use TPN\EmailTemplatesComponent\Validator\ValidationError;

/**
 * Class Validator.
 */
final class Validator implements ValidatorInterface
{
    /**
     * @var ErrorTrace
     */
    private $errorTrace;

    /**
     * @var RuleInterface[]
     */
    private $rules = [];

    /**
     * @param RuleInterface[] $rules
     */
    public function __construct($rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param EmailTemplate $template
     *
     * @return ErrorTrace
     */
    public function validate(EmailTemplate $template)
    {
        $this->errorTrace = new ErrorTrace();

        $children = $template->getChildren();

        $this->check($children);

        return $this->errorTrace;
    }

    /**
     * @param ElementInterface[] $children
     *
     * @return bool
     *
     * @throws \Exception
     */
    private function check($children, $parentPath = '')
    {
        foreach ($children as $key => $element) {
            $elementPath = sprintf('%s%d[%s]', $parentPath, $key, $element->getName());

            foreach ($this->rules as $rule) {
                try {
                    $rule->validate($element);
                } catch (ValidationError $error) {
                    $this->errorTrace->addMessage($elementPath, $error->getMessage());
                }
            }

            if (!$element->isLeaf()) {
                $this->check($element->getChildren(), $elementPath);
            }
        }
    }
}
