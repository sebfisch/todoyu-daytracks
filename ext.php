<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2, 
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Extension main file for daytracks extension
 *
 * @package		Todoyu
 * @subpackage	DayTracks
 */

if( ! defined('TODOYU') ) die('NO ACCESS');

	// declare ext ID, path
define('EXTID_DAYTRACKS', 107);
define('PATH_EXT_DAYTRACKS', PATH_EXT . '/daytracks');

	// request configurations
require_once( PATH_EXT_DAYTRACKS . '/config/extension.php' );
//require_once( PATH_EXT_PROJECT . '/config/filters.php' );

	// register localization files
TodoyuLocale::register('daytracks', PATH_EXT_DAYTRACKS . '/locale/ext.xml');
TodoyuLocale::register('panelwidget-daytracks', PATH_EXT_DAYTRACKS . '/locale/panelwidget-daytracks.xml');

?>