var Model = {
	PATH_MODUL: BASE_URL + "user/",
	getAll: function(searchText, by, sort, page, callback) {
		$.ajax({
			type: "get",
			url: this.PATH_MODUL + "get_all",
			data: {
				q: searchText,
				by: by,
				sort: sort,
				page: page
			},
			success: function(response) {
				//console.log(response);
				callback(JSON.parse(response));
			},
			error: function() {

			}
		});
	},
	get: function(id, callback) {
		$.ajax({
			type: "get",
			url: this.PATH_MODUL + "get",
			data: {
				id: id
			},
			success: function(response) {
				//console.log(response);
				if(response) {
					callback(JSON.parse(response));
				} else {
					callback(false);
				}
			},
			error: function() {
				callback(false);
			}
		});
	},
	getGroups: function(callback) {
		$.ajax({
			type: "get",
			url: this.PATH_MODUL + "get_groups",
			data: {},
			success: function(response) {
				//console.log(response);
				if(response) {
					callback(JSON.parse(response));
				} else {
					callback(false);
				}
			},
			error: function() {
				callback(false);
			}
		});
	},
	post: function(data, callback) {
		$.ajax({
			type: "post",
			url: this.PATH_MODUL + "insert",
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
	remove: function(id, callback) {
		$.ajax({
			type: "post",
			url: this.PATH_MODUL + "delete",
			data: {
				id: id
			},
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

var GridView = {
	sorting: {
        sort: {
            'id': 'DESC'
        }
    },
    sort: function(by, order) {      
        var sortBy = this.sorting.sort[by];
        this.sorting = {
            sort: {
//                by: sortBy,
            }};

        if(order) {
        	this.setSorting(by, order);
        } else {
	        if (sortBy == 'ASC')
	            this.setSorting(by, 'DESC');
	        else
	            this.setSorting(by, 'ASC');
    	}
    },
    getSorting: function() {
        return this.sorting;
    },
    getSort: function() {
        return this.sorting.sort;
    },
    setSorting: function(by, order) {
        //if there is sort by in array then change the order value
        this.sorting.sort[by] = order;       
    },
    //used in index js to know current sort order
    getSortOrder: function(by) {    
        return this.sorting.sort[by];
    },
    sortClass: function(by) {
        if(typeof this.getSortOrder(by) == 'undefined') {
        	return '';
        } else {
        	if(this.getSortOrder(by) == 'ASC') {
	        	return 'sort fa fa-caret-up';
	        } else {
	        	return 'sort fa fa-caret-down';
	        }
        }
    }
}

var router = new VueRouter({});
Vue.mixin({
	data: function() {
		return {	
			module: 'User',		
			showNotifSuccess: false,
			successMessage: '',
			showNotifError: false,
			errorMessage: '',		
		}
	},
	methods: {
		setNotification: function (type, msg) {			
			var self = this;
			self.$dispatch('notification', type, msg);		
		},		
	}
});
Vue.validator('email', function (val) {
  return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(val)
})

var App = Vue.extend({	
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
	}
});

// define
var Notification = Vue.extend({
  props: ['showSuccess', 'successMsg', 'showError', 'errorMsg'],
  template: '#notification', 
});

// register
Vue.component('notification', Notification);

/** Component List Model **/
var cList = Vue.extend({
	template: '#list',	
	data: function() {
		return {
			models: [],	
			pages: [],
			tableHeader: [
				{label: 'ID', sort: 'id'},
				{label: 'Email', sort: 'email'},
				{label: 'Firstname', sort: 'first_name'},
				{label: 'Lastname', sort: 'last_name'},				
				{label: 'Group', sort: false},
			],
			page: this.$route.query.page ? this.$route.query.page : 1,
			previousPage: '',
			nextPage: '',
			searchText: this.$route.query.q ? this.$route.query.q : '',			
		}
	},
	computed: {
		
	},
	methods: {				
		getAll: function() {	
			var self = this;
			var search = this.searchText;			

			var sorting = GridView.getSort();
			var page = this.page;
			var by = Object.keys(sorting);			
			by = by[0];
			sort = sorting[by];

	        Model.getAll(search, by, sort, page, function(result) {
	        	self.models = [];
	        	// console.log(result.data);
	        	self.pages = result.pages;
	        	self.previousPage = (parseInt(result.currentPage) - 1) > 0 ? (parseInt(result.currentPage) - 1) : false ;
	        	self.nextPage = (parseInt(result.currentPage) + 1) <= result.numberOfPages ? (parseInt(result.currentPage) + 1) : false;
	        	self.models = result.data;	        	
	        });
		},
		search: function(event) {
			this.page = 1;
			router.go('/?q=' + this.searchText);
			this.getAll();	  		
		},	
		paging: function(page, event) {
			event.preventDefault();
			var searchText = this.searchText;			
			var sorting = GridView.getSort();

			var by = Object.keys(sorting);			
			by = by[0];
			sort = sorting[by];
			this.page = page;

			router.go('/?q=' + this.searchText + '&by=' + by + '&sort=' + sort + '&page=' + page);
			this.getAll();
		},		
		sortBy: function(by) {
			if(by) {
				GridView.sort(by);
				//console.log(GridView.sortClass(by));
				var sortClass = GridView.sortClass(by);
				var sorting = GridView.getSorting();
				$('.sort').attr('class', 'sort');
				$('#sort-' + by).attr('class', sortClass);
				this.page = 1;

				router.go('/?q=' + this.searchText + '&by=' + by + '&sort=' + sorting.sort[by]);
				this.getAll();
			}
		},
		deleteRow: function(event, model) {
			var self = this;
			var tr = $(event.target).parents('tr');

			//show alert warning before remove icon from cart
			swal({
				title: "Delete " + self.module,
				text: 'Are you sure delete this ' + self.module + ' "'  + model.first_name + '"?',
				type: "warning",
				showCancelButton: true,
				closeOnConfirm: true,
				showLoaderOnConfirm: true
			}, function() {			
				tr.animate({opacity: 0.3});

				setTimeout(function(){
				Model.remove(model.id, function(result) {
					if(result.status) {
						tr.hide();
						self.setNotification('success', result.message);
					} else {
						tr.animate({opacity: 1})
					}
				});
				}, 1000);
			});
		},
		deleteRows: function(event) {
			var rows = [];
			var self = this;
			var checkboxes = $('.checkbox-id');
			for (var i = 0; i < checkboxes.length; i++) {
				// And stick the checked ones onto an array...
				if (checkboxes[i].checked) {
					rows.push({
						id: $(checkboxes[i]).val(),
						tr: $(checkboxes[i]).parents('tr') 
					});
				}
			}

			if(rows.length > 0) {
				//show alert warning before remove icon from cart
				swal({
					title: "Delete " + self.module,
					text: 'Are you sure delete selected '+ self.module +'?',
					type: "warning",
					showCancelButton: true,
					closeOnConfirm: true,
					showLoaderOnConfirm: true
				}, function() {			
					for (var i = 0; i < rows.length; i++) {
						var id = rows[i].id;
						var tr = rows[i].tr;
						tr.animate({opacity: 0.3});
						
						Model.remove(id, function(result) {
							
						});
					}

					self.setNotification('success', 'Success delete selected ' + self.module);
					self.getAll();
					$('#check-all').attr('checked', false);
				});
			} else {
				self.setNotification('error', 'Not ' + self.module + ' selected');
			}
		},		
		checkAll: function(event) {
			var checkboxes = $('.checkbox-id');
	        for (var i = 0, n = checkboxes.length; i < n; i++) {
	            checkboxes[i].checked = event.target.checked;
	        }			
		},
		setSortDefault: function() {
			var by = 'id';
			var order = 'DESC';
			var sortClass = GridView.sort(by, order);
		},		
	},
	created: function() {
		this.setSortDefault();
		this.getAll();
	}
});
/** Eof Component List Model **/

/** Component Detail Model **/
var cView = Vue.extend({
	template: '#view',
	data: function() {
		return {
			model: {}
		}
	},
	methods: {		
		get: function() {
			var self = this;
			var id = this.$route.params.id;

			if(id) {
				Model.get(id, function(result) {
					if(result) {
						self.model = result;
					} else {
						router.go('/');
					}
				});
			}
		}
	},
	created: function() {
		this.get();		
	},
});
/** Eof Component Detail Model **/

/** Component Form Model **/
var cForm = Vue.extend({
	template: '#form',	
	data: function() {
		return {
			id: '',
			username: '',
			firstname: '',
			lastname: '',
			email: '',
			group: '',
			groups: [],
			phone: '',
			action: '',
			label: 'Add',
			loading: false,
			//rules validation
			ruleUsername: {
          		required: { rule: true, message: 'Username is required' } 
      		},
			ruleFirstname: {
          		required: { rule: true, message: 'Firstname is required' } 
      		},
      		ruleLastname: {
          		required: { rule: true, message: 'Lastname is required' } 
      		},
      		ruleEmail: {
          		required: { rule: true, message: 'Email is required' } ,
          		email: { rule: true, message: 'Email should be valid address' } 
      		},
      		ruleGroup: {
          		required: { rule: true, message: 'Group is required' } 
      		},
		}
	},
	methods: {		
		saveAndBack: function(event) {
			var self = this;
			self.$set('loading', true);
			event.preventDefault();
			var data = {
				username: this.username,
				firstname: this.firstname,
				lastname: this.lastname,
				email: this.email,
				group: this.group,
				phone: this.phone
			};
			var action = this.action;
			var id = this.id;

			this.$validate(function () {
		        if (self.$validation.invalid) {
		          event.preventDefault();	
		          self.$set('loading', false);	  
		        } else {	
					if(action === 'POST') {
						Model.post(data, function(result) {
							self.$set('loading', false);
							if(result.status) {					
								router.go('/?by=id&sort=DESC');
								self.setNotification('success', result.message);					
							} else {			
								self.setNotification('error', result.message);
							}
						});
					} else {
						Model.put(id, data, function(result) {
							self.$set('loading', false);
							if(result.status) {					
								router.go('/?by=id&sort=DESC');
								self.setNotification('success', result.message);					
							} else {			
								self.setNotification('error', result.message);
							}
						});
					}
				}
		    });	
		},
		save: function(event) {
			var self = this;
			self.$set('loading', true);
			event.preventDefault();
			var data = {
				username: this.username,
				firstname: this.firstname,
				lastname: this.lastname,
				email: this.email,
				group: this.group,
				phone: this.phone
			};
			var action = this.action;
			var id = this.id;

			this.$validate(function () {
		        if (self.$validation.invalid) {
		          event.preventDefault();	
		          self.$set('loading', false);	  
		        } else {		        			        
		        	if(action === 'POST') {
						Model.post(data, function(result) {	
							self.$set('loading', false);						
							if(result.status) {					
								self.resetForm();								
								self.setNotification('success', result.message);					
							} else {									
								self.setNotification('error', result.message);
							}
						});
					} else {
						// Model.put(id, data, function(result) {	
						// 	self.$set('loading', false);					
						// 	if(result.status) {										
						// 		self.setNotification('success', result.message);					
						// 	} else {			
						// 		self.setNotification('error', result.message);
						// 	}
						// });
					}
		        }
		    });			
		},
		resetForm: function() {
			this.$set('id', '');
			this.$set('username', '');
			this.$set('firstname', '');
			this.$set('lastname', '');
			this.$set('group', '');
			this.$set('phone', '');
			this.$set('email', '');

			this.$resetValidation();
		},
		setForm: function() {
			var self = this;			
			var id = this.$route.params.id ? this.$route.params.id : null;

			if(id) {
				Model.get(id, function(result) {
					if(result) {
						self.id = result.id;
						self.email = result.email;	
						self.firstname = result.first_name;
						self.lastname = result.last_name;
						self.group = result.group;						
					} else {
						router.go('/');
					}
				});

				self.$set('label', 'Update');
				self.$set('action','PUT');
			} else {
				self.$set('action','POST');
			}
		},
		getGroups: function() {
			var self = this;
			Model.getGroups(function(result){
				if(result) {
					self.groups = result;
				}
			});
		}
	},
	created: function() {	
		this.getGroups();	
		this.setForm();
	}
});
/** Eof Component Form Model **/

router.map({
	"/": {
		component: cList
	},
	"/view/:id": {
		name: 'pathView',
		component: cView
	},	
	"/add": {
		component: cForm
	},
	"/update/:id": {
		name: 'pathUpdate',
		component: cForm
	},
});

router.redirect({
  // redirect any not-found route to home
  '*': '/'
})

router.start(App, "#content");


