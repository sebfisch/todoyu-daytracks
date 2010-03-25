/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSC License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Context menu for task entries of daytracks panel widget
 *
*/
Todoyu.Ext.daytracks.PanelWidget.Daytracks.ContextMenu = {

	/**
	 *	Ext shortcut
	 */
	ext:	Todoyu.Ext.daytracks,

	widget:	Todoyu.Ext.daytracks.PanelWidget.Daytracks,



	/**
	 *	Register panel widget context menu
	 */
	init: function() {
		this.attach();
	},



	/**
	 *	Attach context menu
	 */
	attach: function() {
		Todoyu.ContextMenu.attachMenuToClass('contextmenudaytrackspwidget', this.load.bind(this));
	},



	/**
	 *	Detach context menu
	 */
	detach: function() {
		Todoyu.ContextMenu.detachAllMenus('contextmenudaytrackspwidget');
	},



	/**
	 * Load context menu
	 *
	 *	@param	Object	event
	 * 	@return	Boolean
	 */
	load: function(event) {
		var idTask	= event.findElement('li').id.split('-').last();

			// Prepare request parameters
		var url		= Todoyu.getUrl('daytracks', 'panelwidget');
		var options	= {
			'parameters': {
				'action':	'contextmenu',
				'task':		idTask
			}
		};

		Todoyu.ContextMenu.showMenu(url, options, event);

		return false;
	},



	/**
	 *	Attach context menu to given element
	 *
	 *	@param	String	element
	 */
	attachMenuToElement: function(element) {
		Todoyu.ContextMenu.attachMenuToElement($(element), this.show.bind(this));
	}


};