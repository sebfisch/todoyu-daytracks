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
 * Hours sheet which lists all task the person tracked today and
 * cumulate todays worktime
 *
 * @package		Todoyu
 * @subpackage	Timetracking
 */
class TodoyuPanelWidgetDaytracks extends TodoyuPanelWidget implements TodoyuPanelWidgetIf {


	/**
	 * Constructor (initialize widget)
	 *
	 * @param	Array	$config
	 * @param	Array	$params
	 */
	public function __construct(array $config, array $params = array(), $idArea = 0) {

		// construct PanelWidget (init basic configuration)
		parent::__construct(
			'daytracks',							// ext key
			'daytracks',							// panel widget ID
			'LLL:panelwidget-daytracks.title',		// widget title text
			$config,								// widget config array
			$params,								// widget params
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
	 * @return unknown
	 */
	public function renderContent() {
		$tmpl	= 'ext/daytracks/view/panelwidget-daytracks.tmpl';

		$tracks	= TodoyuDaytracksManager::getTodayTrackedTasks();

		$current= TodoyuDaytracksManager::getCurrentTrackedUnsavedTask();

		if( $current !== false ) {
			$tracks[] = $current;
		}

		foreach($tracks as $index => $track)	 {
			$tracks[$index]['isTrackable']	= TodoyuTimetracking::isTrackable($track['type'], $track['status']);
		}
		
		
		$data	= array(
			'tasks'		=> $tracks,
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
	 * Check if panelwidget is allowed
	 *
	 * @return	Bool
	 */
	public static function isAllowed() {
		return allowed('daytracks', 'panelwidgets:daytracks');
	}


}

?>