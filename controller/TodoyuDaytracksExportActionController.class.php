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
 * Controller for the export functionality
 */
class TodoyuDaytracksExportActionController extends TodoyuActionController {

	/**
	 * Init controller: restrict to rights
	 *
	 * @param	Array	$params
	 */
	public function init(array $params) {
		restrict('daytracks', 'general:use');
		restrict('daytracks', 'daytracks:timeExport');
	}



	/**
	 * Renders the download pop-up
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function renderpopupAction(array $params) {
		return TodoyuDaytracksExportRenderer::renderDaytracksExportForm($params);
	}



	/**
	 * Download Action for the csv-file
	 *
	 * @param	Array	$params
	 */
	public function downloadAction(array $params) {
		$form	= TodoyuDaytracksExportManager::getExportForm();
		
		$values	= TodoyuArray::assure($params['export']);

		$data	= $form->getStorageData($values);
		
		TodoyuDaytracksExportManager::exportCSV($data);
	}
}

?>