 <!-- Content App -->
 <div id="content">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
  {{MODULE}}
  <small>module</small>
  </h1>
  
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box" style="min-height:512px">
        <notification
        v-bind:show-success="showNotifSuccess"
        v-bind:success-msg="successMessage"
        v-bind:show-error="showNotifError"
        v-bind:error-msg="errorMessage">
        </notification>
        <router-view></router-view>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>
  
</section>
<!-- /.content -->
</div>
<!-- Eof Content App -->

    <template id="list">       
     
      <div class="box-header">                   
        <a v-link="{path: '/add'}" class="btn btn-primary"><i class="fa  fa-plus"></i> Add {{module}}</a>
        <button v-on:click="deleteRows" class="btn btn-danger"><i class="fa  fa-trash"></i> Remove Selected</button>
        <div class="box-tools">
          <div class="input-group input-group-sm" style="width: 150px;">
            <input v-on:keyup.enter="search" v-model="searchText" type="text" name="table_search" class="form-control pull-right" placeholder="Search">
            <div class="input-group-btn">
              <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
          <tr>
            <th><input type="checkbox" id="check-all" v-on:change="checkAll"></th>
            <th v-for="thead in tableHeader" v-on:click="sortBy(thead.sort)"><i id="sort-{{thead.sort}}" class="sort"></i> {{thead.label}}</th>            
            <th>Action</th>
          </tr>
          <tr v-for="row in models" transition="item">
            <td><input type="checkbox" class="checkbox-id" v-bind:value="row.id" /></td>
            <td>{{row.id}}</td>
            <td>{{row.name}}</td>      
            <td>{{row.description}}</td>   
            <td>
              <!-- <a v-link="{ name: 'pathView', params: {id: row.id}}" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> view</a> -->
              <a v-link="{ name: 'pathUpdate', params: {id: row.id}}" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i> update</a>
              <button class="btn btn-danger btn-xs" v-on:click="deleteRow($event, row)"><i class="fa fa-times"></i> remove</button>
            </td>
          </tr>         
          
          </table>
      </div>
      <div class="box-footer">
        <ul class="pagination pagination-sm no-margin pull-right">
            <li v-if="previousPage"><a v-on:click="paging(previousPage, $event)" href="">«</a></li>
            <li v-for="page in pages" v-bind:class="page.class" transition="item">
              <a v-if="!page.current" v-on:click="paging(page.number, $event)" href="">{{page.number}}</a
              >
              <span v-else>{{page.number}}</span>
            </li>            
            <li v-if="nextPage"><a v-on:click="paging(nextPage, $event)" href="">»</a></li>
          </ul>
      </div>  
    </template>


    <template id="form">    
         <div transition="item">
            <div class="box-header with-border">
              <h3 class="box-title">Form {{label}} {{module}}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <validator name="validation" :classes="{ invalid: 'has-error'}">
            <form class="form-horizontal" id="form-add" novalidate>
              <input type="hidden" name="id" v-model="id" />
              <div class="box-body" style="min-height:400px">
                <div class="form-group" v-validate-class>
                  <label class="col-sm-2 control-label">Group Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Group Name" v-model="name" initial="off" v-validate:name="ruleName" />
                    <p class="text-red" v-if="$validation.name.required">{{$validation.name.required}}</p>
                  </div>
                </div>
                <div class="form-group" v-validate-class>
                  <label class="col-sm-2 control-label">Group Description</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="description" id="description" placeholder="Group Description" v-model="description" initial="off" v-validate:description="ruleDescription" />
                    <p class="text-red" v-if="$validation.description.required">{{$validation.description.required}}</p>
                  </div>
                </div>                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a v-link="{path: '/'}" class="btn btn-default"><i class="fa  fa-arrow-left"></i> Back</a>
                <button type="button" style="margin-left:10px" class="btn btn-success pull-right" v-on:click="save"><i class="fa fa-save"></i> Submit </button>

                <button type="button" class="btn btn-info pull-right" v-on:click="saveAndBack"><i class="fa  fa-save"></i> Submit & Back </button>               
              </div>
              <!-- /.box-footer -->
            </form>
            </validator>
         </div>
         <div class="overlay" v-if="loading">
              <i class="fa fa-refresh fa-spin"></i>
          </div>
    </template>     


    <template id="notification">
      <div class="box-header" v-if="showSuccess" transition="item">
        <div class="alert alert-success alert-dismissible">
          <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
          <h4><i class="icon fa fa-check"></i> Success!</h4>
          {{{ successMsg }}}
        </div>
      </div>

      <div class="box-header" v-if="showError" transition="item">
        <div class="alert alert-danger alert-dismissible">
          <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
          <h4><i class="icon fa fa-check"></i> Error!</h4>
          {{{ errorMsg }}}
        </div>
      </div>
    </template>


    