<?php

namespace MenAtWork\ManipulateTemplates\Service;
use Contao\StringUtil;
use Contao\System;

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2013
 * @package    manipulateTemplates
 * @license    GNU/LGPL
 * @filesource
 */
class ManipulateTemplates
{

    /**
     * Manipulate the template output
     *
     * @param string $strContent
     * @param string $strTemplate
     * @return string
     */
    public function parseFrontendTemplate($strContent, $strTemplate)
    {
        $parser = System::getContainer()->get('contao.insert_tag.parser');

        if ($GLOBALS['TL_CONFIG']['manipulateTemplates']) {
            foreach (StringUtil::deserialize($GLOBALS['TL_CONFIG']['manipulateTemplates']) as $item) {
                if (empty($item['mt_inactive'])) {
                    if ($strTemplate == $item['mt_template']) {
                        $strReplace = $parser->replace($item['mt_replace']);
                        $strContent = str_replace($item['mt_search'], $strReplace, $strContent);
                    }
                }
            }
        }
        return $strContent;
    }
}