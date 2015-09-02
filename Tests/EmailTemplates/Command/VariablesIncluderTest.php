<?php

namespace TPN\EmailTemplatesComponent\Tests\EmailTemplates\Command;

use TPN\BaseMainBundle\Tests\UnitTestCase;
use TPN\EmailTemplatesComponent\Command\VariablesIncluder;
use TPN\EmailTemplatesComponent\Model\EmailTemplate;

/**
 * Class VariablesIncluderTest.
 */
class VariablesIncluderTest extends UnitTestCase
{
    /**
     * @param $oldBody
     * @param $newBody
     * @dataProvider testRawVariablesDataProvider
     */
    public function testExecuteOnBody($oldBody, $newBody, $rawVariables)
    {
        $template = new EmailTemplate();
        $template->setBody($oldBody);

        $command = new VariablesIncluder();
        $command->setTemplateVariables($rawVariables);
        $command->execute($template);

        $this->assertEquals($template->getBody(), $newBody);
    }

    /**
     * @param $oldSubject
     * @param $newSubject
     * @dataProvider testSubjectVariablesDataProvider
     */
    public function testExecuteOnSubject($oldSubject, $newSubject, $rawVariables)
    {
        $template = new EmailTemplate();
        $template->setSubject($oldSubject);

        $command = new VariablesIncluder();
        $command->setTemplateVariables($rawVariables);
        $command->execute($template);

        $this->assertEquals($template->getSubject(), $newSubject);
    }

    public function testRawVariablesDataProvider()
    {
        $body1 = <<< 'EOL'
           Welcome, {{ firstname }}!
           {{ request_tickets_button }}
           {{ company_name }}
           {{ request_tickets_button }}
           {{ some_other }}
EOL;

        $body1_2 = <<< 'EOL'
           Welcome, {{ firstname }}!
           {% include '::requestTicketsButton.html.twig' %}
           {{ company_name }}
           {% include '::requestTicketsButton.html.twig' %}
           {% include '::some_other.html.twig' %}
EOL;

        return [
            'simple-convert' => [
                'oldBody' => '{{ convert_to_raw }}',
                'newBody' => "{% include '::convert_to_raw.html.twig' %}",
                'rawVariables' => [
                    'convert_to_raw' => '::convert_to_raw.html.twig',
                ],
            ],
            'multiline-convert' => [
                'oldBody' => $body1,
                'newBody' => $body1_2,
                'rawVariables' => [
                    'request_tickets_button' => '::requestTicketsButton.html.twig',
                    'some_other' => '::some_other.html.twig',
                ],
            ],
            'dont-convert' => [
                'oldBody' => '{{ convert_to_raw|raw }}',
                'newBody' => '{{ convert_to_raw|raw }}',
                'rawVariables' => [
                    'nothing' => '::nothing.html.twig',
                ],
            ],
            'dont-convert-2' => [
                'oldBody' => '{{ convert_to_raw }}',
                'newBody' => '{{ convert_to_raw }}',
                'rawVariables' => [
                    'nothing' => '::nothing.html.twig',
                ],
            ],
            'dont-convert-3' => [
                'oldBody' => '{{ convert_to_raw|raw }}',
                'newBody' => '{{ convert_to_raw|raw }}',
                'rawVariables' => [
                    'convert_to_raw' => '::convert_to_raw.html.twig',
                    'convert_to_raw|raw' => '::convert_to_raw.html.twig',
                ],
            ],
        ];
    }

    public function testSubjectVariablesDataProvider()
    {
        return [
            'no-variables' => [
                'oldSubject' => 'some old subject',
                'newSubject' => 'some old subject',
                'rawVariables' => [
                    'applicationName' => 'some name'
                ]
            ],
            'simple-variable' => [
                'oldSubject' => 'some {{ applicationName }} name',
                'newSubject' => 'some {% include \'::getstreetteam\' %} name',
                'rawVariables' => [
                    'applicationName' => '::getstreetteam'
                ]
            ],
            'do-not-convert' => [
                'oldSubject' => 'some {{ applicationName }} name',
                'newSubject' => 'some {{ applicationName }} name',
                'rawVariables' => [
                    'someOtherApplicationName' => '::getstreetteam'
                ]
            ]
        ];
    }

}
