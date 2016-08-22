<aside class="main-sidebar">
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
   <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">NAVIGATION</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="active"><a href="<?php echo site_url('dashboard');?>"><i class="fa fa-home"></i> <span>Homepage</span></a></li>
        <li><a href="<?php echo site_url('people');?>" ><i class="fa fa-folder"></i> <span>People</span></a></li>  
        <li><a href="<?php echo site_url('user');?>" ><i class="fa fa-folder"></i> <span>User</span></a></li>
        <li><a href="<?php echo site_url('group');?>" ><i class="fa fa-folder"></i> <span>Group</span></a></li>
        <li><a href="<?php echo site_url('utility');?>" ><i class="fa fa-folder"></i> <span>Utility</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-tasks"></i> <span>Setting</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo site_url('setting/general');?>">General</a></li> 
          </ul>
        </li>
      </ul>
      <!-- /.sidebar-menu -->
  </li>
</ul>
<!-- /.sidebar-menu -->
</section>
<!-- /.sidebar -->
</aside>