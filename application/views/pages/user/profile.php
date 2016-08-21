
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User
        <small>Profile</small>
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content" id="app">

      <!-- Default box -->
    <div class="box" style="min-height:512px">     
      <div class="box-body">
      <div class="row">
        <div class="col-md-4">
          <div class="box box-widget widget-user-2">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header box-primary">
                <div class="widget-user-image">
                  <img class="img-circle" src="<?php echo base_url();?>assets/public/themes/admin-lte/images/user7-128x128.jpg" alt="User Avatar">
                </div>
                <!-- /.widget-user-image -->
                <h3 class="widget-user-username">{{fullname}}</h3>
                <h5 class="widget-user-desc">{{group}}</h5>                
              </div>
              <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                  <li v-link-active><a v-link="{path: '/detail'}">Profile</a></li>
                  <li v-link-active><a v-link="{path: '/change'}">Change Profile</a></li>
                  <li v-link-active><a v-link="{path: '/change-password'}">Change Password</a></li>  
                </ul>
              </div>
            </div>
        </div> 
        <div class="col-md-8">

          <router-view></router-view>

        </div>
      </div>
      </div>     
    </div>
    <!-- /.box -->


    </section>
    <!-- /.content -->

    <template id="profile">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Profile Detail</h3>
          <!-- /.box-tools -->
        </div> 

        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding" style="display: block;">
          <table class="table table-hover">
            <tbody>
              <tr>
                <td>Username</td>
                <td>{{user.username}}</td>
              </tr>
              <tr>
                <td>Email</td>
                <td>{{user.email}}</td>
              </tr>
              <tr>
                <td>Firstname</td>
                <td>{{user.firstname}}</td>
              </tr>
              <tr>
                <td>Lastname</td>
                <td>{{user.lastname}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
    </template>


    <template id="form-change-profile">
      <div class="box">
        <notification 
          v-bind:show-success="showNotifSuccess" 
          v-bind:success-message="successMessage"
          v-bind:show-error="showNotifError"
          v-bind:error-message="errorMessage">          
        </notification>

        <div class="box-header">
          <h3 class="box-title">Change Profile</h3>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <validator name="validationChangeProfile" :classes="{ invalid: 'has-error'}"> 
        <form novalidate v-on:submit="changeProfile">
        <div class="box-body" style="display: block;">  
            <div class="form-group" v-validate-class>
              <label>Firstname</label>
              <input class="form-control" type="text" v-model="firstname" v-bind:value="user.firstname" initial="off" v-validate:firstname="{ required: true}" />
               <p class="text-red" v-if="$validationChangeProfile.firstname.required">FirstName is Required</p>
            </div>
            <div class="form-group">
              <label>Lastname</label>
              <input class="form-control" type="text" v-model="lastname" v-bind:value="user.lastname" />             
            </div>  
        </div>
        <div class="box-footer">
          <button class="btn btn-success pull-right" type="submit"><i class="fa fa-save"></i> Save Changes</button>
        </div>
        </form>
        </validator>
        <!-- /.box-body -->
      </div>
    </template>

    <template id="form-change-password">
      <div class="box">
        <notification 
          v-bind:show-success="showNotifSuccess" 
          v-bind:success-message="successMessage"
          v-bind:show-error="showNotifError"
          v-bind:error-message="errorMessage">          
        </notification>

        <div class="box-header">
          <h3 class="box-title">Change Password</h3>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <validator name="validationChangePassword" :classes="{ invalid: 'has-error'}"> 
          <form novalidate v-on:submit="changePassword">
          <div class="box-body" style="display: block;">  
              <div class="form-group" v-validate-class>
                <label>Old Password</label>
                <input class="form-control" type="password" name="old" v-model="oldPassword" initial="off" v-validate:old="rulesOld" />
                <p class="text-red" v-if="$validationChangePassword.old.invalid">{{ $validationChangePassword.old.required }}</p>
              </div>
              <div class="form-group" v-validate-class>
                <label>New Password</label>
                <input class="form-control" type="password" name="new" initial="off" v-model="newPassword" v-validate:new="rulesNew" />
                <p class="text-red" v-if="$validationChangePassword.new.required">{{ $validationChangePassword.new.required }}</p>
                <p class="text-red" v-if="$validationChangePassword.new.minlength">{{ $validationChangePassword.new.minlength }}</p>
              </div>  
              <div class="form-group" v-validate-class>
                <label>Confirm New Password</label>
                <input class="form-control" type="password" name="confirm_new" initial="off"
                  v-model="confirmNewPassword" 
                  v-validate:confirm="{
                    required: { rule: true, message: 'required you confirm password !!' },
                    confirm: { rule: newPassword, message: 'your confirm password incorrect !!' }
                  }" />
                <p class="text-red" v-if="$validationChangePassword.confirm.required">{{ $validationChangePassword.confirm.required }}</p>
                <p class="text-red" v-if="$validationChangePassword.confirm.confirm">{{ $validationChangePassword.confirm.confirm }}</p>
              </div>  
          </div>
          <div class="box-footer">
            <button class="btn btn-success pull-right" type="submit"><i class="fa fa-save"></i> Save Changes</button>
          </div>
          </form>
        </validator>
        <!-- /.box-body -->
      </div>
    </template>

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