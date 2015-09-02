<?php

namespace TPN\BaseMainBundle\Tests\Lib;

use TPN\BaseMainBundle\Tests\UnitTestCase;
use TPN\EmailTemplatesComponent\Command\BlockEmbedder;
use TPN\EmailTemplatesComponent\Command\EndOfLineFixer;
use TPN\EmailTemplatesComponent\Command\InstructionsRemover;
use TPN\EmailTemplatesComponent\Command\NotAllowedTagsRemover;
use TPN\EmailTemplatesComponent\Command\VariablesIncluder;
use TPN\EmailTemplatesComponent\Command\TemplateExtender;
use TPN\EmailTemplatesComponent\Enricher;
use TPN\EmailTemplatesComponent\Model\EmailTemplate;

class EnricherTest extends UnitTestCase
{
    public function testProcessTemplate()
    {
        $body = <<< 'EOL'
Welcome to Street Team, {{firstname}}!
{% block default %}
    emphasized block
{% endblock default %}

block normal

{% block default %}
    another emphasized block
{% endblock default %}

{{request_tickets_button}}

{{application_name}}
EOL;

        $expected = <<< 'EOL'
{% embed '::email_base.html.twig' %}
{% block style %}{% endblock style %}
{% block header %}{% endblock header %}
{% block content %}Welcome to Street Team, {{firstname}}!<br />
{% embed "::EmailBlocks/default.html.twig" %}{% block default %}<br />
    emphasized block<br />
{% endblock default %}{% endembed %}<br />
<br />
block normal<br />
<br />
{% embed "::EmailBlocks/default.html.twig" %}{% block default %}<br />
    another emphasized block<br />
{% endblock default %}{% endembed %}<br />
<br />
{{request_tickets_button}}<br />
<br />
{{application_name}}{% endblock content %}
{% block footer %}{% endblock footer %}
{% endembed %}
EOL;

        $template = new EmailTemplate();
        $template->setBody($body);
        $template->setTags([
            'request_tickets_button',
            'firstname',
            'application_name',
        ]);
        $template->setBlocks([
            'default',
        ]);

        $enricher = $this->getEnricher();
        $enricher->processTemplate($template);

        $this->assertEquals($expected, $template->getBody());
    }

    private function getEnricher()
    {
        $enricher = new Enricher();
        $enricher->addCommand(new EndOfLineFixer(), 1);
        $enricher->addCommand(new InstructionsRemover(), 2);
        $enricher->addCommand(new NotAllowedTagsRemover(), 3);
        $enricher->addCommand(new BlockEmbedder(), 4);
        $enricher->addCommand(new VariablesIncluder(), 5);
        $enricher->addCommand(new TemplateExtender(), 6);

        return $enricher;
    }
}
