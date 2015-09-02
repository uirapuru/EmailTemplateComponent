<?php

namespace TPN\EmailTemplatesComponent\Element;

use TPN\EmailTemplatesComponent\ArrayConverter;

final class WhiteBlock extends AbstractElement implements ElementInterface
{
    /**
     * @var string
     */
    protected $markup = <<<EOL
<!-- start whiteblock element -->
<tr>
    <td height="50">&nbsp;</td>
</tr>

<tr>
    <td valign="top">
        <table border="0" align="center" cellspacing="0" cellpadding="0" style="width: 100%%; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" class="featured">
            <tbody>
                <tr>
                    <td bgcolor="#fff" style="width: 100%%; background: #fff; padding: 30px 50px;" class="featured__content">
                        %s
                    </td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
<!-- end whiteblock element -->
EOL;

    /**
     * @var string[]
     */
    public static $allowedChildren = [
        ArrayConverter::ELEMENT_PARAGRAPH,
        ArrayConverter::ELEMENT_HEADER,
        ArrayConverter::ELEMENT_READONLY,
    ];

    /**
     * @var string
     */
    protected $name = ArrayConverter::ELEMENT_WHITEBLOCK;
}
