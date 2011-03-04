<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Hours sheet which lists all task the person tracked today and
 * cumulate todays worktime
 *
 * @package		Todoyu
 * @subpackage	Daytracks
 */
class TodoyuDaytracksPanelWidgetDaytracks extends TodoyuPanelWidget implements TodoyuPanelWidgetIf {

	/**
	 * Constructor (initialize widget)
	 *
	 * @param	Array	$config
	 * @param	Array	$params
	 */
	public function __construct(array $config, array $params = array(), $idArea = 0) {

		// Construct PanelWidget (init basic configuration)
		parent::__construct(
			'daytracks',							// ext key
			'daytracks',							// panel widget ID
			'LLL:daytracks.panelwidget-daytracks.title',		// widget title text
			$config,								// widget config array
			$params,								// widget parameters
			$idArea
		);

		$this->addClass('daytracks');
		$this->addHasIconClass();

			// Add onload init function
		TodoyuPage::addJsOnloadedFunction('Todoyu.Ext.daytracks.PanelWidget.Daytracks.init.bind(Todoyu.Ext.daytracks.PanelWidget.Daytracks)', 100);
	}



	/**
	 * Render widget content
	 *
	 * @return	String
	 */
	public function renderContent() {
		$tmpl	= 'ext/daytracks/view/panelwidget-daytracks.tmpl';

		$tasks	= TodoyuDaytracksManager::getTodayTrackedTasks();

		$current= TodoyuDaytracksManager::getCurrentTrackedUnsavedTask();
		if( $current !== false ) {
			$tasks[] = $current;
		}

			// Add 'isTrackable' flag to each tracking
		foreach($tasks as $index => $task) {
			$tasks[$index]['isTrackable']	= TodoyuTimetracking::isTrackable($task['type'], $task['status'], $task['id']);
			$tasks[$index]['seeTask']		= TodoyuProjectTaskRights::isSeeAllowed($task['id']); // TodoyuTimetracking::isTrackable($track['type'], $track['status']);
		}


		$data	= array(
			'tasks'		=> $tasks,
			'current'	=> TodoyuTimetracking::getTaskID(),
			'total'		=> TodoyuTimetracking::getTodayTrackedTime()
		);

		return render($tmpl, $data);
	}



	/**
	 * Render widget content (get evoked)
	 *
	 * @return	String
	 */
	public function render() {
		$this->setContent( $this->renderContent() );

		return parent::render();
	}



	/**
	 * Get widget content
	 *
	 * @return	String
	 */
	public function getContent() {

		return $this->renderContent();
	}



	/**
	 * Check whether panel widget is allowed
	 *
	 * @return	Boolean
	 */
	public static function isAllowed() {
		return allowed('daytracks', 'general:use');
	}

}

?>