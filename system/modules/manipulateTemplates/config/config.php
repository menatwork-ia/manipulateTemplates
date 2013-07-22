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
 * Hooks
 */

$GLOBALS['TL_HOOKS']['parseFrontendTemplate'][]    = array('ManipulateTemplates', 'parseFrontendTemplate');
$GLOBALS['TL_HOOKS']['generatePage'][]             = array('ManipulateTemplates', 'generatePage');