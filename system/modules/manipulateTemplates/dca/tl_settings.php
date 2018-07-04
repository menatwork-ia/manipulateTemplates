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
$GLOBALS['TL_DCA']['tl_settings']['fields']['manipulateTemplates'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['manipulateTemplates'],
    'inputType' => 'multiColumnWizard',
    'exclude'   => true,
    'eval'      => [
        'style'        => 'width:100%;',
        'columnFields' => [
            'mt_template' => [
                'label'            => $GLOBALS['TL_LANG']['tl_settings']['mt_template'],
                'inputType'        => 'select',
                'eval'             => [ 'style' => 'width:150px', 'nospace' => true ],
                'options_callback' => [ 'mt_tl_settings', 'getActiveTemplates' ],
            ],
            'mt_search'   => [
                'label'     => $GLOBALS['TL_LANG']['tl_settings']['mt_search'],
                'inputType' => 'text',
                'eval'      => [ 'allowHtml' => true, 'preserveTags' => true, 'decodeEntities' => true, 'style' => 'width:191px' ],
            ],
            'mt_replace'  => [
                'label'     => $GLOBALS['TL_LANG']['tl_settings']['mt_replace'],
                'inputType' => 'text',
                'eval'      => [ 'allowHtml' => true, 'preserveTags' => true, 'decodeEntities' => true, 'style' => 'width:191px' ],
            ],
            'mt_inactive' => [
                'label'     => $GLOBALS['TL_LANG']['tl_settings']['mt_inactive'],
                'inputType' => 'checkbox',
                'eval'      => [ 'style' => 'width:40px' ],
            ],
        ],
    ],
];

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
        if (version_compare(VERSION, '4.0', '>=')) {
            return $this->getActiveTemplatesContao4();
        } else {
            return $this->getActiveTemplatesContao3();
        }
    }

    /**
     * Gets a list with all Contao 4 templates.
     */
    protected function getActiveTemplatesContao4()
    {
        $arrAllTemplates = [];
        $arrAllowed      = StringUtil::trimsplit(',', strtolower(Config::get('templateFiles')));

        /** @var SplFileInfo[] $files */
        $files = System::getContainer()->get('contao.resource_finder')->findIn('templates')->files()->name('/\.(' . implode('|', $arrAllowed) . ')$/');

        foreach ($files as $file) {
            $strRelpath                               = str_replace('.' . $file->getExtension(), '', $file->getFilename());
            $strModule                                = preg_replace('@^(vendor/([^/]+/[^/]+)/|system/modules/([^/]+)/).*$@', '$2$3', strtr(StringUtil::stripRootDir($file->getPathname()), '\\', '/'));
            $arrAllTemplates[$strModule][$strRelpath] = $strRelpath;
        }

        return $arrAllTemplates;
    }

    /**
     * Get a list with all Contao 3 templates.
     *
     * @return array
     */
    protected function getActiveTemplatesContao3()
    {
        $arrAllTemplates = [];
        $arrAllowed      = trimsplit(',', $GLOBALS['TL_CONFIG']['templateFiles']);

        // Get all templates
        foreach ($this->Config->getActiveModules() as $strModule) {
            // Continue if there is no templates folder
            if ($strModule == 'repository' || !is_dir(TL_ROOT . '/system/modules/' . $strModule . '/templates')) {
                continue;
            }

            // Find all templates
            $objFiles = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    TL_ROOT . '/system/modules/' . $strModule . '/templates', FilesystemIterator::UNIX_PATHS | \FilesystemIterator::FOLLOW_SYMLINKS
                )
            );

            foreach ($objFiles as $objFile) {
                if ($objFile->isFile()) {
                    $strExtension = pathinfo($objFile->getFilename(), PATHINFO_EXTENSION);

                    if (in_array($strExtension, $arrAllowed)) {
                        $strName = basename($objFile->getPathname());
                        $strName = str_replace('.' . $strExtension, '', $strName);

                        $arrAllTemplates[$strModule][$strName] = $strName;
                    }
                }
            }
        }

        return $arrAllTemplates;
    }

}