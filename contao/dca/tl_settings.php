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

use Contao\Backend;
use Contao\Config;
use Contao\Controller;
use Contao\StringUtil;
use Contao\System;

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

        //ToDo: find alle templates and not only the fe_ones
        return Controller::getTemplateGroup('fe_');

    }
}
