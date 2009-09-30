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
 * Renderer for the worklaod history
 *
 * @package Todoyu
 * @subpackage daytracks
 */

class TodoyuDaytracksWorkloadHistoryRenderer extends TodoyuRenderer {

	/**
	 * Renders the popup
	 *
	 * @return string
	 */
	public static function render()	{
		$month	= TodoyuRequest::getParam('month')	? TodoyuRequest::getParam('month')	: date('n');
		$year	= TodoyuRequest::getParam('year')		? TodoyuRequest::getParam('year')		: date('Y');

		$details = TodoyuRequest::getParam('details') ? true : false;

		$timetracks	= new TodoyuDaytracksWorkloadHistory($month, $year, $details);

		$data	=	array(
			'tracks'	=>	$timetracks->getLoadedTrackedTime(),
			'values'	=> array(
				'year'		=> $year,
				'month'		=> $month,
				'details'	=> $details
			),
			'selectors'	=> $timetracks->getSelectorData(),
			'labels'	=> array(
				'total'			=> 'LLL:daytracks.workloadhistory.total',
				'toggleDetails'	=> array(
					'open' 	=> 'LLL:daytracks.workloadhistory.toggledetails.open',
					'close'	=> 'LLL:daytracks.workloadhistory.toggledetails.close')
				)
			);

		return render('ext/daytracks/view/daytracksworkloadhistory.tmpl', $data);
	}

}
?>