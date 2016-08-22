<div id="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{MODULE}}
        <small>general</small>
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box" style="min-height:512px">
        <notification
          v-bind:show-success="showNotifSuccess"
          v-bind:success-msg="successMessage"
          v-bind:show-error="showNotifError"
          v-bind:error-msg="errorMessage">
        </notification>

        <form-general></form-general>

        <div class="overlay" v-if="loading"><i class="fa fa-refresh fa-spin"></i></div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
</div>

<template id="form-general">
  <div class="box-header">
    <h3 class="box-title">General</h3>
  </div>
  <validator name="validation">
  <form class="form-horizontal" novalidate v-on:submit="save">
    <div class="box-body">
      <div class="form-group">
        <label class="col-sm-3 control-label">Site Name</label>
        <div class="col-sm-9">
          <input type="text" name="sitename" class="form-control" v-model="sitename" v-validate:sitename="ruleSiteName" initial="off" />
          <p class="text-red" v-if="$validation.sitename.required">{{$validation.sitename.required}}</p>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">Address</label>
        <div class="col-sm-9">
          <input type="text" name="address" class="form-control" v-model="address" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">Phone</label>
        <div class="col-sm-9">
          <input type="text" name="phone" class="form-control" v-model="phone" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label">Data Per Page</label>
        <div class="col-sm-9">
          <input type="text" name="per-page" class="form-control" v-model="perpage" v-validate:perpage="rulePerPage" initial="off" />
          <p class="text-red" v-if="$validation.perpage.required">{{$validation.perpage.required}}</p>
        </div>
      </div>
    </div>
    <div class="box-footer">
      <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Save Changes</button>
    </div>
  </form>
  </validator>
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