<?php

namespace TPN\EmailTemplatesComponent\Command;

use TPN\EmailTemplatesComponent\Model\EmailTemplate;

class NotAllowedTagsRemover implements CommandInterface
{
    public function execute(EmailTemplate $template)
    {
        $body = $template->getBody();
        $regex = "/{{\s*(?<variable>.*?)\s*}}/uis";
        preg_match_all($regex, $body, $matches);

        $foundVariables = $matches['variable'];
        $availableTags = $template->getTags();

        foreach ($foundVariables as $variable) {
            if (!in_array($variable, $availableTags)) {
                $search = "/({{\s*".preg_quote($variable)."\s*}})/ui";
                $body = preg_replace($search, $variable, $body, 1);
            }
        }

        $template->setBody($body);
    }
}
