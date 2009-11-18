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

Todoyu.Ext.daytracks.History = {

	popup: null,
	
	popupID: 'daytracksHistory',

	showDetails: false,
	
	getPopup: function() {
		return Todoyu.Popup.getPopup(this.popupID);
	},

	show: function() {
		var url		= Todoyu.getUrl('daytracks', 'history');
		var options	= {
			'parameters': {
				'action': 'history'
			}
		};
		var idPopup	= 'popup-daytracks-history';
		var title	= '[LLL:daytracks.history.title]';

		this.popup	= Todoyu.Popup.openWindow(this.popupID, title, 420, 490, url, options);
	},


	/**
	 *	Update history
	 */
	update: function() {
		var date	= this.getSelectedDate();

		var url		= Todoyu.getUrl('daytracks', 'history');
		var options = {
			'parameters': {
				'action':	'history',
				'year':		date.year,
				'month':	date.month,
				'details':	this.showDetails ? 1 : 0
			}
		};

		Todoyu.Popup.updateContent(this.popupID, url, options);
	},


	getSelectedDate: function() {
		var range	= $F('daytracks-history-selector').split('-');

		return {
			'year': range[0],
			'month': range[1]
		};
	},


	/**
	 *	Toggle details
	 */
	toggleDetails: function()	{
		this.showDetails = !this.showDetails;
		this.update();
	},

	goToTask: function(idTask) {
		this.popup.close();

		Todoyu.goTo('project', 'ext', {task:idTask,tab:'timetracking'}, 'task-' + idTask + '-timetracking');
	}

};