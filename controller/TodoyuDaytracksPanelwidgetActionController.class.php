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
 * Action controller for daytracks panelwidget
 *
 * @package 	Todoyu
 * @subpackage	Daytracks
 */
class TodoyuDaytracksPanelwidgetActionController extends TodoyuActionController {

	/**
	 * Init. Check rights for panelwidget
	 *
	 * @param	Array		$params
	 */
	public function init(array $params) {
		restrict('daytracks', 'general:use');
	}



	/**
	 * Update the panelwidget content
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function updateAction(array $params) {
		$panelWidget = TodoyuPanelWidgetManager::getPanelWidget('Daytracks');

		return $panelWidget->getContent();
	}



	/**
	 * Get contextmenu for daytracks panelwidget
	 *
	 * @param	Array		$params
	 */
	public function contextmenuAction(array $params) {
		TodoyuHeader::sendHeaderJSON();

		$idTask		= intval($params['task']);

		$contextMenu= new TodoyuContextMenu('DaytracksPanelwidget', $idTask);

		$contextMenu->printJSON();
	}

}

?>