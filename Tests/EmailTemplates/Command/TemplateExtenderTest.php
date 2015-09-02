<?php

namespace TPN\EmailTemplatesComponent\Tests\EmailTemplates\Command;

use TPN\BaseMainBundle\Tests\UnitTestCase;
use TPN\EmailTemplatesComponent\Command\TemplateExtender;
use TPN\EmailTemplatesComponent\Model\EmailTemplate;

/**
 * Class TemplateExtenderTest.
 */
class TemplateExtenderTest extends UnitTestCase
{
    public function testExecute()
    {
        $templateName = '::someTestTemplate.html.twig';

        $oldBody = 'Hi! This message is for you, {{ firstname }} {{ lastname }} how about {% embed "::EmailBlocks/miscBlock.html.twig" %}{% block miscBlock %}some misc info{% endblock miscBlock %}{% endembed %}';

        $newBody = "{% embed '".$templateName."' %}
{% block style %}{% include \"::test_styles.html.twig\" %}{% endblock style %}
{% block header %}{% include \"::test_header.html.twig\" %}{% endblock header %}
{% block content %}".$oldBody.'{% endblock content %}
{% block footer %}{% include "::test_footer.html.twig" %}{% endblock footer %}
{% endembed %}';

        $command = new TemplateExtender();
        $command->setBaseEmailTemplate($templateName);
        $command->setHeaderTemplate('::test_header.html.twig');
        $command->setFooterTemplate('::test_footer.html.twig');
        $command->setStylesTemplate('::test_styles.html.twig');

        $template = new EmailTemplate();
        $template->setBody($oldBody);

        $command->execute($template);

        $this->assertEquals($newBody, $template->getBody());
    }
}
