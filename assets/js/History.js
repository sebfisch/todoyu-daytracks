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
				'action':	'history'
			}
		};
		var idPopup	= 'popup-daytracks-history';
		var title	= '[LLL:daytracks.history.title]';

		this.popup	= Todoyu.Popup.openWindow(this.popupID, title, 420, url, options);
	},


	/**
	 * Update history
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
	 * Toggle details
	 */
	toggleDetails: function()	{
		this.showDetails = !this.showDetails;
		this.update();
	}

};