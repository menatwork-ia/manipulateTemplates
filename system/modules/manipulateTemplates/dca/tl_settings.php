<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2013 
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

    /**
     * Get a list with all templates. Chose a function 
     * for contao 2 or 3.
     * 
     * @return array
     */
    public function getActiveTemplates()
    {
        if (version_compare(VERSION, '3.0', '>='))
        {
            return $this->getActiveTemplatesContao3();
        }
        else
        {
            return $this->getActiveTemplatesContao2();
        }
    }

    /**
     * Get a list with all Contao 3 templates.
     * 
     * @return array
     */
    protected function getActiveTemplatesContao3()
    {
        $arrAllTemplates = array();
        $arrAllowed      = trimsplit(',', $GLOBALS['TL_CONFIG']['templateFiles']);

        // Get all templates
        foreach ($this->Config->getActiveModules() as $strModule)
        {
            // Continue if there is no templates folder
            if ($strModule == 'repository' || !is_dir(TL_ROOT . '/system/modules/' . $strModule . '/templates'))
            {
                continue;
            }

            // Find all templates
            $objFiles = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator(
                    TL_ROOT . '/system/modules/' . $strModule . '/templates', FilesystemIterator::UNIX_PATHS | \FilesystemIterator::FOLLOW_SYMLINKS
                    )
            );

            foreach ($objFiles as $objFile)
            {
                if ($objFile->isFile())
                {
                    $strExtension = pathinfo($objFile->getFilename(), PATHINFO_EXTENSION);

                    if (in_array($strExtension, $arrAllowed))
                    {
                        $strName = basename($objFile->getPathname());
                        $strName = str_replace('.' . $strExtension, '', $strName);

                        $arrAllTemplates[$strModule][$strName] = $strName;
                    }
                }
            }
        }

        return $arrAllTemplates;
    }

    /**
     * Get a list with all Contao 2 templates.
     * 
     * @return array
     */
    protected function getActiveTemplatesContao2()
    {
        $arrTemplates         = array();
        $arrAllowedExtensions = trimsplit(",", $GLOBALS['TL_CONFIG']['templateFiles']);

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

                $strName                            = preg_replace("/\.(" . implode("|", $arrAllowedExtensions) . ")$/", "", $strTemplate);
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

            $strName                             = preg_replace("/\.(" . implode("|", $arrAllowedExtensions) . ")$/", "", $strTemplate);
            $arrTemplates["templates"][$strName] = $strName;
        }

        return $arrTemplates;
    }

}