<?php

namespace TPN\EmailTemplatesComponent;

use TPN\EmailTemplatesComponent\Model\EmailTemplate;

/**
 * Interface ValidatorInterface.
 */
interface ValidatorInterface
{
    /**
     * @param EmailTemplate $template
     *
     * @return mixed
     */
    public function validate(EmailTemplate $template);
}
