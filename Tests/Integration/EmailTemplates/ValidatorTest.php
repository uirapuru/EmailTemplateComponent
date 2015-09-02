<?php

namespace TPN\BaseMainBundle\Tests\Integration\EmailTemplates;

use TPN\EmailTemplatesComponent\ArrayConverter;
use TPN\EmailTemplatesComponent\Element\ElementFactory;
use TPN\EmailTemplatesComponent\Element\NormalText;
use TPN\EmailTemplatesComponent\Element\Paragraph;
use TPN\EmailTemplatesComponent\Element\WhiteBlock;
use TPN\EmailTemplatesComponent\Validator;
use TPN\EmailTemplatesComponent\Model\EmailTemplate;

/**
 * Class ValidatorTest.
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testParsedJson()
    {
        $fileContents = json_decode(file_get_contents(__DIR__.'/../../../Tests/Resources/email_template.json'), true);
        $arrayConverter = new ArrayConverter(new ElementFactory());

        $result = $arrayConverter->createFromArray($fileContents);

        $emailTemplate = new EmailTemplate();
        $emailTemplate->setChildren($result);

        $validator = new Validator([
            new Validator\Rule\CantBeEmpty(),
            new Validator\Rule\CheckAllowedChildren(),
        ]);

        $this->assertFalse($validator->validate($emailTemplate)->hasErrors());
    }

    public function testValidationSingleNotValid()
    {
        $emailTemplate = new EmailTemplate();
        $emailTemplate->setChildren([
            new WhiteBlock([]),
        ]);

        $validator = new Validator([
            new Validator\Rule\CantBeEmpty(),
        ]);

        $this->assertTrue($validator->validate($emailTemplate)->hasErrors());
        $this->assertEquals("0[whiteBlock] Element property 'children[]' can't be empty", $validator->validate($emailTemplate)->dump());
    }

    public function testValidationTwiceNotValid()
    {
        $emailTemplate = new EmailTemplate();
        $emailTemplate->setChildren([
            new WhiteBlock([]),
            new WhiteBlock([]),
        ]);

        $validator = new Validator([
            new Validator\Rule\CantBeEmpty(),
        ]);

        $this->assertTrue($validator->validate($emailTemplate)->hasErrors());
        $this->assertEquals(
            "0[whiteBlock] Element property 'children[]' can't be empty\n".
            "1[whiteBlock] Element property 'children[]' can't be empty",
            $validator->validate($emailTemplate)->dump()
        );
    }

    public function testValidationOneLevelNestedNotValid()
    {
        $emailTemplate = new EmailTemplate();
        $emailTemplate->setChildren([
            new WhiteBlock([new NormalText()]),
            new WhiteBlock([new NormalText()]),
        ]);

        $validator = new Validator([
            new Validator\Rule\CantBeEmpty(),
            new Validator\Rule\CheckAllowedChildren(),
        ]);

        $this->assertTrue($validator->validate($emailTemplate)->hasErrors());
        $this->assertEquals(
            "0[whiteBlock] Element 'whiteBlock' can't have [normalText] as child. Only [paragraph, header, readOnly] allowed.\n".
            "0[whiteBlock]0[normalText] Element property 'content' can't be empty\n".
            "1[whiteBlock] Element 'whiteBlock' can't have [normalText] as child. Only [paragraph, header, readOnly] allowed.\n".
            "1[whiteBlock]0[normalText] Element property 'content' can't be empty",
            $validator->validate($emailTemplate)->dump());
    }

    public function testValidationFiveDimensionsNotValid()
    {
        $emailTemplate = new EmailTemplate();
        $emailTemplate->setChildren([
            new WhiteBlock([
                    new WhiteBlock([
                        new WhiteBlock([
                            new WhiteBlock([
                                new WhiteBlock([]),
                            ]),
                        ]),
                    ]),
                ]),
        ]);

        $validator = new Validator([
            new Validator\Rule\CantBeEmpty(),
            new Validator\Rule\CheckAllowedChildren(),
        ]);

        $this->assertTrue($validator->validate($emailTemplate)->hasErrors());
        $this->assertEquals(
            "0[whiteBlock] Element 'whiteBlock' can't have [whiteBlock] as child. Only [paragraph, header, readOnly] allowed.\n".
            "0[whiteBlock]0[whiteBlock] Element 'whiteBlock' can't have [whiteBlock] as child. Only [paragraph, header, readOnly] allowed.\n".
            "0[whiteBlock]0[whiteBlock]0[whiteBlock] Element 'whiteBlock' can't have [whiteBlock] as child. Only [paragraph, header, readOnly] allowed.\n".
            "0[whiteBlock]0[whiteBlock]0[whiteBlock]0[whiteBlock] Element 'whiteBlock' can't have [whiteBlock] as child. Only [paragraph, header, readOnly] allowed.\n".
            "0[whiteBlock]0[whiteBlock]0[whiteBlock]0[whiteBlock]0[whiteBlock] Element property 'children[]' can't be empty",
            $validator->validate($emailTemplate)->dump()
        );
    }

    public function testValidationMultiDimensionalNotValid()
    {
        $emailTemplate = new EmailTemplate();
        $emailTemplate->setChildren([
            new WhiteBlock([new Paragraph()]),
            new WhiteBlock([new WhiteBlock(), new WhiteBlock([new WhiteBlock([])])]),
            new WhiteBlock([new NormalText()]),
            new WhiteBlock([]),
        ]);

        $validator = new Validator([
            new Validator\Rule\CantBeEmpty(),
            new Validator\Rule\CheckAllowedChildren(),
        ]);

        $this->assertTrue($validator->validate($emailTemplate)->hasErrors());
        $this->assertEquals(
            "0[whiteBlock]0[paragraph] Element property 'children[]' can't be empty\n".
            "1[whiteBlock] Element 'whiteBlock' can't have [whiteBlock, whiteBlock] as child. Only [paragraph, header, readOnly] allowed.\n".
            "1[whiteBlock]0[whiteBlock] Element property 'children[]' can't be empty\n".
            "1[whiteBlock]1[whiteBlock] Element 'whiteBlock' can't have [whiteBlock] as child. Only [paragraph, header, readOnly] allowed.\n".
            "1[whiteBlock]1[whiteBlock]0[whiteBlock] Element property 'children[]' can't be empty\n".
            "2[whiteBlock] Element 'whiteBlock' can't have [normalText] as child. Only [paragraph, header, readOnly] allowed.\n".
            "2[whiteBlock]0[normalText] Element property 'content' can't be empty\n".
            "3[whiteBlock] Element property 'children[]' can't be empty",
            $validator->validate($emailTemplate)->dump()
        );
    }
}
