<?php

namespace TPN\EmailTemplatesComponent\Command;

use TPN\EmailTemplatesComponent\Model\EmailTemplate;

class TemplateExtender implements CommandInterface
{
    const INCLUDE_EXTENSION = '{%% include "%s" %%}';

    const LAYOUT_EXTENSION = <<<'EOL'
{%% embed '%s' %%}
{%% block style %%}%s{%% endblock style %%}
{%% block header %%}%s{%% endblock header %%}
{%% block content %%}%s{%% endblock content %%}
{%% block footer %%}%s{%% endblock footer %%}
{%% endembed %%}
EOL;

    /**
     * @var string
     */
    protected $baseEmailTemplate = '::email_base.html.twig';

    /**
     * @var string
     */
    private $headerTemplate;

    /**
     * @var string
     */
    private $footerTemplate;

    /**
     * @var string
     */
    private $stylesTemplate;

    /**
     * @param string $baseEmailTemplate
     */
    public function setBaseEmailTemplate($baseEmailTemplate)
    {
        $this->baseEmailTemplate = $baseEmailTemplate;
    }

    /**
     * @param string $stylesTemplate
     */
    public function setStylesTemplate($stylesTemplate)
    {
        $this->stylesTemplate = $stylesTemplate;
    }

    /**
     * @param string $headerTemplate
     */
    public function setHeaderTemplate($headerTemplate)
    {
        $this->headerTemplate = $headerTemplate;
    }

    /**
     * @param string $footerTemplate
     */
    public function setFooterTemplate($footerTemplate)
    {
        $this->footerTemplate = $footerTemplate;
    }

    public function execute(EmailTemplate $template)
    {
        $header = $this->headerTemplate ? sprintf(static::INCLUDE_EXTENSION, $this->headerTemplate) : '';
        $footer = $this->footerTemplate ? sprintf(static::INCLUDE_EXTENSION, $this->footerTemplate) : '';
        $cssStyles = $this->stylesTemplate ? sprintf(static::INCLUDE_EXTENSION, $this->stylesTemplate) : '';

        $template->setBody(sprintf(
            static::LAYOUT_EXTENSION,
            $this->baseEmailTemplate,
            $cssStyles,
            $header,
            $template->getBody(),
            $footer
        ));
    }
}
