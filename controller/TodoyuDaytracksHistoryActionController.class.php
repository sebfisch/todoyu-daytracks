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
 * Action controller for daytracks history
 *
 * @package 	Todoyu
 * @subpackage	Daytracks
 */
class TodoyuDaytracksHistoryActionController extends TodoyuActionController {

	/**
	 * @param array $params
	 * @return void
	 */
	public function init(array $params) {
		restrict('daytracks', 'general:use');
	}



	/**
	 * Update tracks history popup
	 *
	 * @param	Array	$params
	 * @return	String
	 */
	public function historyAction(array $params) {
		restrict('daytracks', 'daytracks:showHistory');

		$year	= intval($params['year']);
		$month	= intval($params['month']);
		$details= intval($params['details']) === 1;

		return TodoyuDaytracksHistoryRenderer::renderHistory($year, $month, $details);
	}

}

?>