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
 * Assets (JS, CSS, SWF, etc.) requirements for daytracks extension
 *
 * @package		Todoyu
 * @subpackage	Daytracks
 */

if( ! defined('TODOYU') ) die('NO ACCESS');


$CONFIG['EXT']['daytracks']['assets'] = array(
		// default assets: loaded all over the installation always
	'default' => array(
		'js' => array(

		),
		'css' => array(

		)
	),


		// public assets: basis assets for this extension
	'public' => array(
		'js' => array(
			array(
				'file'		=> 'ext/daytracks/assets/js/Ext.js',
				'position'	=> 100
			),
			array(
				'file'		=> 'ext/daytracks/assets/js/History.js',
				'position'	=> 101
			)
		),
		'css' => array(
			array(
				'file'		=> 'ext/daytracks/assets/css/ext.css',
				'position'	=> 100
			),
			array(
				'file'		=> 'ext/daytracks/assets/css/history.css',
				'position'	=> 101
			)
		)
	),


	'panelwidget-daytracks' => array(
		'js' => array(
			array(
				'file'		=> 'ext/daytracks/assets/js/PanelWidgetDaytracks.js',
				'position'	=> 110
			),
			array(
				'file'		=> 'ext/daytracks/assets/js/PanelWidgetDaytracksContextmenu.js',
				'position'	=> 111
			)
		),
		'css' => array(
			array(
				'file'		=> 'ext/daytracks/assets/css/panelwidget-daytracks.css',
				'position'	=> 110
			)
		)
	)
);


?>