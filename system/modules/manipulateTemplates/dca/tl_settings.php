<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  MEN AT WORK 2011
 * @package    manipulateTemplates
 * @license    GNU/LGPL
 * @filesource
 */

/**
 * Add to palette
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{manipulateTemplates_legend},manipulateTemplates'; 

/**
 * Add field
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['manipulateTemplates'] = array(
    'label'                         => &$GLOBALS['TL_LANG']['tl_settings']['manipulateTemplates'],
    'inputType'                     => 'multiColumnWizard',
    'exclude'                       => true,
    'eval' => array
        (
        'style'                     => 'width:100%;',
        'columnFields' => array
            (
                'mt_template' => array
                (
                    'label'         => $GLOBALS['TL_LANG']['tl_settings']['mt_template'],
                    'inputType'     => 'select',
                    'eval'          => array('style' => 'width:150px', 'nospace' => true),
                    'options_callback' => array('mt_tl_settings', 'getActiveTemplates'),
                ), 
                'mt_search' => array
                (
                    'label'         => $GLOBALS['TL_LANG']['tl_settings']['mt_search'],
                    'inputType'     => 'text',
                    'eval'          => array('allowHtml' => true, 'preserveTags' => true, 'decodeEntities' => true, 'style' => 'width:191px'),
                ),
                'mt_replace' => array
                (
                    'label'         => $GLOBALS['TL_LANG']['tl_settings']['mt_replace'],
                    'inputType'     => 'text',
                    'eval'          => array('allowHtml' => true, 'preserveTags' => true, 'decodeEntities' => true, 'style' => 'width:191px'),
                ),
                'mt_inactive' => array
                (
                    'label'         => $GLOBALS['TL_LANG']['tl_settings']['mt_inactive'],
                    'inputType'     => 'checkbox',
                    'eval'          => array('style' => 'width:40px'),
                ),
            )
        )
);

// Set chosen if we have a contao version 2.11
if(version_compare(VERSION, "2.11", ">="))
{
    $GLOBALS['TL_DCA']['tl_settings']['fields']['manipulateTemplates']['eval']['columnFields']['mt_template']['eval']['chosen'] = true;
}

class mt_tl_settings extends Backend
{

    public function getActiveTemplates()
    {
        $arrAllowedExtensions = trimsplit(",", $GLOBALS['TL_CONFIG']['templateFiles']);

        $arrTemplates = array();

        // Get all templates
        foreach ($this->Config->getActiveModules() as $strModule)
        {
            // Continue if there is no templates folder
            if (!is_dir(TL_ROOT . '/system/modules/' . $strModule . '/templates'))
            {
                continue;
            }

            // Find all templates
            foreach (scan(TL_ROOT . '/system/modules/' . $strModule . '/templates') as $strTemplate)
            {
                // Ignore non-template files
                if (preg_match("/.?\.(" . implode("|", $arrAllowedExtensions) . ")/", $strTemplate) == 0)
                {
                    continue;
                }
                
                $strName = preg_replace("/\.(" . implode("|", $arrAllowedExtensions) . ")$/", "", $strTemplate);
                $arrTemplates[$strModule][$strName] = $strName;
            }
        }

        // Find all templates
        foreach (scan(TL_ROOT . '/templates') as $strTemplate)
        {
            // Ignore non-template files
            if (preg_match("/.?\.(" . implode("|", $arrAllowedExtensions) . ")/", $strTemplate) == 0)
            {
                continue;
            }

            $strName = preg_replace("/\.(" . implode("|", $arrAllowedExtensions) . ")$/", "", $strTemplate);            
            $arrTemplates["templates"][$strName] = $strName;
        }

        return $arrTemplates;
    }

}

?>