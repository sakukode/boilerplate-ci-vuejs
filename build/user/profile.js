var Model = {
	PATH_MODUL: BASE_URL + "user/",
	getUserLogin: function(callback) {
		$.ajax({
			type: "get",
			url: this.PATH_MODUL + "get_user_login",
			data: {
			
			},
			success: function(response) {
				//console.log(response);
				callback(JSON.parse(response));
			},
			error: function() {
				callback({status: false, message: 'Error System'});
			}
		});
	},
	updateProfile: function(data, callback) {
		$.ajax({
			type: "post",
			url: this.PATH_MODUL + "update_profile",
			data: data,
			success: function(response) {
				//console.log(response);
				callback(JSON.parse(response));
			},
			error: function() {
				callback(false);
			}
		});	
	},
	updatePassword: function(data, callback) {
		$.ajax({
			type: "post",
			url: this.PATH_MODUL + "update_password",
			data: data,
			success: function(response) {
				//console.log(response);
				callback(JSON.parse(response));
			},
			error: function() {
				callback(false);
			}
		});	
	}
};

var router = new VueRouter({
	linkActiveClass: 'active'
});

// define
var Notification = Vue.extend({
  props: ['showSuccess', 'successMessage', 'showError', 'errorMessage'],
  template: '#notification', 
});

// register
Vue.component('notification', Notification);

Vue.mixin({
	data: function() {
		return {			
			showNotifSuccess: false,
			successMessage: '',
			showNotifError: false,
			errorMessage: '',	
			user: {}	
		}
	},
	methods: {
		setNotification: function(type, msg) {	
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
		get: function() {
			var self = this;
			Model.getUserLogin(function(result) {
				if(result) {
					self.$set('user',result);
				}
			});
		},
		setHeaderProfile: function () {
	      var self = this;
	      Model.getUserLogin(function(user) {
	      	var fullname = user.firstname + " " + user.lastname;
	        self.$dispatch('header-profile', fullname, user.group);
	      });	      
	    }
	}
});

var App = Vue.extend({
	data: function(){
		return {
	    	fullname: '',
	    	group: ''
		}
	},
	created: function() {
		this.get();	
	},
	events: {
	    'header-profile': function (fullname, group) {
	      // `this` in event callbacks are automatically bound
	      // to the instance that registered it
	      this.fullname = fullname;
	      this.group = group;
	    }
	  }
});

/** Component Profile **/
var cProfile = Vue.extend({
	template: '#profile',
	data: function() {
		return {
			
		}
	},
	created: function() {
		this.get();		
		this.setHeaderProfile();
	},
});
/** Eof Component Profile **/

/** Component Form Change Profile **/
var cFormChangeProfile = Vue.extend({
	template: '#form-change-profile',
	data: function() {
		return {	
			firstname: '',
			lastname: ''		
		}
	},
	methods: {
		changeProfile: function(event) {
			var self = this;
			event.preventDefault();
			var data = {
				firstname: this.firstname,
				lastname: this.lastname
			};

			this.$validate(function () {
		        if (self.$validationChangeProfile.invalid) {
		          event.preventDefault();			          		       		 
		        } else {		        	
		        	Model.updateProfile(data, function(result) {
		        		if(result.status) {
		        			self.setNotification('success', result.message);
		        			self.setHeaderProfile();
		        		} else {
		        			self.setNotification('error', result.message);
		        		}
		        	});		         
		        }
		    });
		}
	},
	created: function() {
		this.get();	
		this.setHeaderProfile();			
	},
});
/** Eof Component Form Change Profile **/

/** Component Form Change Profile **/
var cFormChangePassword = Vue.extend({
	template: '#form-change-password',
	data: function() {
		return {
			oldPassword: '',
			newPassword:'',
			confirmNewPassword: '',
			rulesOld: {
          		required: { rule: true, message: 'required you old password' } 
      		},
			rulesNew: {
	          required: { rule: true, message: 'required you new password' },
	          minlength: { rule: 6, message: 'your new password short too. min 6 character' }
	        },			
		}
	},
	validators: {
	    confirm: function (val, target) {
	      return val === target
	    }
	},
	methods: {
		changePassword: function(event) {
			var self = this;
			event.preventDefault();
			var data = {
				old: this.oldPassword,
				new: this.newPassword,
				confirm: this.confirmNewPassword
			};

			this.$validate(function () {
		        if (self.$validationChangePassword.invalid) {
		          event.preventDefault();			          		       		 
		        } else {		        	
		        	Model.updatePassword(data, function(result) {
		        		if(result.status) {
		        			self.setNotification('success', result.message);
		        			self.oldPassword = '';
		        			self.newPassword = '';
		        			self.confirmNewPassword = '';
		        		} else {
		        			self.setNotification('error', result.message);
		        		}
		        	});	         
		        }
		    });
		}
	},
	created: function() {
		this.setHeaderProfile();
	},
});
/** Eof Component Form Change Profile **/


router.map({
	"/detail": {
		component: cProfile
	},	
	"/change": {
		component: cFormChangeProfile
	},
	"/change-password": {
		component: cFormChangePassword
	}
});

router.redirect({
  // redirect any not-found route to home
  '*': '/detail',
  '/': '/detail',
})

router.start(App, "#app");


