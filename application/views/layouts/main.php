<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <?php
    // Page Title
    if(isset($theme['assets']['header']['title']))
      echo $this->template->get_title() . "\n";

    // Meta Tags
    if(isset($theme['assets']['header']['meta'])) {
      foreach($this->template->get_meta() as $meta_tag) {
        echo $meta_tag . "\n";
      }
    }

    // Custom CSS Files
    if(isset($theme['assets']['header']['css'])) {
      foreach($this->template->get_css() as $css_file) {
        echo $css_file . "\n";
      }
    }

    // Custom JS Files
    if(isset($theme['assets']['header']['js'])) {
      foreach($this->template->get_js('header') as $js_file) {
        echo $js_file . "\n";
      }
    }
  ?>  

  <style>   
    .item-transition {
      transition: opacity .5s ease;
    }
    .item-enter {
      opacity: 0;
    }
    .item-leave {
      opacity: 0;   
      display: none;
      position: absolute;   
    }
    .fade-transition {
      transition: opacity .3s ease;
    }
    .fade-enter, .fade-leave {
      opacity: 0;
    }
    
  </style>
  <script type="text/javascript">
      BASE_URL = '<?php echo base_url();?>';
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <?php  
      if(isset($parts['navbar'])) {
            echo $parts['navbar'];
        }   
  ?>
  <!-- Eof Main Header -->

  <!-- Sidebar -->
  <?php  
      if(isset($parts['sidebar'])) {
            echo $parts['sidebar'];
        }   
  ?>
  <!-- Eof Sidebar -->
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <?php 
        if(isset($content)) {
            echo $content;
        }
    ?>
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php  
      if(isset($parts['footer'])) {
            echo $parts['footer'];
        }   
  ?>
  <!-- Eof Main Footer -->
  

</div>
<!-- ./wrapper -->


    <?php
        // Custom JS Files
        if(isset($theme['assets']['footer']['js'])) {
            foreach($this->template->get_js('footer') as $js_file) {
                echo $js_file . "\n";
            }
        }

    ?>        
</body>
</html>
