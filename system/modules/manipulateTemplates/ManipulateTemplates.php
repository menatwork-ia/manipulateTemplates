<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2013 
 * @package    manipulateTemplates
 * @license    GNU/LGPL 
 * @filesource
 */

class ManipulateTemplates extends Controller
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
        if ($GLOBALS['TL_CONFIG']['manipulateTemplates'])
        {
            foreach (deserialize($GLOBALS['TL_CONFIG']['manipulateTemplates']) as $item)
            {
                if (empty($item['mt_inactive']))
                {
                    if ($strTemplate == $item['mt_template'])
                    {
                        $strReplace = $this->replaceInsertTags($item['mt_replace']);
                        $strContent = str_replace($item['mt_search'], $strReplace, $strContent);
                    }
                }
            }
        }
        return $strContent;
    }

    public function generatePage(&$objPage, $objLayout, $objPageRegular)
    {
        $objPage->cssClass .= (strlen($objPage->cssClass) == 0) ? standardize($this->Environment->ip) : ' ' . standardize($this->Environment->ip);        
    }

}