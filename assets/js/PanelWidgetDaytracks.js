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
 * Panel widget: daytracks
 *
*/
Todoyu.Ext.daytracks.PanelWidget.Daytracks = {

	ext:		Todoyu.Ext.daytracks,

	_task: 0,

	_tasktime: 0,

	_totaltime: 0,

	_el_task: null,

	_el_total: null,


	init: function() {
		this.registerTimetracking();
		this.ContextMenu.init();
	},


	/**
	 *	Register to timetracking callbacks
	 */
	registerTimetracking: function() {
		Todoyu.Ext.timetracking.registerToggleCallback(this.onTimetrackingToggle.bind(this));
		Todoyu.Ext.timetracking.registerClockCallback(this.onTimetrackingClockUpdate.bind(this));
	},



	/**
	 *	Show task in(side its project) in project area
	 */
	showInProject: function(idTask, idProject) {

		Todoyu.Ext.project.goToTaskInProject(idTask, idProject);
	},



	/**
	 *	Go to given task
	 *
	 *	@param	Integer	idProject
	 *	@param	Integer	idTask
	 */
	goToTask: function(idProject, idTask) {
		if( this.isTaskInCurrentView(idTask) ) {
			$('task-' + idTask).scrollToElement();
		} else {
			Todoyu.Ext.project.goToTaskInProject(idTask, idProject);
		}
	},

	isTaskInCurrentView: function(idTask) {
		return Todoyu.exists('task-' + idTask);
	},




	/**
	 *	Update task status
	 */
	updateTaskStatus: Todoyu.Ext.project.Task.updateStatus,


	/**
	 *	Refresh widget
	 */
	refresh: function() {
		var target	= 'panelwidget-daytracks-content';
		var url		= Todoyu.getUrl('daytracks', 'panelwidget');
		var options	= {
			'parameters': {
				'cmd': 'update'
			}
		};

			// Update daytracks list
		Todoyu.Ui.update(target, url, options);
	},



	/**
	 *	Toggle timetracking of given task
	 *
	 *	@param	Integer	idTask
	 */
	toggleTimetracking: function(idTask) {
		Todoyu.Ext.timetracking.toggle(idTask);
	},



	/**
	 *	Timetracking toggle-handler
	 *
	 *	@param	Integer	idTask
	 *	@param	unknown	start
	 */
	onTimetrackingToggle: function(idTask, start) {
		this._task = 0;

		this.refresh();
	},



	/**
	 *	Handle timetracking event: clock update
	 *
	 *	@param	Integer	idTask
	 *	@param	Integer	time
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
	}


};