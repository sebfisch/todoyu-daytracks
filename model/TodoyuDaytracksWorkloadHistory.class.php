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
 * Class of the Daytrackhistory
 *
 * @package Todoyu
 * @subpackage daytracks

 */
class TodoyuDaytracksWorkloadHistory {

	/**
	 * Month to show the history
	 *
	 * @var	Integer
	 */
	protected $month = 0;



	/**
	 * Year to show the history
	 *
	 * @var	Integer
	 */
	protected $year = 0;



	/**
	 * array of trackings
	 *
	 * @var	Array
	 */
	protected $loadedTrackedTime = array();



	/**
	 * current user
	 *
	 * @var	Integer
	 */
	protected $user = 0;



	/**
	 * Selecor data of the month and year selector
	 *
	 * @var	Array
	 */
	protected $selectorData = array();



	/**
	 * Flag for the tracking details
	 *
	 * @var	Boolean
	 */
	protected $showDetails = false;



	/**
	 * constructor of the class
	 *
	 * @param	Integer	$month
	 * @param	Integer	$year
	 * @param	Boolean	$showDetails
	 */
	function __construct($month = 0, $year = 0, $showDetails = false)	{
		$this->month		= ($month > 0) ? $month : date('n');
		$this->year			= ($year > 0) ? $year : date('Y');
		$this->showDetails	= $showDetails;

		$this->user = userid();

		$this->loadCycleBorderDates();
		$this->loadTrackedTimeFromCycle();
		$this->prepareSelectorData();
	}



	/**
	 * Getter for the loaded Tracked Time
	 *
	 * @return	Array
	 */
	public function getLoadedTrackedTime()	{
		return $this->loadedTrackedTime;
	}



	/**
	 * Getter for the selector data
	 *
	 * @return	Array
	 */
	public function getSelectorData()	{
		return $this->selectorData;

	}



	/**
	 * Getter for the Month
	 *
	 * @return	Integer
	 */
	public function getMonth()	{
		return $this->month;
	}



	/**
	 * Getter for the Year
	 *
	 * @return	Integer
	 */
	public function getYear()	{
		return $this->year;
	}



	/**
	 * Getter for the user
	 *
	 * @return	Integer
	 */
	public function getUser()	{
		return $this->user;
	}



	/**
	 * Getter for the showDetails flag
	 *
	 * @return	Boolean
	 */
	public function getShowDetails()	{
		return $this->showDetails;
	}



	/**
	 * loads the border timestamps of the given month
	 *
	 */
	protected function loadCycleBorderDates()	{
		$start = mktime(0, 0, 0, $this->month, 1, $this->year);
		$end	= mktime(23, 59, 59, $this->month, date('t', $start), $this->year);

		$this->cycleBorderDates = array('start'	=> $start,
										'end'	=> $end);
	}



	/**
	 * loads all trackings of given user and date parameters from the database
	 *
	 */
	protected function loadTrackedTimeFromCycle()	{
		$fields	= 'tr.*';

		$tables	= 'ext_timetracking_track as tr';

		$where	= '	tr.date_create BETWEEN ' . $this->cycleBorderDates['start'] . ' AND ' . $this->cycleBorderDates['end'] . ' AND
					tr.id_user		= ' . $this->getUser() ;

		$order	= 'tr.date_create';

		if($this->getShowDetails() === true)	{
			$fields.= ', task.title';
			$tables.= ', ext_project_task as task';
			$where.=  ' AND task.id = tr.id_task';
		}

		$result = Todoyu::db()->doSelect($fields, $tables, $where, $group, $order);

		$this->handleTrackedTimeFromCycle($result);
	}



	/**
	 * Some modifications of the given db-records
	 *
	 * calculates the tracked time per day
	 * calculates the total tracked time of the month
	 *
	 * adds the details if set
	 *
	 * orders the entries
	 *
	 * @param	db-result $result
	 */
	protected function handleTrackedTimeFromCycle($result)	{
		while($track = Todoyu::db()->fetchAssoc($result))	{
			$this->loadedTrackedTime['entries'][date('d', $track['date_create'])]['trackedtime'] += $track['workload_tracked'];
			$this->loadedTrackedTime['entries'][date('d', $track['date_create'])]['date'] = $track['date_create'];
			$this->loadedTrackedTime['total'] += $track['workload_tracked'];

			if($this->getShowDetails() === true)	{
				$this->loadedTrackedTime['entries'][date('d', $track['date_create'])]['details'][] = array('title' => $track['title'],
																										   'trackedtime' => $track['workload_tracked']);
			}
		}

		if(is_array($this->loadedTrackedTime['entries']))	{
			sort($this->loadedTrackedTime['entries']);
		}

	}



	/**
	 * Gets the timetrackentry which is the earliest for the current user
	 *
	 * @return	Array
	 */
	protected function loadEarliestTrackedTimeFromUser()	{
		$fields = 'MIN(date_create) as earliest';

		$tables = 'ext_timetracking_track';

		$where = 'id_user = '. $this->getUser();


		return Todoyu::db()->getArray($fields, $tables, $where);
	}



	/**
	 * Creates the Selectors
	 *
	 * @param	Integer	$curMonth
	 * @param	Integer	$curYear
	 * @return	Array
	 */
	protected function prepareSelectorData()	{
		$yearSelector = array();
		$monthSelector = array();

		$earliest = $this->loadEarliestTrackedTimeFromUser();

		$earliest = date('Y', $earliest[0]['earliest']);

		$numOfYears = date('Y')-$earliest;

		for($i = 0; $i <= $numOfYears; $i++)	{
			$yearEntry = $earliest+$i;
			$yearSelector[$yearEntry] =  array('value' => $yearEntry,
												'selected' => $yearEntry==$this->getYear() ? true: false);
		}

		for($i = 1; $i <= 12; $i++)	{
			$monthSelector[$i] =  array('value' => strlen($i) == 1 ? '0'.$i:$i,
										'selected' => $i==$this->getMonth() ? true: false);
		}

		$this->selectorData = array('month' => $monthSelector, 'year' => $yearSelector);
	}


}
?>