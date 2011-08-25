<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 
class ManipulateTemplates extends Controller
{

    private $arrSearchReplace = array(
        'id="right"'    	=> 'id="right" class="right"',
        'id="left"'     	=> 'id="left" class="left"',
        'id="main"'     	=> 'id="main" class="main"',
		'class="inside'    => 'class="inside clearfix',
    );
    
    public function outputFrontendTemplate($strContent, $strTemplate)
    {
        if ($strTemplate == 'fe_page')
        {
            foreach ($this->arrSearchReplace as $key => $value)
            {
                $strContent = str_replace($key, $value, $strContent);
            }            
        }

        return $strContent;
    }

    public function generatePage(Database_Result &$objPage, Database_Result $objLayout, PageRegular $objPageRegular)
    {
        $objPage->cssClass .= " nojs";
    }
    
}

?>