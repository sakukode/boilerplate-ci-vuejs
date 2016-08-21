<div class="login-box" id="app">
  <div class="login-logo">
    <a href="<?php echo site_url();?>"><b>{{siteName}}</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Login</p>
    <!-- Notif -->
    <notification 
      v-bind:show-success="showNotifSuccess"
      v-bind:success-message="successMessage"
      v-bind:show-error="showNotifError"
      v-bind:error-message="errorMessage">
    </notification>
    <!-- end Notif -->

    <form v-on:submit="login">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" name="identity" v-model="identity" />
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password" v-model="password" />
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" id="remember" value="1" name="remember"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<template id="notification">
  <div class="box-header" v-if="showSuccess" transition="item">
    <div class="alert alert-success alert-dismissible">
      <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      {{{ successMessage }}}
    </div>
  </div>
  <div class="box-header" v-if="showError" transition="item">
    <div class="alert alert-danger alert-dismissible">
      <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
      <h4><i class="icon fa fa-check"></i> Error!</h4>
      {{{ errorMessage }}}
    </div>
  </div>
</template>
