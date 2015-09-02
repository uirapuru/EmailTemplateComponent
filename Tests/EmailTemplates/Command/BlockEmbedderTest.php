<?php

namespace TPN\EmailTemplatesComponent\Tests\EmailTemplates\Command;

use TPN\BaseMainBundle\Tests\UnitTestCase;
use TPN\EmailTemplatesComponent\Command\BlockEmbedder;
use TPN\EmailTemplatesComponent\Model\EmailTemplate;

/**
 * Class BlockEmbedderTest.
 */
class BlockEmbedderTest extends UnitTestCase
{
    /**
     * @param $base
     * @param $new
     * @dataProvider testEmbedBlocksDataProvider
     */
    public function testExecute($base, $new)
    {
        $template = new EmailTemplate();
        $template->setBody($base);

        $command = new BlockEmbedder();
        $command->execute($template);

        $this->assertEquals($new, $template->getBody());
    }

    public function testEmbedBlocksDataProvider()
    {
        $brokenLine1 = <<< 'EOL'
{% block A %}
text
{% endblock A %}
EOL;
        $brokenLine1_2 = <<< 'EOL'
{% embed "::EmailBlocks/A.html.twig" %}{% block A %}
text
{% endblock A %}{% endembed %}
EOL;

        $brokenLine2 = <<< 'EOL'
        Hi {{ firstname }} !

        This message is for you, {{ firstname }} {{ lastname }}!

        {% block default %}
                This is a block for
                {{ firstname }}, {{ lastname }}
                {% block strong %}
                    This should be in strong
                    {{ firstname }}, {{ lastname }}
                {% endblock strong %}
        {% endblock default %}
EOL;
        $brokenLine2_2 = <<< 'EOL'
        Hi {{ firstname }} !

        This message is for you, {{ firstname }} {{ lastname }}!

        {% embed "::EmailBlocks/default.html.twig" %}{% block default %}
                This is a block for
                {{ firstname }}, {{ lastname }}
                {% embed "::EmailBlocks/strong.html.twig" %}{% block strong %}
                    This should be in strong
                    {{ firstname }}, {{ lastname }}
                {% endblock strong %}{% endembed %}
        {% endblock default %}{% endembed %}
EOL;

        $multiblock_1 = <<< 'EOL'
music festivals.{% block default %}Block Default{% endblock default %}Block No Background{% block default %}Block Default with button {{button_twitter}}{% endblock default %}Block-no-block
EOL;

        $multiblock_1_2 = <<< 'EOL'
music festivals.{% embed "::EmailBlocks/default.html.twig" %}{% block default %}Block Default{% endblock default %}{% endembed %}Block No Background{% embed "::EmailBlocks/default.html.twig" %}{% block default %}Block Default with button {{button_twitter}}{% endblock default %}{% endembed %}Block-no-block
EOL;

        return [
            'basic-embed' => [
                'base' => '{% block A %}text{% endblock A %}',
                'new' => '{% embed "::EmailBlocks/A.html.twig" %}{% block A %}text{% endblock A %}{% endembed %}',
            ],
            'broken-embed-1' => [
                'base' => '{% block %}text{% endblock A %}',
                'new' => '{% block %}text{% endblock A %}',
            ],
            'broken-embed-2' => [
                'base' => '{% block%}text',
                'new' => '{% block%}text',
            ],
            'custom-formating-embed' => [
                'base' => '{%block A %}text{% endblock A%}',
                'new' => '{% embed "::EmailBlocks/A.html.twig" %}{%block A %}text{% endblock A%}{% endembed %}',
            ],
            'custom-block-naming' => [
                'base' => '{% block A-_usu$su/h123 %}text{% endblock A-_usu$su/h123 %}',
                'new' => '{% block A-_usu$su/h123 %}text{% endblock A-_usu$su/h123 %}',
            ],
            'nested-embed' => [
                'base' => '{% block A %}AA{% endblock A %}{% block A %}{% block B %}text{% endblock B %}{% endblock A %}',
                'new' => '{% embed "::EmailBlocks/A.html.twig" %}{% block A %}AA{% endblock A %}{% endembed %}{% embed "::EmailBlocks/A.html.twig" %}{% block A %}{% embed "::EmailBlocks/B.html.twig" %}{% block B %}text{% endblock B %}{% endembed %}{% endblock A %}{% endembed %}',
            ],
            'nested-broken-embed-1' => [
                'base' => '{% block A %}{% block B %}text{% endblock A %}',
                'new' => '{% embed "::EmailBlocks/A.html.twig" %}{% block A %}{% block B %}text{% endblock A %}{% endembed %}',
            ],
            'nested-broken-embed-2' => [
                'base' => '{% block A %}{% block B %}text',
                'new' => '{% block A %}{% block B %}text',
            ],
            'braked-lines' => [
                'base' => $brokenLine1,
                'new' => $brokenLine1_2,
            ],
            'braked-lines-2' => [
                'base' => $brokenLine2,
                'new' => $brokenLine2_2,
            ],
            'blocks-aside' => [
                'base' => $multiblock_1,
                'new' => $multiblock_1_2,
            ],
        ];
    }
}
