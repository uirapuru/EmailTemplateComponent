<?php

namespace TPN\BaseMainBundle\Tests\EmailTemplates\Validator\Rule;

use Mockery as m;
use TPN\EmailTemplatesComponent\Validator\Rule\CheckAllowedChildren;

class CheckAllowedChildrenTest extends \PHPUnit_Framework_TestCase
{
    public function testValidate()
    {
        $rule = new CheckAllowedChildren();

        $leafElement1 = m::mock('TPN\EmailTemplatesComponent\Element\ElementInterface');
        $leafElement1->shouldReceive('getName')->once()->andReturn('aaa');

        $leafElement2 = m::mock('TPN\EmailTemplatesComponent\Element\ElementInterface');
        $leafElement2->shouldReceive('getName')->once()->andReturn('bbb');

        $nodeElement = m::mock('TPN\EmailTemplatesComponent\Element\ElementInterface');
        $nodeElement->shouldReceive('getChildren')->once()->andReturn([$leafElement1, $leafElement2]);
        $nodeElement->shouldReceive('getAllowedChildren')->once()->andReturn(['aaa', 'bbb']);

        $this->assertNull($rule->validate($nodeElement));
    }

    /**
     * @throws \TPN\EmailTemplatesComponent\Validator\ValidationError
     * @expectedException TPN\EmailTemplatesComponent\Validator\ValidationError
     * @expectedExceptionMessage Element 'main' can't have [ccc] as child. Only [aaa, bbb] allowed.
     */
    public function testValidateWithException()
    {
        $rule = new CheckAllowedChildren();

        $leafElement1 = m::mock('TPN\EmailTemplatesComponent\Element\ElementInterface');
        $leafElement1->shouldReceive('getName')->once()->andReturn('aaa');

        $leafElement2 = m::mock('TPN\EmailTemplatesComponent\Element\ElementInterface');
        $leafElement2->shouldReceive('getName')->twice()->andReturn('ccc');

        $nodeElement = m::mock('TPN\EmailTemplatesComponent\Element\ElementInterface');
        $nodeElement->shouldReceive('getName')->once()->andReturn('main');
        $nodeElement->shouldReceive('getChildren')->once()->andReturn([$leafElement1, $leafElement2]);
        $nodeElement->shouldReceive('getAllowedChildren')->twice()->andReturn(['aaa', 'bbb']);

        $this->assertNull($rule->validate($nodeElement));
    }

    public function tearDown()
    {
        m::close();
    }
}
