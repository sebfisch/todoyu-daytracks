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
 *	Context menu for hours sheet panel widget. Use the same items, change behaviour.
 */




$CONFIG['EXT']['daytracks']['ContextMenu']['PanelWidget'] = array(
	'showinproject' => $CONFIG['EXT']['project']['ContextMenu']['Task']['showinproject'],
	'status'		=> $CONFIG['EXT']['project']['ContextMenu']['Task']['status'],
	'bookmark'		=> $CONFIG['EXT']['project']['ContextMenu']['Task']['bookmark']
);

$CONFIG['EXT']['daytracks']['ContextMenu']['PanelWidget']['showinproject']['jsAction'] 	= 'Todoyu.Ext.Daytracks.PanelWidget.Daytracks.showInProject(#ID#)';

$CONFIG['EXT']['daytracks']['ContextMenu']['PanelWidget']['status']['submenu']['planning']['jsAction'] 	= 'Todoyu.Ext.daytracks.PanelWidget.Daytracks.updateTaskStatus(#ID#, ' . STATUS_PLANNING . ')';
$CONFIG['EXT']['daytracks']['ContextMenu']['PanelWidget']['status']['submenu']['open']['jsAction'] 		= 'Todoyu.Ext.daytracks.PanelWidget.Daytracks.updateTaskStatus(#ID#, ' . STATUS_OPEN . ')';
$CONFIG['EXT']['daytracks']['ContextMenu']['PanelWidget']['status']['submenu']['progress']['jsAction'] 	= 'Todoyu.Ext.daytracks.PanelWidget.Daytracks.updateTaskStatus(#ID#, ' . STATUS_PROGRESS . ')';
$CONFIG['EXT']['daytracks']['ContextMenu']['PanelWidget']['status']['submenu']['confirm']['jsAction'] 	= 'Todoyu.Ext.daytracks.PanelWidget.Daytracks.updateTaskStatus(#ID#, ' . STATUS_CONFIRM . ')';
$CONFIG['EXT']['daytracks']['ContextMenu']['PanelWidget']['status']['submenu']['done']['jsAction'] 		= 'Todoyu.Ext.daytracks.PanelWidget.Daytracks.updateTaskStatus(#ID#, ' . STATUS_DONE . ')';
$CONFIG['EXT']['daytracks']['ContextMenu']['PanelWidget']['status']['submenu']['accepted']['jsAction'] 	= 'Todoyu.Ext.daytracks.PanelWidget.Daytracks.updateTaskStatus(#ID#, ' . STATUS_ACCEPTED . ')';
$CONFIG['EXT']['daytracks']['ContextMenu']['PanelWidget']['status']['submenu']['rejected']['jsAction'] 	= 'Todoyu.Ext.daytracks.PanelWidget.Daytracks.updateTaskStatus(#ID#, ' . STATUS_REJECTED . ')';
$CONFIG['EXT']['daytracks']['ContextMenu']['PanelWidget']['status']['submenu']['cleared']['jsAction'] 	= 'Todoyu.Ext.daytracks.PanelWidget.Daytracks.updateTaskStatus(#ID#, ' . STATUS_CLEARED . ')';

?>