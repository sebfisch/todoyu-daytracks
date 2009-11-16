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
 * Context menu for task entries of daytracks panel widget
 *
*/
Todoyu.Ext.daytracks.PanelWidget.Daytracks.ContextMenu = {

	ext: Todoyu.Ext.daytracks,
	
	widget: Todoyu.Ext.daytracks.PanelWidget.Daytracks,



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
	 *	Show context menu
	 */
	show: function(event) {
		var li 		= Event.findElement(event, 'li');
		var idParts	= li.id.split('-');
		var idTask	= Todoyu.Helper.intval(idParts[2]);
		var url		= Todoyu.getUrl('daytracks', 'panelwidgetcontextmenu');
		var options	= {
			'parameters': {
				'task': idTask
			},
			'asynchronous': false
		};

			// Load context menu asynchronous to make sure the menu is loaded
		Todoyu.Ui.updateContextMenu(url, options);

		Todoyu.ContextMenu.renderMenu(event);

		event.stop();

		return false;
	},
	
	
	load: function(event) {
		var idTask	= event.findElement('li').id.split('-').last();

			// Prepare request parameters
		var url		= Todoyu.getUrl('daytracks', 'panelwidget');
		var options	= {
			'parameters': {
				'action': 'contextmenu',
				'task': idTask
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