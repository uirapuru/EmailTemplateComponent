<?php

namespace TPN\EmailTemplatesComponent\Command;

use TPN\EmailTemplatesComponent\Model\EmailTemplate;

class EndOfLineFixer implements CommandInterface
{
    public function execute(EmailTemplate $template)
    {
        $body = $template->getBody();
        $body = nl2br($body);
        $template->setBody($body);
    }
}
