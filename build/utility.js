var Model = {
	PATH_MODULE: BASE_URL + "utility/",
	checkKey: function(key, callback) {
		$.ajax({
			type: "get",
			url: this.PATH_MODULE + "check_key_backup",
			data: {
				key: key
			},
			success: function(response) {
				//console.log(response);
				callback(JSON.parse(response));
			},
			error: function() {
				callback({status:false});
			}
		});
	},
	getTables: function(callback) {
		$.ajax({
			type: "get",
			url: this.PATH_MODULE + "get_tables",			
			success: function(response) {
				//console.log(response);
				callback(JSON.parse(response));
			},
			error: function() {
				callback(false);
			}
		});
	},
	backup: function(tables, callback) {
		$.ajax({
			type: "post",
			url: this.PATH_MODULE + "backup",	
			data: {
				tables: tables
			},		
			success: function(response) {
				//console.log(response);
				callback(JSON.parse(response));
			},
			error: function() {
				callback({status: false, message: 'Error Request'});
			}
		});	
	}
}


/**
 * =======================================
 */

Vue.component('notification', {
	props: ['showSuccess', 'successMsg', 'showError', 'errorMsg'],
	template: '#notification'
});

Vue.component('authBackup', {
	props: [],
	template: '#auth-backup',
	data: function() {
		return {
			key: ''
		}
	},
	methods: {
		submitKey: function(event) {
			event.preventDefault();
			var self = this;
			self.$dispatch('set-loading', true);
			Model.checkKey(this.key, function(result) {
				self.$dispatch('set-loading', false);
				if(result.status) {
					self.$dispatch('set-auth', true);
				} else {
					self.$dispatch('notification', 'error', result.message);
				}
			});
		}
	}
});

Vue.component('backup', {
	props: [],
	data: function() {
		return {
			tables: [],		
			doneBackup: false,
			pathDownload: ''
		}
	},
	template: '#backup',
	methods: {
		getTables: function() {
			var self = this;
			return Model.getTables(function(result) {
				self.$set('tables', result);
			});
		},
		checkAll: function(event) {
			var checkboxes = $('.tables');
	        for (var i = 0, n = checkboxes.length; i < n; i++) {
	            checkboxes[i].checked = event.target.checked;
	        }	
		},		
		backup: function(event) {
			event.preventDefault();
			this.$dispatch('set-loading', true);
			var self = this;
			var tables = [];
			var checkboxesTable = $('.tables');
			for (var i = 0; i < checkboxesTable.length; i++) {
				// And stick the checked ones onto an array...
				if (checkboxesTable[i].checked) {
					tables.push($(checkboxesTable[i]).val());
				}
			}

			Model.backup(tables, function(result) {
				self.$dispatch('set-loading', false);
				if(result.status) {
					self.doneBackup = true;
					self.pathDownload = result.path;
				} else {
					self.$dispatch('notification', 'error', result.message);
				}
			});
		},
		downloadFile: function(event) {
			event.preventDefault();
			window.location = this.pathDownload;   
			this.$dispatch('set-auth', false);
			this.$dispatch('notification', 'success', "Success Download File");
		}
	},
	created: function() {
		this.getTables();
	}
});


var App = new Vue({
	el: '#content',
	data: function() {
		return {
			MODULE:'Utility',
			auth: false,
			showNotifSuccess: false,
			showNotifError: false,
			successMessage: '',
			errorMessage: '',
			loading: false
		}
	},
	events: {
		'set-auth': function(val) {
			this.$set('auth', val);
		},
		'notification': function(type, msg) {	
			var self = this;
			this.$set('showNotifSuccess', false);
			this.$set('showNotifError', false);

			if(type === 'success') {
				this.$set('successMessage', msg);
				this.$set('showNotifSuccess', true);			
			} else {
				this.$set('errorMessage', msg);
				this.$set('showNotifError', true);			
			}

			setTimeout(function(){
				self.$set('showNotifSuccess', false);
				self.$set('showNotifError', false);
				self.$set('successMessage', '');
				self.$set('errorMessage', '');
			}, 3000);
		},
		'set-loading': function(val) {
			this.$set('loading', val);
		}
	}
});



