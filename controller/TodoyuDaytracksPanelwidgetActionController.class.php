<?php

class TodoyuDaytracksPanelwidgetActionController extends TodoyuActionController {

	public function updateAction(array $params) {
		$panelWidget = TodoyuPanelWidgetManager::getPanelWidget('Daytracks');
		
		return $panelWidget->getContent();
	}
	
	
	public function contextmenuAction(array $params) {
		TodoyuHeader::sendHeaderJSON();

		$idTask		= intval($params['task']);
		
		$contextMenu= new TodoyuContextMenu('DaytracksPanelwidget', $idTask);
		
		return $contextMenu->getJSON();
	}
		
}

?>