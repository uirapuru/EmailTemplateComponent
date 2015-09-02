<?php

namespace TPN\BaseMainBundle\Tests\EmailTemplates;

use TPN\EmailTemplatesComponent\Validator;
use Mockery as m;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidationIsValid()
    {
        $child1 = m::mock('TPN\EmailTemplatesComponent\Element\AbstractElement');
        $child1->shouldReceive('isLeaf')->once()->andReturn(true);
        $child1->shouldReceive('getName')->once()->andReturn('child1');

        $child2 = m::mock('TPN\EmailTemplatesComponent\Element\AbstractElement');
        $child2->shouldReceive('isLeaf')->once()->andReturn(true);
        $child2->shouldReceive('getName')->once()->andReturn('child2');

        $child3 = m::mock('TPN\EmailTemplatesComponent\Element\AbstractElement');
        $child3->shouldReceive('isLeaf')->once()->andReturn(true);
        $child3->shouldReceive('getName')->once()->andReturn('child3');

        $children = [$child1, $child2, $child3];

        $emailTemplate = m::mock('TPN\EmailTemplatesComponent\Model\EmailTemplate');
        $emailTemplate->shouldReceive('getChildren')->once()->andReturn($children);

        $ruleMock = m::mock('TPN\EmailTemplatesComponent\Validator\Rule\RuleInterface');
        $ruleMock->shouldReceive('validate')->times(3);

        $validator = new Validator([$ruleMock]);
        $errorTrace = $validator->validate($emailTemplate);

        $this->assertInstanceOf('TPN\EmailTemplatesComponent\Validator\ErrorTrace', $errorTrace);
        $this->assertFalse($errorTrace->hasErrors());
    }

    public function testValidationNotValid()
    {
        $child1 = m::mock('TPN\EmailTemplatesComponent\Element\AbstractElement');
        $child1->shouldReceive('isLeaf')->once()->andReturn(true);
        $child1->shouldReceive('getName')->once()->andReturn('child1');

        $child2 = m::mock('TPN\EmailTemplatesComponent\Element\AbstractElement');
        $child2->shouldReceive('isLeaf')->once()->andReturn(true);
        $child2->shouldReceive('getName')->once()->andReturn('child2');

        $child3 = m::mock('TPN\EmailTemplatesComponent\Element\AbstractElement');
        $child3->shouldReceive('isLeaf')->once()->andReturn(true);
        $child3->shouldReceive('getName')->once()->andReturn('child3');

        $children = [$child1, $child2, $child3];

        $emailTemplate = m::mock('TPN\EmailTemplatesComponent\Model\EmailTemplate');
        $emailTemplate->shouldReceive('getChildren')->once()->andReturn($children);

        $ruleMock = m::mock('TPN\EmailTemplatesComponent\Validator\Rule\RuleInterface');
        $ruleMock->shouldReceive('validate')->andThrow(new Validator\ValidationError('some fancy error'));

        $validator = new Validator([$ruleMock]);
        $errorTrace = $validator->validate($emailTemplate);

        $this->assertInstanceOf('TPN\EmailTemplatesComponent\Validator\ErrorTrace', $errorTrace);
        $this->assertTrue($errorTrace->hasErrors());
        $this->assertEquals(
            "0[child1] some fancy error\n".
            "1[child2] some fancy error\n".
            '2[child3] some fancy error',
            $errorTrace->dump()
        );
    }
}
