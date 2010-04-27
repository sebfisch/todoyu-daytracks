/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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
 * Panel widget: daytracks
 *
*/
Todoyu.Ext.daytracks.PanelWidget.Daytracks = {

	/**
	 * Ext shortcut
	 *
	 * @var	{Object}	ext
	 */
	ext:		Todoyu.Ext.daytracks,

	_task:		0,

	_tasktime:	0,

	_totaltime:	0,

	_el_task:	null,

	_el_total:	null,



	/**
	 * Init daytrack panelWidget
	 */
	init: function() {
		this.registerTimetracking();
		this.registerHooks();
		this.ContextMenu.init();
	},



	/**
	 * Register to timetracking callbacks
	 */
	registerTimetracking: function() {
		Todoyu.Ext.timetracking.registerToggleCallback(this.onTimetrackingToggle.bind(this));
		Todoyu.Ext.timetracking.registerClockCallback(this.onTimetrackingClockUpdate.bind(this));
	},
	
	
	
	/**
	 * Register JS hooks of daytracks
	 */
	registerHooks: function() {
		Todoyu.Hook.add('taskStatusUpdated', this.onTaskStatusUpdated.bind(this));	
		Todoyu.Hook.add('QuickTaskSaved', this.onQuickTaskAdded.bind(this));	
	},



	/**
	 * Go to given task
	 *
	 * @param	{Number}	idProject
	 * @param	{Number}	idTask
	 */
	goToTask: function(idProject, idTask) {
		if( this.isTaskInCurrentView(idTask) ) {
			$('task-' + idTask).scrollToElement();
		} else {
			Todoyu.Ext.project.goToTaskInProject(idTask, idProject);
		}
	},


	/**
	 * Check whether task element exists within current view
	 * 
	 * @return	{Boolean}
	 */
	isTaskInCurrentView: function(idTask) {
		return Todoyu.exists('task-' + idTask);
	},



	/**
	 * Refresh widget
	 */
	refresh: function() {
		var target	= 'panelwidget-daytracks-content';
		var url		= Todoyu.getUrl('daytracks', 'panelwidget');
		var options	= {
			'parameters': {
				'action':	'update'
			},
			'onComplete': this.onRefreshed.bind(this)
		};

			// Update daytracks list
		Todoyu.Ui.update(target, url, options);
	},
	
	
	
	/**
	 * onRefreshed daytracks event handler
	 *
	 * @param	{Object}		response
	 */
	onRefreshed: function(response) {
		this.ContextMenu.attach();
	},



	/**
	 * Toggle timetracking of given task
	 *
	 * @param	{Number}	idTask
	 */
	toggleTimetracking: function(idTask) {
		Todoyu.Ext.timetracking.toggle(idTask);
	},
	
	
	
	/**
	 * Update task status
	 * 
	 * @param	{Number}		idTask
	 * @param	{String}		Status
	 */
	updateTaskStatus: function(idTask, status) {
		Todoyu.Ext.project.Task.updateStatus(idTask, status);
	},

	
	
	/**
	 * Handler when task status is updated and hook is called
	 * 
	 * @param	{Number}		idTask
	 * @param	{Number}		status
	 */
	onTaskStatusUpdated: function(idTask, status) {
		this.refresh();
	},
	
	

	/**
	 * Timetracking toggle-handler
	 *
	 * @param	{Number}		idTask
	 * @param	{Boolean}		start
	 */
	onTimetrackingToggle: function(idTask, start) {
		this._task = 0;

		this.refresh();
	},



	/**
	 * Handle timetracking event: clock update
	 *
	 * @param	{Number}	idTask
	 * @param	{Number}	time
	 */
	onTimetrackingClockUpdate: function(idTask, time) {
		this._el_task	= $('daytracks-track-' + idTask + '-time');

		if( this._task !== idTask ) {
			if (this._el_task) {
				this._el_total	= $('daytracks-daytotal-time');
				this._task 		= idTask;
				this._tasktime 	= Todoyu.Time.parseTimeToSeconds(this._el_task.innerHTML);
				this._totaltime = Todoyu.Time.parseTimeToSeconds(this._el_total.innerHTML) - time;
			}
		}

		if (this._el_task && this._el_total) {
			this._el_task.update( Todoyu.Time.timeFormatSeconds(this._tasktime + time) );
			this._el_total.update( Todoyu.Time.timeFormatSeconds(this._totaltime + time) );
		}
	},
	
	
	onQuickTaskAdded: function(idTask, idProject, response) {
		this.refresh();
	},



	/**
	 * Evoke showing of timetracking history
	 */
	showHistory: function() {
		this.ext.History.show();
	}

};