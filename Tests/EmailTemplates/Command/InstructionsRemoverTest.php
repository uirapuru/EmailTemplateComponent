<?php

namespace TPN\EmailTemplatesComponent\Tests\EmailTemplates\Command;

use TPN\BaseMainBundle\Tests\UnitTestCase;
use TPN\EmailTemplatesComponent\Command\InstructionsRemover;
use TPN\EmailTemplatesComponent\Model\EmailTemplate;

/**
 * Class InstructionsRemoverTest.
 */
class InstructionsRemoverTest extends UnitTestCase
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

        $command = new InstructionsRemover();
        $command->execute($template);

        $this->assertEquals($template->getBody(), $newBody);
    }

    public function testExecuteDataProvider()
    {
        return [
            [
                'oldBody' => '{{ some tag }}{% some instruction %}some text',
                'newBody' => '{{ some tag }}some text',
            ],
        ];
    }
}
