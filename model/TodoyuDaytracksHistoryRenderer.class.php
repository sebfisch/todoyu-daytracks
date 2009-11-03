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
 * Workload history renderer
 *
 * @package 	Todoyu
 * @subpackage	Daytracks
 */
class TodoyuDaytracksHistoryRenderer {

	/**
	 * Render the history in the popup
	 *
	 * @param	Integer		$year
	 * @param	Integer		$month
	 * @param	Bool		$details
	 * @return	String
	 */
	public static function renderHistory($year = 0, $month = 0, $details = false) {
		$year	= intval($year);
		$month	= intval($month);

			// use current date if not set
		$year	= $year === 0 ? date('Y') : $year ;
		$month	= $month === 0 ? date('n') : $month ;

		$tmpl	= 'ext/daytracks/view/history.tmpl';
		$data	=	array(
			'id'		=> 'daytracks-history',
			'curYear'	=> $year,
			'curMonth'	=> $month,
			'details'	=> $details,
			'tracking'	=> TodoyuDaytracksHistoryManager::getRangeTracks($year, $month, $details), //$timetracks->getLoadedTrackedTime(),
			'ranges'	=> TodoyuDaytracksHistoryManager::getMonthSelectorOptions()
		);

		return render($tmpl, $data);
	}

}
?>