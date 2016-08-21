<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
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
  
  <script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });

  var BASE_URL = '<?php echo base_url();?>';
</script>
</head>
<body class="hold-transition login-page">  

  <!-- Content -->
  <?php 
      if(isset($content)) {
        echo $content;
      }
  ?>
  <!-- Eof Content -->

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
