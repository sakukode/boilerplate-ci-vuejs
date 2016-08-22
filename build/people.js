var Model = {
	PATH_MODULE: BASE_URL + "people/",
	getAll: function(searchText, by, sort, page, callback) {
		$.ajax({
			type: "get",
			url: this.PATH_MODULE + "get_all",
			data: {
				q: searchText,
				by: by,
				sort: sort,
				page: page
			},
			success: function(response) {			
				callback(JSON.parse(response));
			},
			error: function() {
				callback(false);
			}
		});
	},
	get: function(id, callback) {
		$.ajax({
			type: "get",
			url: this.PATH_MODULE + "get",
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
	},
	post: function(data, callback) {
		$.ajax({
			type: "post",
			url: this.PATH_MODULE + "insert",
			data: data,
			success: function(response) {
				//console.log(response);
				callback(JSON.parse(response));
			},
			error: function() {
				callback({status: false});
			}
		});
	},
	put: function(id, data, callback) {
		$.ajax({
			type: "post",
			url: this.PATH_MODULE + "update/" + id,
			data: data,
			success: function(response) {
				//console.log(response);
				callback(JSON.parse(response));
			},
			error: function() {
				callback({status: false});
			}
		});
	},
	remove: function(id, callback) {
		$.ajax({
			type: "post",
			url: this.PATH_MODULE + "delete",
			data: {
				id: id
			},
			success: function(response) {
				//console.log(response);
				callback(JSON.parse(response));
			},
			error: function() {
				callback({status: false});
			}
		});
	},
	importXls: function(data, callback) {
		$.ajax({
			url: this.PATH_MODULE + 'import_xls',
			type: 'POST',
			dataType: 'json',
			data: data,
			async: true,
			success: function(response) {
				callback(response);
			},
			error: function() {
				callback({status: false, 'message': 'Error Request'});
			},
			cache: false,
			contentType: false,
			processData: false
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
            sort: {}
        };

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
			MODULE: 'People',		
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
				{label: 'Firstname', sort: 'first_name'},
				{label: 'Lastname', sort: 'last_name'},
				{label: 'Gender', sort: 'gender'},
				{label: 'Email', sort: 'email'},
			],
			page: this.$route.query.page ? this.$route.query.page : 1,
			previousPage: '',
			nextPage: '',
			searchText: this.$route.query.q ? this.$route.query.q : '',
			loading: false			
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
			GridView.sort(by);
			//console.log(GridView.sortClass(by));
			var sortClass = GridView.sortClass(by);
			var sorting = GridView.getSorting();
			$('.sort').attr('class', 'sort');
			$('#sort-' + by).attr('class', sortClass);
			this.page = 1;

			router.go('/?q=' + this.searchText + '&by=' + by + '&sort=' + sorting.sort[by]);
			this.getAll();
		},
		deleteRow: function(event, model) {
			var self = this;
			var tr = $(event.target).parents('tr');

			//show alert warning before remove icon from cart
			swal({
				title: "Delete " + self.MODULE,
				text: 'Are you sure delete this ' + self.MODULE + ' "' + model.first_name + '"?',
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
					title: "Delete " + self.MODULE,
					text: 'Are you sure delete selected '+ self.MODULE +'?',
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

					self.setNotification('success', 'Success delete selected ' + self.MODULE);
					self.getAll();
					$('#check-all').attr('checked', false);
				});
			} else {
				self.setNotification('error', 'Not ' + self.MODULE + ' selected');
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
		selectFile: function(event) {
			event.preventDefault();
			$('#file-xls').trigger('click');
		},
		importXls: function(event) {
			var self = this;
			self.$set('loading', true);
			var formData = new FormData();
			formData.append("filexls", event.target.files[0]);

			Model.importXls(formData, function(result) {
				//self.$set('loading', false);
				if(result.status) {
					self.setNotification('success', result.message);
					self.getAll();
					self.$set('loading', false);
				} else {
					self.setNotification('error', result.message);
				}
			});
		}
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
			firstname: '',
			lastname: '',
			gender: '',
			email: '',
			action: '',
			label: 'Add',
			loading: false,
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
      		ruleGender: {
          		required: { rule: true, message: 'Gender is required' } 
      		},
		}
	},
	methods: {		
		saveAndBack: function(event) {
			var self = this;
			self.$set('loading', true);
			event.preventDefault();
			var data = {
				firstname: this.firstname,
				lastname: this.lastname,
				email: this.email,
				gender: this.gender
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
				firstname: this.firstname,
				lastname: this.lastname,
				email: this.email,
				gender: this.gender
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
						Model.put(id, data, function(result) {	
							self.$set('loading', false);					
							if(result.status) {										
								self.setNotification('success', result.message);					
							} else {			
								self.setNotification('error', result.message);
							}
						});
					}
		        }
		    });			
		},
		resetForm: function() {
			this.$set('id', '');
			this.$set('firstname', '');
			this.$set('lastname', '');
			this.$set('gender', '');
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
						self.firstname = result.first_name;
						self.lastname = result.last_name;
						self.gender = result.gender;
						self.email = result.email;	
					} else {
						router.go('/');
					}
				});

				self.$set('label', 'Update');
				self.$set('action','PUT');
			} else {
				self.$set('action','POST');
			}
		}
	},
	created: function() {		
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


