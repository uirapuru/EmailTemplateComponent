<?php

namespace TPN\BaseMainBundle\Tests\Integration\EmailTemplates\Integration;

use TPN\EmailTemplatesComponent\ArrayConverter;
use TPN\EmailTemplatesComponent\Element\ElementFactory;

class ArrayConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromStaticArray()
    {
        $data = [
            ['type' => 'whiteBlock', 'children' => []],
            ['type' => 'whiteBlock', 'children' => [
                ['type' => 'header', 'children' => [
                    ['type' => 'normalText', 'content' => 'Some normal text in header'],
                ]],
                ['type' => 'paragraph', 'children' => [
                    ['type' => 'normalText', 'content' => 'Some normal text in paragraph 1'],
                ]],
                ['type' => 'paragraph', 'children' => [
                    ['type' => 'normalText', 'content' => 'Some normal text in paragraph 2'],
                ]],
            ]],
            ['type' => 'whiteBlock', 'children' => [
                ['type' => 'normalText', 'content' => 'Some normal text'],
                ['type' => 'boldText', 'content' => 'Some bold text'],
            ]],
        ];

        $converter = new ArrayConverter(new ElementFactory());
        $result = $converter->createFromArray($data);

        $el = ArrayConverter::$elements;

        $this->assertCount(3, $result);

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_WHITEBLOCK], $result[0]);
        $this->assertCount(0, $result[0]->getChildren());

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_WHITEBLOCK], $result[1]);
        $this->assertCount(3, $result[1]->getChildren());

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_WHITEBLOCK], $result[2]);
        $this->assertCount(2, $result[2]->getChildren());

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_HEADER], $result[1]->getChildren()[0]);
        $this->assertCount(1, $result[1]->getChildren()[0]->getChildren());

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[1]->getChildren()[0]->getChildren()[0]);
        $this->assertEquals($result[1]->getChildren()[0]->getChildren()[0]->getContent(), 'Some normal text in header');

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_PARAGRAPH], $result[1]->getChildren()[1]);
        $this->assertCount(1, $result[1]->getChildren()[1]->getChildren());

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[1]->getChildren()[1]->getChildren()[0]);
        $this->assertEquals($result[1]->getChildren()[1]->getChildren()[0]->getContent(), 'Some normal text in paragraph 1');

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[1]->getChildren()[1]->getChildren()[0]);
        $this->assertEquals($result[1]->getChildren()[2]->getChildren()[0]->getContent(), 'Some normal text in paragraph 2');

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[2]->getChildren()[0]);
        $this->assertEquals('Some normal text', $result[2]->getChildren()[0]->getContent());

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_BOLDTEXT], $result[2]->getChildren()[1]);
        $this->assertEquals('Some bold text', $result[2]->getChildren()[1]->getContent());
    }

    public function testCreateFromJsonFileArray()
    {
        $fileContents = file_get_contents(__DIR__.'/../../../Tests/Resources/email_template.json');
        $jsonArray = json_decode($fileContents, true);

        $converter = new ArrayConverter(new ElementFactory());
        $result = $converter->createFromArray($jsonArray);

        $el = ArrayConverter::$elements;

        $this->assertCount(6, $result);

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_WHITEBLOCK], $result[0]);
        $this->assertCount(2, $result[0]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_HEADER], $result[0]->getChildren()[0]);
        $this->assertCount(1, $result[0]->getChildren()[0]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[0]->getChildren()[0]->getChildren()[0]);
        $this->assertEquals('Welcome to CurbanA, Shaquille!', $result[0]->getChildren()[0]->getChildren()[0]->getContent());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_PARAGRAPH], $result[0]->getChildren()[1]);
        $this->assertCount(1, $result[0]->getChildren()[1]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[0]->getChildren()[1]->getChildren()[0]);
        $this->assertEquals('The place to earn free tickets & VIP rewards for promoting your favourite music festivals', $result[0]->getChildren()[1]->getChildren()[0]->getContent());

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_WHITEBLOCK], $result[1]);
        $this->assertCount(3, $result[1]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_HEADER], $result[1]->getChildren()[0]);
        $this->assertCount(1, $result[1]->getChildren()[0]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[1]->getChildren()[0]->getChildren()[0]);
        $this->assertEquals('1. Request tickets to promote your favourite music festivals', $result[1]->getChildren()[0]->getChildren()[0]->getContent());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_PARAGRAPH], $result[1]->getChildren()[1]);
        $this->assertCount(1, $result[1]->getChildren()[1]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[1]->getChildren()[1]->getChildren()[0]);
        $this->assertEquals("Click on the icon below to request tickets to the festival you'd like to promote.\nIf the campaign isn't live just yet, click I'm interested and you'll be the first to know when the tickets are available.", $result[1]->getChildren()[1]->getChildren()[0]->getContent());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_PARAGRAPH], $result[1]->getChildren()[2]);
        $this->assertCount(1, $result[1]->getChildren()[2]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[1]->getChildren()[2]->getChildren()[0]);
        $this->assertEquals('{{ requestTicketsButton }}', $result[1]->getChildren()[2]->getChildren()[0]->getContent());

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_WHITEBLOCK], $result[2]);
        $this->assertCount(2, $result[2]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_HEADER], $result[2]->getChildren()[0]);
        $this->assertCount(1, $result[2]->getChildren()[0]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[2]->getChildren()[0]->getChildren()[0]);
        $this->assertEquals('2. Get tickets allocated and start promoting!', $result[2]->getChildren()[0]->getChildren()[0]->getContent());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_PARAGRAPH], $result[2]->getChildren()[1]);
        $this->assertCount(1, $result[2]->getChildren()[1]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[2]->getChildren()[1]->getChildren()[0]);
        $this->assertEquals("Don't worry, if you don't sell the allocated tickets, you don't owe us anything.", $result[2]->getChildren()[1]->getChildren()[0]->getContent());

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_WHITEBLOCK], $result[3]);
        $this->assertCount(4, $result[3]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_HEADER], $result[3]->getChildren()[0]);
        $this->assertCount(1, $result[3]->getChildren()[0]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[3]->getChildren()[0]->getChildren()[0]);
        $this->assertEquals('3. Connect your social networks', $result[3]->getChildren()[0]->getChildren()[0]->getContent());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_PARAGRAPH], $result[3]->getChildren()[1]);
        $this->assertCount(1, $result[3]->getChildren()[1]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[3]->getChildren()[1]->getChildren()[0]);
        $this->assertEquals('Find out which festivals and bands your friends like and earn your FREE ticket faster!', $result[3]->getChildren()[1]->getChildren()[0]->getContent());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_PARAGRAPH], $result[3]->getChildren()[2]);
        $this->assertCount(1, $result[3]->getChildren()[2]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[3]->getChildren()[2]->getChildren()[0]);
        $this->assertEquals('{{ connectFacebookButton }}', $result[3]->getChildren()[2]->getChildren()[0]->getContent());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_PARAGRAPH], $result[3]->getChildren()[3]);
        $this->assertCount(1, $result[3]->getChildren()[2]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[3]->getChildren()[3]->getChildren()[0]);
        $this->assertEquals('{{ connectTwitterButton }}', $result[3]->getChildren()[3]->getChildren()[0]->getContent());

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_WHITEBLOCK], $result[4]);
        $this->assertCount(2, $result[4]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_HEADER], $result[4]->getChildren()[0]);
        $this->assertCount(1, $result[4]->getChildren()[0]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[4]->getChildren()[0]->getChildren()[0]);
        $this->assertEquals('Questions?', $result[4]->getChildren()[0]->getChildren()[0]->getContent());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_PARAGRAPH], $result[4]->getChildren()[1]);
        $this->assertCount(1, $result[4]->getChildren()[1]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_NORMALTEXT], $result[4]->getChildren()[1]->getChildren()[0]);
        $this->assertEquals("Your Community Manager, Dean Attil is here to help, you can find their contact details in your {{ accountLink }}.\nor feel free to take our {{ ambassadorTourLink }}.", $result[4]->getChildren()[1]->getChildren()[0]->getContent());

        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_WHITEBLOCK], $result[5]);
        $this->assertCount(2, $result[5]->getChildren());
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_HEADER], $result[5]->getChildren()[0]);
        $this->assertInstanceOf($el[ArrayConverter::ELEMENT_READONLY], $result[5]->getChildren()[1]);

        $this->assertEquals($result[5]->getChildren()[1]->getContentId(), 'ticketConfirmationSummary');
//        $this->assertEquals($result[5]->getChildren()[1]->dump(), '{{ contentId_file_ticketConfirmationSummary }}');

    }
}
