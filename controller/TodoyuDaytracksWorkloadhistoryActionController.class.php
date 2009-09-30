<?php

class TodoyuDaytracksWorkloadhistoryActionController extends TodoyuActionController {

	public function renderAction(array $params) {
		return TodoyuDaytracksWorkloadHistoryRenderer::render();
	}
		
}

?>