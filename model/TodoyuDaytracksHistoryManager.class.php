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
 * Manager for daytracks history
 *
 * @package 	Todoyu
 * @subpackage	Daytracks
 */
class TodoyuDaytracksHistoryManager {

	/**
	 * Database table
	 *
	 */
	const TABLE = 'ext_timetracking_track';



	/**
	 * Get the date of the first and the last tracking
	 *
	 * @param	Integer		$idUser
	 * @return	Array		[min,max]
	 */
	public static function getTrackingRanges($idUser = 0) {
		$idUser	= userid($idUser);
		$fields	= '	MIN(' . Todoyu::db()->backtick('date_track') . ') as ' . Todoyu::db()->backtick('min') . ',
					MAX(' . Todoyu::db()->backtick('date_track') . ') as ' . Todoyu::db()->backtick('max');
		$table	= self::TABLE;
		$where	= 'id_user_create = ' . $idUser;

		$result	= Todoyu::db()->getRecordByQuery($fields, $table, $where);

		return array(
			'min'	=> intval($result['min']),
			'max'	=> intval($result['max'])
		);
	}



	/**
	 * Get data array for the range selector in the history popup
	 * The years are the keys, the month the subelements of each year
	 * The range is starts at the first tracking, and ends at the last
	 *
	 * @param	Integer		$idUser
	 * @return	Array
	 */
	public static function getMonthSelectorOptions($idUser = 0) {
		$range	= self::getTrackingRanges($idUser);
		$options= array();

		$current = $range['max'];
		$min	 = mktime(0, 0, 0, date('n', $range['min']), 1, date('Y', $range['min']));

		while( $min <= $current) {
			$year	= date('Y', $current);
			$month	= date('n', $current);

			$options[$year][$month]	= Label('date.month.' . strtolower(date('F', $current)));

			if( $month === 1 ) {
				$month	= 12;
				$year--;
			} else {
				$month--;
			}

			$current = mktime(0, 0, 0, $month, 1, $year);
		}

		return $options;
	}



	/**
	 * Get all tracks in a range for a user
	 * The tracks are grouped by day and already summed up in the total key
	 *
	 * @param	Integer		$year
	 * @param	Integer		$month
	 * @param	Bool		$details
	 * @param	Integer		$idUser
	 * @return	Array
	 */
	public static function getRangeTracks($year, $month, $details = false, $idUser = 0) {
		$year		= intval($year);
		$month		= intval($month);
		$idUser		= userid($idUser);
		$dateStart	= mktime(0, 0, 0, $month, 1, $year);
		$dateEnd	= mktime(0, 0, 0, $month+1, 1, $year)-1;


		$tracks		= TodoyuTimetracking::getUserTracks($dateStart, $dateEnd, $idUser);

		$workloads	= TodoyuArray::getColumn($tracks, 'workload_tracked');
		$total		= array_sum($workloads);
		$tracksByDay= array();

		foreach($tracks as $track) {
			$timestamp	= TodoyuTime::getStartOfDay($track['date_track']);

			$tracksByDay[$timestamp]['tracks'][] 	= $track;
			$tracksByDay[$timestamp]['total'] 		+= $track['workload_tracked'];
		}



		if( $details ) {
			foreach($tracksByDay as $timestamp => $dayTracks) {
				foreach($dayTracks['tracks'] as $index => $track) {
					$tracksByDay[$timestamp]['tracks'][$index]['task'] = TodoyuTaskManager::getTaskData($track['id_task']);
				}
			}
		}

		return array(
			'dateStart'	=> $dateStart,
			'dateEnd'	=> $dateEnd,
			'total'		=> $total,
			'dayTracks'	=> $tracksByDay
		);
	}
}


?>