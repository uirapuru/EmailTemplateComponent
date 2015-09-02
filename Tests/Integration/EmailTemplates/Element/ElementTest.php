<?php

namespace TPN\BaseMainBundle\Tests\Integration\EmailTemplates\Element;

use TPN\EmailTemplatesComponent\Element\BoldText;
use TPN\EmailTemplatesComponent\Element\ElementInterface;
use TPN\EmailTemplatesComponent\Element\Header;
use TPN\EmailTemplatesComponent\Element\NormalText;
use TPN\EmailTemplatesComponent\Element\Paragraph;
use TPN\EmailTemplatesComponent\Element\WhiteBlock;

class ElementTest extends \PHPUnit_Framework_TestCase
{
    public function testDump()
    {
        $whiteBlock = new WhiteBlock([
            new Paragraph([
                new NormalText('some test 1'),
            ], '<p>%s</p>'),
            new Header([
                new NormalText('some test 2'),
            ], '<h1>%s</h1>'),
        ], '<wb>%s</wb>');

        $this->assertEquals('<wb><p>some test 1</p><h1>some test 2</h1></wb>', $whiteBlock->dump());
    }

    public function testDumpElementWithNewLine()
    {
        $whiteBlock = new WhiteBlock([
            new Paragraph([
                new BoldText("bold text"),
                new NormalText("first line\nsecond line"),
            ], '<p>%s</p>'),
            new Header([
                new NormalText("first line\nsecond line"),
            ], '<h1>%s</h1>'),
        ], '<wb>%s</wb>');

        $this->assertEquals('<wb><p><b>bold text</b>first line<br />
second line</p><h1>first line<br />
second line</h1></wb>', $whiteBlock->dump());
    }

    /**
     * @param ElementInterface        $element
     * @param string|ElementInterface $children
     * @param bool                    $supports
     * @dataProvider testIsAllowedTrueDataProvider
     */
    public function testIsAllowed(ElementInterface $element, $children, $supports)
    {
        $this->assertEquals($element->isAllowed($children), $supports);
    }

    public function testIsAllowedTrueDataProvider()
    {
        return [
            'wb -> para' => ['element' => new WhiteBlock(), 'children' => new Paragraph(), 'supports' => true],
            'wb -> head' => ['element' => new WhiteBlock(), 'children' => new Header(), 'supports' => true],
            'wb -> nt' => ['element' => new WhiteBlock(), 'children' => new NormalText(), 'supports' => false],
            'wb -> bt' => ['element' => new WhiteBlock(), 'children' => new BoldText(), 'supports' => false],
            'wb -> wb' => ['element' => new WhiteBlock(), 'children' => new WhiteBlock(), 'supports' => false],

            'para -> nt' => ['element' => new Paragraph(), 'children' => new NormalText(), 'supports' => true],
            'para -> bt' => ['element' => new Paragraph(), 'children' => new BoldText(), 'supports' => true],
            'para -> para' => ['element' => new Paragraph(), 'children' => new Paragraph(), 'supports' => false],
            'para -> head' => ['element' => new Paragraph(), 'children' => new Header(), 'supports' => false],
            'para -> wb' => ['element' => new Paragraph(), 'children' => new WhiteBlock(), 'supports' => false],

            'head -> nt' => ['element' => new Header(), 'children' => new NormalText(), 'supports' => true],
            'head -> bt' => ['element' => new Header(), 'children' => new BoldText(), 'supports' => false],
            'head -> para' => ['element' => new Header(), 'children' => new Paragraph(), 'supports' => false],
            'head -> head' => ['element' => new Header(), 'children' => new Header(), 'supports' => false],
            'head -> wb' => ['element' => new Header(), 'children' => new WhiteBlock(), 'supports' => false],

            'nt -> nt' => ['element' => new NormalText(), 'children' => new NormalText(), 'supports' => false],
            'nt -> bt' => ['element' => new NormalText(), 'children' => new BoldText(), 'supports' => false],
            'nt -> para' => ['element' => new NormalText(), 'children' => new Paragraph(), 'supports' => false],
            'nt -> head' => ['element' => new NormalText(), 'children' => new Header(), 'supports' => false],
            'nt -> wb' => ['element' => new NormalText(), 'children' => new WhiteBlock(), 'supports' => false],

            'bt -> nt' => ['element' => new BoldText(), 'children' => new NormalText(), 'supports' => false],
            'bt -> bt' => ['element' => new BoldText(), 'children' => new BoldText(), 'supports' => false],
            'bt -> para' => ['element' => new BoldText(), 'children' => new Paragraph(), 'supports' => false],
            'bt -> head' => ['element' => new BoldText(), 'children' => new Header(), 'supports' => false],
            'bt -> wb' => ['element' => new BoldText(), 'children' => new WhiteBlock(), 'supports' => false],
        ];
    }
}
