<?php

class TodoyuDaytracksPreferenceActionController extends TodoyuActionController {

	public function pwidgetAction(array $params) {
		$idWidget	= $params['item'];
		$value		= $params['value'];
		
		TodoyuPanelWidgetManager::saveCollapsedStatus(EXTID_DAYTRACKS, $idWidget, $value);
	}
		
}

?>