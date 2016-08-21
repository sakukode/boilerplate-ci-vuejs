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
            
            <auth-backup v-if="!auth"></auth-backup>
            <backup v-else></backup>
            <!-- /.box-body -->
            <div class="overlay" v-if="loading"><i class="fa fa-refresh fa-spin"></i></div>
          </div>
          <!-- /.box -->
        </div>
      </div>
      
    </section>
    <!-- /.content -->
</div>

<template id="auth-backup">
    <div class="box-header">
      
    </div>
    <div class="box-body">
      <div class="row">
      <div class="col-md-3 col-md-offset-4">
      <form v-on:submit="submitKey">
        <div class="form-group">
          <label>Please Enter Key to Backup Database</label>
          <input type="password" name="key" class="form-control" v-model="key" />
        </div>
        <div class="box-footer">
          <button type="submit" class="btn btn-success btn-block"><i class="fa fa-key"></i> Submit</button>
        </div>
      </form>
      </div>
      </div>
    </div>
</template>


<template id="backup">
    <div class="box-header with-border">
      <h3 class="box-title">Backup Database</h3>
    </div>
    <div class="box-body" v-if="!doneBackup">      
      <form v-on:submit="backup">
        <div class="form-group">
          <label>Please Select Tables</label>
        </div>
        <div class="form-group">     
          <label class="checkbox-inline"">
            <input type="checkbox" v-on:change="checkAll"> <span class="text-info">Select All</span>
          </label>
        </div>  
        <div class="form-group">
          <label class="checkbox-inline" v-for="table in tables">
            <input type="checkbox" :value="table.name" name="tables" class="tables"> {{table.label}}
          </label>
        </div>        
        <div class="box-footer">
          <button type="submit" class="btn btn-success"><i class="fa fa-archive"></i> Backup</button>
        </div>
      </form>
    </div>
    <div class="box-body" v-if="doneBackup">
      <div class="box-footer">
        <button v-on:click="downloadFile" target="_blank" class="btn bg-purple">Download File</button>
      </div>
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