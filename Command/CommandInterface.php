<?php

namespace TPN\EmailTemplatesComponent\Command;

use TPN\EmailTemplatesComponent\Model\EmailTemplate;

interface CommandInterface
{
    public function execute(EmailTemplate $template);
}
