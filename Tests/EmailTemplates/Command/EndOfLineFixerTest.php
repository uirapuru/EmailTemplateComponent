<?php

namespace TPN\EmailTemplatesComponent\Tests\EmailTemplates\Command;

use TPN\BaseMainBundle\Tests\UnitTestCase;
use TPN\EmailTemplatesComponent\Command\EndOfLineFixer;
use TPN\EmailTemplatesComponent\Model\EmailTemplate;

/**
 * Class EndOfLineFixerTest.
 */
class EndOfLineFixerTest extends UnitTestCase
{
    /**
     * @param $oldBody
     * @param $newBody
     * @dataProvider testExecuteDataProvider
     */
    public function testExecute($oldBody, $newBody)
    {
        $template = new EmailTemplate();
        $template->setBody($oldBody);

        $command = new EndOfLineFixer();
        $command->execute($template);

        $this->assertEquals($template->getBody(), $newBody);
    }

    /**
     * @return array
     */
    public function testExecuteDataProvider()
    {
        $body1 = <<< 'EOL'
       Welcome, {{ firstname }}!
       {{ request_tickets_button }}
       {{ company_name }}
       {{ request_tickets_button }}
       {{ some_other }}
EOL;

        $body1_2 = <<< 'EOL'
       Welcome, {{ firstname }}!<br />
       {{ request_tickets_button }}<br />
       {{ company_name }}<br />
       {{ request_tickets_button }}<br />
       {{ some_other }}
EOL;

        return [
            [
                'oldBody' => '',
                'newBody' => '',
            ],
            [
                'oldBody' => PHP_EOL,
                'newBody' => '<br />'.PHP_EOL,
            ],
            [
                'oldBody' => $body1,
                'newBody' => $body1_2,
            ],
        ];
    }
}
