<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012, snowflake productions GmbH, Switzerland
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
 * Manages the daytracks export
 *
 * @package		Todoyu
 * @subpackage	Daytracks
 */
class TodoyuDaytracksExportManager {

	/**
	 * Returns the configured export-filter-form
	 *
	 * @return	TodoyuForm	$form
	 */
	public static function getExportForm() {
		$xmlPath	= 'ext/daytracks/config/form/export.xml';

		$form		= new TodoyuForm($xmlPath);

		$form->setUseRecordID(false);

			// User is only allowed to export his own trackings
		if( !Todoyu::allowed('daytracks', 'daytracks:timeExportAllPerson') ) {
			$form->removeField('employee', true);
			$form->addHiddenField('employee', Todoyu::personid());
			$form->getField('employees')->setAttribute('comment', TodoyuContactPersonManager::getPerson(Todoyu::personid())->getFullName());
			$form->removeField('employerSelect', true);
		}

		if( Todoyu::allowed('daytracks', 'daytracks:timeExportAllEmployer') ) {
			$form->removeField('employerSelect', true);
			$form->getField('employerAC')->setName('employer');
		} else {
			$form->removeField('employerAC', true);
			$form->addHiddenField('employer', '');
		}

		return $form;
	}



	/**
	 * Exports the CSV file
	 *
	 * @todo add check for allowed projects / employers / companies
	 * @param	Array	$exportData
	 */
	public static function exportCSV(array $exportData) {
		if( Todoyu::allowed('daytracks', 'daytracks:timeExportAllPerson') ) {
			$employeeIDs	= TodoyuArray::intExplode(',', $exportData['employee'], true, true);
		} else {
			$employeeIDs	= array(Todoyu::personid());
		}

		$employer	= TodoyuArray::intExplode(',', $exportData['employers']);
		$project	= TodoyuArray::intExplode(',', $exportData['project']);
		$company	= TodoyuArray::intExplode(',', $exportData['company']);

		$reports	= self::getTrackingReport($employeeIDs, $employer, $project, $company, $exportData['date_start'], $exportData['date_end']);
		$reports	= self::prepareDataForExport($reports);

		$export		= new TodoyuExportCSV($reports);

		$export->download('daytracks_export_' . date('YmdHis') . '.csv');
	}



	/**
	 * Gets and prepares the data for the daytracks export
	 *
	 * @param	Array		$personIDs
	 * @param	Array		$employerIDs
	 * @param	Array		$projectIDs
	 * @param	Array		$companyIDs
	 * @param	Integer		$dateStart
	 * @param	Integer		$dateEnd
	 * @return	Array
	 */
	public static function getTrackingReport(array $personIDs = array(), array $employerIDs = array(), array $projectIDs = array(), array $companyIDs = array(), $dateStart = 0, $dateEnd = 0) {
		$personIDs		= TodoyuArray::intval($personIDs, true, true);
		$employerIDs	= TodoyuArray::intval($employerIDs, true, true);
		$projectIDs		= TodoyuArray::intval($projectIDs, true, true);
		$companyIDs		= TodoyuArray::intval($companyIDs, true, true);
		$dateStart		= intval($dateStart);
		$dateEnd		= intval($dateEnd);

		if( sizeof($projectIDs) > 0 ) {
			$companyIDs = array();
		}

		$fields	= '	CONCAT_WS(\'.\', ta.id_project, ta.tasknumber) as tasknumber,
					ta.title as task,
					DATE_FORMAT(FROM_UNIXTIME(tr.date_track), \'%Y-%m-%d\') as date_tracked,
					SEC_TO_TIME(tr.workload_tracked) as workload_tracked,
					SEC_TO_TIME(tr.workload_chargeable) as workload_chargeable,
					co.title as company,
					pr.title as project,
					CONCAT_WS(\', \', pe.lastname, pe.firstname) as name,
					tr.comment,
					a.title as activity';
		$tables	= '	ext_project_task ta,
					ext_project_project pr,
					ext_project_activity a,
					ext_contact_company co,
					ext_contact_person pe,
					ext_timetracking_track tr';
		$where	= '		ta.id_project		= pr.id'
				. ' AND ta.id_activity		= a.id'
				. '	AND pr.id_company		= co.id'
				. ' AND tr.id_task			= ta.id'
				. ' AND tr.id_person_create	= pe.id';
		$order	= '	tr.date_track ASC';

		if( sizeof($personIDs) > 0 ) {
			$where .= ' AND pe.id IN(' . implode(',', $personIDs) . ')';
		}

		if( sizeof($employerIDs) > 0 ) {
			$tables	.= ',ext_contact_mm_company_person pcmm';
			$where .= ' AND pe.id = pcmm.id_person AND pcmm.id_company IN(' . implode(',', $employerIDs) . ')';
		}

		if( sizeof($companyIDs) > 0 ) {
			$where .= ' AND co.id IN(' . implode(',', $companyIDs) . ')';
		}

		if( sizeof($projectIDs) > 0 ) {
			$where .= ' AND pr.id IN(' . implode(',', $projectIDs) . ')';
		}

		if( $dateStart > 0 ) {
			$dateStart	= TodoyuTime::getDayStart($dateStart);
			$where .= ' AND tr.date_track >= ' . $dateStart;
		}

		if( $dateEnd > 0 ) {
			$dateEnd	= TodoyuTime::getDayEnd($dateEnd);
			$where .= ' AND tr.date_track < ' . $dateEnd;
		}

		return Todoyu::db()->getArray($fields, $tables, $where, '', $order);
	}




	/**
	 * Returns the options for employers of current person
	 *
	 * @param	TodoyuFormElement	$field
	 * @return	Array
	 */
	public static function getEmployersOptions(TodoyuFormElement $field) {
		$companies	= TodoyuContactPersonManager::getPersonCompanyRecords(Todoyu::personid());
		$reformConfig	= array(
			'id'	=> 'value',
			'title'	=> 'label'
		);

		return TodoyuArray::reform($companies, $reformConfig, true);
	}



	/**
	 * Prepare data for export - substitute locale labels by their parsed values
	 *
	 * @param	Array	$dataArray
	 * @return	Array
	 */
	protected static function prepareDataForExport(array $dataArray) {
		$parsedArray	= array();

		foreach( $dataArray as $index => $report ) {
			$parsedArray[$index][Todoyu::Label('project.task.taskno')]						= $report['tasknumber'];
			$parsedArray[$index][Todoyu::Label('project.task.attr.title')]					= $report['task'];
			$parsedArray[$index][Todoyu::Label('timetracking.ext.attr.date_track')]			= $report['date_tracked'];
			$parsedArray[$index][Todoyu::Label('timetracking.ext.attr.workload_tracked')]	= $report['workload_tracked'];
			$parsedArray[$index][Todoyu::Label('timetracking.ext.attr.workload_chargeable')]= $report['workload_chargeable'];
			$parsedArray[$index][Todoyu::Label('contact.ext.company')]						= $report['company'];
			$parsedArray[$index][Todoyu::Label('project.ext.project')]						= $report['project'];
			$parsedArray[$index][Todoyu::Label('contact.ext.person')]						= $report['name'];
			$parsedArray[$index][Todoyu::Label('timetracking.ext.attr.comment')]			= $report['comment'];
			$parsedArray[$index][Todoyu::Label('project.ext.records.activity')]				= $report['activity'];
		}

		return $parsedArray;
	}
}
?>