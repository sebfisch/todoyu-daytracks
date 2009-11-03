Todoyu.Ext.daytracks.History = {
	
	popup: null,
	
	showDetails: false,
	
	show: function() {
		var url		= Todoyu.getUrl('daytracks', 'history');
		var options	= {
			'parameters': {
				'cmd': 'history'
			}
		};
		var idPopup	= 'popup-daytracks-history';
		var title	= '[LLL:daytracks.history.title]';

		this.popup	= Todoyu.Popup.openWindow(idPopup, title, 420, 490, url, options);
	},
	
	
	/**
	 *	Update history
	 */
	update: function() {
		var date	= this.getSelectedDate();
		
		var url		= Todoyu.getUrl('daytracks', 'history');
		var options = {
			'parameters': {
				'cmd':		'history',
				'year':		date.year,
				'month':	date.month,	
				'details':	this.showDetails ? 1 : 0
			}
		};

		Todoyu.Popup.updateContent(url, options);
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