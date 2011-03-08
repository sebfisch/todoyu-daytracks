/****************************************************************************
 * todoyu is published under the BSD License:
 * http://www.opensource.org/licenses/bsd-license.php
 *
 * Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
 * @module	Export
 */

/**
 * Daytracks export
 *
 * @class		Export
 * @namespace	Todoyu.Ext
 */
Todoyu.Ext.daytracks.Export = {

	/**
	 *
	 */
	popup: {},



	/**
	 * Opens the Export - Popup
	 *
	 * @method	openExportPopup
	 */
	openExportPopup: function() {
		var url = Todoyu.getUrl('daytracks', 'export');
		var parameters = {
			'parameters': {
				'action': 'renderpopup'
			}
		};

		this.popup = Todoyu.Popups.open('time-export', '[LLL:daytracks.ext.export.popup.title]', 460, url, parameters);
		this.popup.show();
	},



	/**
	 * Closes the export - popup
	 *
	 * @method	closePopup
	 */
	closePopup: function() {
		this.popup.close();
	},



	/**
	 * sends download request.
	 *
	 * @method	download
	 * @param	{String}	form
	 */
	download: function(form) {
		if( this.verifyForm() ) {
			var formValues	= $(form).serialize(true);

			formValues.action = 'download';

			Todoyu.goTo('daytracks', 'export', formValues);
		} else {
			Todoyu.notifyError('[LLL:daytracks.ext.export.popup.error.allFieldsEmpty]');
		}
	},



	/**
	 * Checks if minimal one form field is filled in
	 *
	 * @method	verifyForm
	 * @return	{Boolean}
	 */
	verifyForm: function() {
		if( $F('export-field-employee') !== '' ) {
			return true;
		}
		if( $F('export-field-project') !== '' ) {
			return true;
		}
		if( $F('export-field-company') !== '' ) {
			return true;
		}
		if( $F('export-field-date-start') !== '' ) {
			return true;
		}

		return $F('export-field-date-end') !== '';
	},





	/**
	 * Class to handle the multi-auto-completer
	 */
	MultiAc: {

		/**
		 * Handles the person selection
		 *
		 * @method	onPersonSelected
		 * @param	inputField
		 * @param	idField
		 * @param	selectedValue
		 * @param	selectedText
		 * @param	autocompleter
		 * @return	{Boolean}
		 */
		onPersonSelected: function(inputField, idField, selectedValue, selectedText, autocompleter) {
			var container	= $('formElement-export-field-employees').down('span.commenttext');

			this.saveSelection(container, inputField, idField, selectedValue, selectedText);

			inputField.value = '';

			return false;
		},



		/**
		 * Handles the Project Selection
		 *
		 * @method	onProjectSelected
		 * @param	inputField
		 * @param	idField
		 * @param	selectedValue
		 * @param	selectedText
		 * @param	autocompleter
		 * @return	{Boolean}
		 */
		onProjectSelected: function(inputField, idField, selectedValue, selectedText, autocompleter) {
			var container	= $('formElement-export-field-projects').down('span.commenttext');

			this.saveSelection(container, inputField, idField, selectedValue, selectedText);

			inputField.value = '';

			return false;
		},



		/**
		 * Handles the Company selection from the auto-completer
		 *
		 * @method	onCompanySelected
		 * @param	inputField
		 * @param	idField
		 * @param	selectedValue
		 * @param	selectedText
		 * @param	autocompleter
		 * @return	{Boolean}
		 */
		onCompanySelected: function(inputField, idField, selectedValue, selectedText, autocompleter) {
			var container	= $('formElement-export-field-companies').down('span.commenttext');

			this.saveSelection(container, inputField, idField, selectedValue, selectedText);

			inputField.value = '';

			return false;
		},



		/**
		 * Handles the employer selection from the auto-completer
		 *
		 * @method	onEmployerSelected
		 * @param	inputField
		 * @param	idField
		 * @param	selectedValue
		 * @param	selectedText
		 * @param	autocompleter
		 * @return	{Boolean}
		 */
		onEmployerSelected: function(inputField, idField, selectedValue, selectedText, autocompleter) {
			var container	= $('formElement-export-field-employers').down('span.commenttext');

			this.saveSelection(container, inputField, idField, selectedValue, selectedText);

			inputField.value = '';

			return false;
		},



		/**
		 * Handles the employer selection from the select-input
		 *
		 * @method	onEmployerSelectedSelect
		 * @return	{Boolean}
		 */
		onEmployerSelectedSelect: function() {
			var select = $('formElement-export-field-employer').down('select');

			if( select.value > 0 ) {
				return this.onEmployerSelected(select,$('export-field-employers'), select.value, select.options[select.selectedIndex].text);
			}
		},



		/**
		 * Saves the selection fo any auto-completer
		 *
		 * @method	saveSelection
		 * @param	{Element}	container
		 * @param	{Element}	inputField
		 * @param	{Element}	idField
		 * @param	{String}	selectedValue
		 * @param	{String}	selectedText
		 */
		saveSelection: function(container, inputField, idField, selectedValue, selectedText) {
			var ul	= this.getList(container, idField);

			this.addListItem(ul, idField.id, selectedValue, selectedText);
			this.updateValueField(idField, ul);
		},



		/**
		 * Returns the list of selected elements
		 *
		 * @method	getList
		 * @param	container
		 * @param	idField
		 */
		getList: function(container, idField) {
			if( ! container.down('ul') ) {
				var list = new Element('ul', {
					'class': 'itemlist'
				});

				list.observe('click', this.onListClick.bindAsEventListener(this, idField));

				container.insert(list);
			}

			return container.down('ul');
		},



		/**
		 * Handles the list click (removal of selected items)
		 *
		 * @method	onListClick
		 * @param	event
		 * @param	idField
		 */
		onListClick: function(event, idField) {
			var li	= event.findElement('li');

			if( li !== document ) {
				this.removeItem(idField, li);
			}
		},



		/**
		 * Adds an item to the selection list
		 *
		 * @method	addListItem
		 * @param	container
		 * @param	baseName
		 * @param	idElement
		 * @param	textElement
		 */
		addListItem: function(container, baseName, idElement, textElement) {
			var item = (new Element('li', {
					id: baseName + '-item-' + idElement
			})).update(textElement);

			container.insert({
				bottom: item
			});
		},



		/**
		 * Updates the (hidden) value field
		 *
		 * @method	updateValueField
		 * @param	{Element}	field
		 * @param	{Element}	list
		 */
		updateValueField: function(field, list) {
			field.value = list.select('li').collect(function(item){
				return item.id.split('-').last();
			});
		},



		/**
		 * Removes the selection-element from the selection-list
		 *
		 * @method	removeItem
		 * @param	{String}	idField
		 * @param	{Element}	listItem
		 */
		removeItem: function(idField, listItem) {
			var list = listItem.up('ul');
			var value= listItem.id.split('-').last();

				// Remove from value field
			idField.value = idField.value.split(',').without(value).join(',');
				// Remove from list
			listItem.remove();
		}

	}

};