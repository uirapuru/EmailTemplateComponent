<?php

namespace TPN\EmailTemplatesComponent\Command;

use TPN\EmailTemplatesComponent\Model\EmailTemplate;

class VariablesIncluder implements CommandInterface
{
    /**
     * @var array
     */
    private $templateVariables = [];

    /**
     * @param array $templateVariables
     */
    public function setTemplateVariables($templateVariables)
    {
        $this->templateVariables = $templateVariables;
    }

    /**
     * @param EmailTemplate $template
     */
    public function execute(EmailTemplate $template)
    {
        $template->setSubject($this->process($template->getSubject()));
        $template->setBody($this->process($template->getBody()));
    }

    /**
     * @param string $string
     * @return string
     */
    private function process($string)
    {
        $regex = "/{{\s*(?<variable>(?!\|raw)[a-z0-9\_\-]*?)\s*}}/ui";
        preg_match_all($regex, $string, $matches);

        $foundVariables = $matches['variable'];
        $templateVariables = array_keys($this->templateVariables);

        foreach ($foundVariables as $variable) {
            if (in_array($variable, $templateVariables)) {
                $search = "/{{\s*(".$variable.")\s*}}/ui";
                $replace = "{% include '".$this->templateVariables[$variable]."' %}";
                $string = preg_replace($search, $replace, $string, 1);
            }
        }

        return $string;
    }
}
