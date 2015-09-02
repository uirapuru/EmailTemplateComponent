<?php

namespace TPN\BaseMainBundle\Tests\EmailTemplates\Validator\Rule;

use TPN\EmailTemplatesComponent\Validator\Rule\CantBeEmpty;
use Mockery as m;

class CantBeEmptyTest extends \PHPUnit_Framework_TestCase
{
    public function testValidate()
    {
        $leafElement = m::mock('TPN\EmailTemplatesComponent\Element\ElementInterface');
        $leafElement->shouldReceive('isLeaf')->once()->andReturn(true);
        $leafElement->shouldReceive('getContent')->once()->andReturn('some text');

        $rule = new CantBeEmpty();

        $this->assertNull($rule->validate($leafElement));

        $nodeElement = m::mock('TPN\EmailTemplatesComponent\Element\ElementInterface');
        $nodeElement->shouldReceive('isLeaf')->once()->andReturn(false);
        $nodeElement->shouldReceive('getChildren')->once()->andReturn([1, 2, 3]);

        $this->assertNull($rule->validate($nodeElement));
    }

    /**
     * @throws \TPN\EmailTemplatesComponent\Validator\ValidationError
     * @expectedException TPN\EmailTemplatesComponent\Validator\ValidationError
     * @expectedExceptionMessage Element property 'content' can't be empty
     */
    public function testValidateLeafWithException()
    {
        $leafElement = m::mock('TPN\EmailTemplatesComponent\Element\ElementInterface');
        $leafElement->shouldReceive('isLeaf')->once()->andReturn(true);
        $leafElement->shouldReceive('getContent')->once()->andReturnNull();

        $rule = new CantBeEmpty();

        $this->assertNull($rule->validate($leafElement));
    }

    /**
     * @throws \TPN\EmailTemplatesComponent\Validator\ValidationError
     * @expectedException TPN\EmailTemplatesComponent\Validator\ValidationError
     * @expectedExceptionMessage Element property 'children[]' can't be empty
     */
    public function testValidateNodeWithException()
    {
        $nodeElement = m::mock('TPN\EmailTemplatesComponent\Element\ElementInterface');
        $nodeElement->shouldReceive('isLeaf')->once()->andReturn(false);
        $nodeElement->shouldReceive('getChildren')->once()->andReturn([]);

        $rule = new CantBeEmpty();

        $this->assertNull($rule->validate($nodeElement));
    }

    public function tearDown()
    {
        m::close();
    }
}
