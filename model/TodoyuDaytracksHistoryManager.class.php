<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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
 * Manager for daytracks history
 *
 * @package 	Todoyu
 * @subpackage	Daytracks
 */
class TodoyuDaytracksHistoryManager {

	/**
	 * @var	String		Default table for database requests
	 */
	const TABLE = 'ext_timetracking_track';



	/**
	 * Check whether given person has time tracks in given month of given year
	 *
	 * @param	Integer		$idPerson
	 * @param	Integer		$month
	 * @param	Integer		$year
	 * @return	Boolean
	 */
	public static function personHasTracksInMonth($idPerson, $month, $year) {
		$idPerson	= intval($idPerson);
		$month		= intval($month);
		$year		= intval($year);

		$monthStart	= mktime(0, 0, 0, $month, 1, $year);
		$monthEnd	= mktime(0, 0, 0, $month + 1, 0, $year);

		$tracks = TodoyuTimetracking::getPersonTracks($monthStart, $monthEnd, $idPerson);

		return ( count($tracks) > 0 );
	}



	/**
	 * Get the date of the first and the last tracking
	 *
	 * @param	Integer		$idPerson
	 * @return	Array		[min,max]
	 */
	public static function getTrackingRanges($idPerson = 0) {
		$idPerson	= personid($idPerson);
		$fields	= '	MIN(' . Todoyu::db()->backtick('date_track') . ') as ' . Todoyu::db()->backtick('min') . ',
					MAX(' . Todoyu::db()->backtick('date_track') . ') as ' . Todoyu::db()->backtick('max');
		$table	= self::TABLE;
		$where	= 'id_person_create = ' . $idPerson;

		$result	= Todoyu::db()->getRecordByQuery($fields, $table, $where);

		return array(
			'min'	=> intval($result['min']),
			'max'	=> intval($result['max'])
		);
	}



	/**
	 * Get data array for the range selector in the history pop-up
	 * The years are the keys, the month the sub elements of each year
	 * The range is starts at the first tracking, and ends at the last
	 *
	 * @param	Integer		$idPerson
	 * @return	Array
	 */
	public static function getMonthSelectorOptions($idPerson = 0) {
		$range	= self::getTrackingRanges($idPerson);
		$options= array();

		$current = $range['max'];
		$min	 = mktime(0, 0, 0, date('n', $range['min']), 1, date('Y', $range['min']));

		while( $min <= $current ) {
			$year	= date('Y', $current);
			$month	= date('n', $current);

			$options[$year][$month]	= array(
				'label'		=> Label('date.month.' . strtolower(date('F', $current))),
				'hasTracks'	=> self::personHasTracksInMonth($idPerson, $month, $year)
			);

				// Advance to next month / year
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
	 * Get all tracks in a range for a person
	 * The tracks are grouped by day and already summed up in the total key
	 *
	 * @param	Integer		$year
	 * @param	Integer		$month
	 * @param	Boolean		$details
	 * @param	Integer		$idPerson
	 * @return	Array
	 */
	public static function getRangeTracks($year, $month, $details = false, $idPerson = 0) {
		$year		= intval($year);
		$month		= intval($month);
		$idPerson	= personid($idPerson);
		$dateStart	= mktime(0, 0, 0, $month, 1, $year);
		$dateEnd	= mktime(0, 0, 0, $month + 1, 1, $year) - 1;


		$tracks		= TodoyuTimetracking::getPersonTracks($dateStart, $dateEnd, $idPerson);

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
					$tracksByDay[$timestamp]['tracks'][$index]['task'] 		= TodoyuTaskManager::getTaskData($track['id_task']);
					$tracksByDay[$timestamp]['tracks'][$index]['seeTask'] 	= TodoyuTaskRights::isSeeAllowed($track['id_task']);
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