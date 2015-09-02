<?php

namespace TPN\EmailTemplatesComponent\Tests\EmailTemplates\Command;

use TPN\BaseMainBundle\Tests\UnitTestCase;
use TPN\EmailTemplatesComponent\Command\NotAllowedTagsRemover;
use TPN\EmailTemplatesComponent\Model\EmailTemplate;

/**
 * Class NotAllowedTagsRemoverTest.
 */
class NotAllowedTagsRemoverTest extends UnitTestCase
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
        $template->setTags(['allowed tag']);

        $command = new NotAllowedTagsRemover();
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
       Welcome, firstname!
       request_tickets_button
       company_name
       request_tickets_button
       some_other
EOL;

        return [
            [
                'oldBody' => $body1,
                'newBody' => $body1_2,
            ],
            [
                'oldBody' => '{{ not allowed }}{{ allowed tag }}{{ not allowed }}',
                'newBody' => 'not allowed{{ allowed tag }}not allowed',
            ],
        ];
    }
}
