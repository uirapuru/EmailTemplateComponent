<?php

namespace TPN\EmailTemplatesComponent\Command;

use TPN\EmailTemplatesComponent\Model\EmailTemplate;

class BlockEmbedder implements CommandInterface
{
    const EMBED_EXTENSION = '{%% embed "%s" %%}%s{%% endembed %%}';

    /**
     * @var string
     */
    protected $baseBlockNameTemplate = '::EmailBlocks/%s.html.twig';

    public function execute(EmailTemplate $template)
    {
        $body = $template->getBody();
        $updated = $this->replaceBlocksWithBlocksEmbed($body);
        $template->setBody($updated);
    }

    /**
     * @param string $templateBody
     *
     * @return string
     */
    protected function replaceBlocksWithBlocksEmbed($templateBody)
    {
        list($expressions, $blocks) = $this->analyzeTemplate($templateBody);

        foreach ($expressions as $i => $expression) {
            if (preg_match($expression, $templateBody, $blockContentResult) === 0) {
                continue;
            }

            $foundBlock = $blockContentResult['block'];

            $embeddedBlock = sprintf(
                self::EMBED_EXTENSION,
                sprintf($this->baseBlockNameTemplate, $blocks[$i]),
                $foundBlock
            );

            $templateBody = str_replace($foundBlock, $embeddedBlock, $templateBody);
        }

        return $templateBody;
    }

    /**
     * Returns a set of prepared expressions to be used in traversing template.
     *
     * @param $templateBody
     *
     * @return array[]
     */
    private function analyzeTemplate($templateBody)
    {
        $expressions = [];
        $blocks = [];

        // find all {% block blockName %} occurrences
        $anyBlockRegex = "/(\{%\s*block\s(?<blockName>[A-Za-z0-9_-]+)\s*?%\})/us";

        if (preg_match_all($anyBlockRegex, $templateBody, $blockNameResult) !== 0) {
            foreach ($blockNameResult[1] as $key => $blockTag) {
                $blockTag = preg_quote($blockTag);
                $blockName = preg_quote($blockNameResult['blockName'][$key]);
                $filename = sprintf(preg_quote($this->baseBlockNameTemplate, '/'), $blockName);

                // find "{% block blockName %}.*{% endblock blockName %}" not preceded directly by {% embed .* %} AND not followed by {% endembed %}
                // (if block is preceded by embed and followed by endembed that means it has been already processed, we don't need to wrap it in embed
                // second time - useful in cases when we search for more than one occurrence of the same block in string)

                $expressions[] = "/(?<!\{% embed \"".$filename."\" %\})(?<block>".$blockTag."(?<content>.*?)\{%\s*?endblock\s*?".$blockName."\s*?%\})(?!{% endembed %})/us";
                $blocks[] = $blockName;
            }
        }

        return [$expressions, $blocks];
    }
}
