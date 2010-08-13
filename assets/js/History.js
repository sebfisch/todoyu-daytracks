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

	popup:			null,

	popupID:		'daytracks-history',

	showDetails:	false,


	/**
	 * Get popUp
	 *
	 * @teturn	Object
	 */
	getPopup: function() {
		return Todoyu.Popup.getPopup(this.popupID);
	},



	/**
	 * Display daytracks history popUp
	 */
	show: function() {
		var url		= Todoyu.getUrl('daytracks', 'history');
		var options	= {
			'parameters': {
				'action':	'history'
			}
		};
		var idPopup	= 'popup-daytracks-history';
		var title	= '[LLL:daytracks.history.title]';

		this.popup	= Todoyu.Popup.openWindow(this.popupID, title, 500, url, options);
	},



	/**
	 * Update shown history
	 */
	update: function() {
		var range	= $F('daytracks-history-selector').split('-');

		var url		= Todoyu.getUrl('daytracks', 'history');
		var options = {
			'parameters': {
				'action':	'history',
				'year':		range[0],
				'month':	range[1],
				'details':	( this.showDetails ) ? 1 : 0
			}
		};

		Todoyu.Popup.updateContent(this.popupID, url, options);
	},



	/**
	 * Toggle display of details of historic timetracks 
	 */
	toggleDetails: function()	{
		this.showDetails = ! this.showDetails;
		this.update();
	}

};