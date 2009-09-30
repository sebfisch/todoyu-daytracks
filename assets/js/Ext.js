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
 * Ext: daytracks
 */

Todoyu.Ext.daytracks = {

	PanelWidget: {},

	Headlet: {},

	showDetails: 0,



	/**
	 *	Show history
	 */
	showHistory: function() {
		var contentUrl = Todoyu.getUrl('daytracks', 'workloadhistory');
		contentUrl = contentUrl + '&cmd=render';

		var requestOptions	= {
			'parameters': {
//				'name':		'',
//				'value':	'',
			}
		};

		Todoyu.Popup.openWindow('popup-daytracks-workload-history', '[LLL:daytracks.workloadhistory.title]', 420, 490, 810, 200, contentUrl, requestOptions);
	},



	/**
	 *	Update history
	 */
	updateHistory: function()	{
		var month = $('workload-history-selector-month').getValue();
		var year = 	$('workload-history-selector-year').getValue();

		var url = Todoyu.getUrl('daytracks', 'workloadhistory');

		var options = {
			'parameters': {
				'cmd':		'render',
				'month':	month,
				'year':		year,
				'details':	this.showDetails
			}
		};

		Todoyu.Popup.updateContent(url, options);
	},



	/**
	 *	Toggle details
	 */
	toggleDetails: function()	{
		if(this.showDetails == true)	{
			this.showDetails = 0;
		} else {
			this.showDetails = 1;
		}

		this.updateHistory();
	}
};