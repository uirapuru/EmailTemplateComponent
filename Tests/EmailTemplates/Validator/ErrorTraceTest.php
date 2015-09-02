<?php

namespace TPN\BaseMainBundle\Tests\EmailTemplates\Validator\Rule;

use Mockery as m;
use TPN\EmailTemplatesComponent\Validator\ErrorTrace;

class ErrorTraceTest extends \PHPUnit_Framework_TestCase
{
    public function testDump()
    {
        $trace = new ErrorTrace();
        $trace->addMessage('path', 'message1');
        $trace->addMessage('path', 'message2');
        $trace->addMessage('path2', 'message');
        $trace->addMessage('path3', 'message');

        $this->assertEquals("path message1\nmessage2\n".
            "path2 message\n".
            'path3 message',
        $trace->dump());
    }

    public function tearDown()
    {
        m::close();
    }
}
