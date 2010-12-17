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
 * Panel widget: daytracks
 *
 */
Todoyu.Ext.daytracks.PanelWidget.Daytracks = {

	/**
	 * Ext namespace shortcut
	 *
	 * @var	{Object}	ext
	 */
	ext:		Todoyu.Ext.daytracks,

	timeTask:	0,

	timeTotal:	0,

	spanTimeTask:	null,

	spanTimeTotal:	null,



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
		Todoyu.Ext.timetracking.addToggle('daytracks', this.onTrackingToggle.bind(this), this.onTrackingToggleUpdate.bind(this));
		Todoyu.Ext.timetracking.addTick(this.onTrackingClockUpdate.bind(this));
	},



	/**
	 * Register JS hooks of daytracks
	 */
	registerHooks: function() {
		Todoyu.Hook.add('project.task.statusUpdated', this.onTaskStatusUpdated.bind(this));
		Todoyu.Hook.add('project.quickTask.saved', this.onQuickTaskAdded.bind(this));
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
		var target = 'panelwidget-daytracks-content';
		var url = Todoyu.getUrl('daytracks', 'panelwidget');
		var options = {
			'parameters': {
				'action':	'update'
			},
			'onComplete': this.onRefreshed.bind(this)
		};

		// Update dayTracks list
		Todoyu.Ui.update(target, url, options);
	},



	/**
	 * onRefreshed dayTracks event handler
	 *
	 * @param	{Ajax.Response}		response
	 */
	onRefreshed: function(response) {
		this.ContextMenu.attach();
	},



	/**
	 * Toggle timeTracking of given task
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
	 * @param	{String}		status
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
	onTrackingToggle: function(idTask, start) {
		return false;
	},


	onTrackingToggleUpdate: function(idTask, data, response) {
		this.setContent(data);
	},


	/**
	 * Update content
	 *
	 * @param	{String}	html
	 */
	setContent: function(html) {
		$('panelwidget-daytracks-content').update(html);
		this.ContextMenu.attach();
	},



	/**
	 * Handle timetracking event: clock update
	 *
	 * @param	{Number}	idTask
	 * @param	{Number}	trackedTotal
	 */
	onTrackingClockUpdate: function(idTask, trackedTotal, trackedToday, trackedCurrent) {
		var taskTimeToday	= Todoyu.Time.timeFormatSeconds(trackedToday + trackedCurrent);
			// Update current task time
		if( $('daytracks-track-' + idTask + '-time') ) {
			$('daytracks-track-' + idTask + '-time').update(taskTimeToday);
		}

		var timeElements= $('panelwidget-daytracks-content').select('ul li a span.time');
		var timeToday	= 0;

			// Sum up all task trackings
		timeElements.each(function(element){
			timeToday += Todoyu.Time.parseTimeToSeconds(element.innerHTML);
		});

		var totalTimeToday	= Todoyu.Time.timeFormatSeconds(timeToday);
		$('daytracks-daytotal-time').update(totalTimeToday);
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