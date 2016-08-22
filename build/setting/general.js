/** MODEL **/
var Model = {
	PATH_MODULE: BASE_URL + "setting/",
	getGeneral: function(callback) {
		$.ajax({
			type: "get",
			url: this.PATH_MODULE + "get_general",			
			success: function(response) {
				//console.log(response);
				callback(JSON.parse(response));
			},
			error: function() {
				callback(false);
			}
		});
	},	
	saveGeneral: function(data, callback) {
		$.ajax({
			type: "post",
			url: this.PATH_MODULE + "save_general",	
			data: data,		
			success: function(response) {
				//console.log(response);
				callback(JSON.parse(response));
			},
			error: function() {
				callback({status: false, message: 'Error Request'});
			}
		});	
	}
};
/** EOF MODEL **/


Vue.component('notification', {
	props: ['showSuccess', 'successMsg', 'showError', 'errorMsg'],
	template: '#notification'
});

Vue.component('formGeneral', {
	template: '#form-general',
	data: function() {
		return {
			sitename: '',
			address: '',
			phone: '',
			perpage: 0,
			ruleSiteName: {
          		required: { rule: true, message: 'Site Name is required' } 
      		},
      		rulePerPage: {
          		required: { rule: true, message: 'Data Per Page is required' } 
      		},
		}
	},
	methods: {
		save: function(event) {
			event.preventDefault();
			var self = this;
			var data = {
				sitename: this.sitename,
				address: this.address,
				phone: this.phone,
				perpage: this.perpage
			};

			this.$validate(function () {
		        if (self.$validation.invalid) {
		          event.preventDefault();			          
		        } else {	
					Model.saveGeneral(data, function(result) {
						if(result.status) {
							self.$dispatch('notification', 'success', result.message);
							self.get();
						} else {
							self.$dispatch('notification', 'error', result.message);
						}
					});
				}
		    });	
		},
		get: function() {
			var self = this;
			Model.getGeneral(function(result) {
				if(result) {
					self.sitename = result.sitename;
					self.address = result.address;
					self.phone = result.phone;
					self.perpage = parseInt(result.perpage);
				}
			});
		}
	},
	created: function() {
		this.get();
	}
});

var App = new Vue({
	el: '#content',
	data: function() {
		return {
			MODULE:'Setting',
			auth: false,
			showNotifSuccess: false,
			showNotifError: false,
			successMessage: '',
			errorMessage: '',
			loading: false
		}
	},
	events: {		
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