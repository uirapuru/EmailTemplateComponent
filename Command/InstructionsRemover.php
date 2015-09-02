<?php

namespace TPN\EmailTemplatesComponent\Command;

use TPN\EmailTemplatesComponent\Model\EmailTemplate;

class InstructionsRemover implements CommandInterface
{
    public function execute(EmailTemplate $template)
    {
        $body = $template->getBody();
        $regex = '/(?<instruction>{%.*?%})/uis';
        preg_match_all($regex, $body, $matches);

        $foundInstructions = $matches['instruction'];

        foreach ($foundInstructions as $instruction) {
            // the only instructions we don't "escape" is "block" cause we need it :)
            if (strpos($instruction, 'block') || strpos($instruction, 'endblock')) {
                continue;
            }
            $search = "/$instruction/uis";
            $body = preg_replace($search, '', $body, 1);
        }

        $template->setBody($body);
    }
}
