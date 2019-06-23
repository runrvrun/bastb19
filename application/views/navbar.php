<?php
  $menulist = $this->session->userdata('logged_in')->menu_items;
  // print_r($menulist);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Header
============================================= -->
<header id="header" class="full-header dark transparent-header">

  <div id="header-wrap">

    <div class="container clearfix">

      <div id="primary-menu-trigger"><i class="icon-reorder"></i></div>

      <!-- Logo
      ============================================= -->
      <div id="logo">
        <a href="<?php echo base_url('Home'); ?>" title="BASTB Logo">
          <img src="<?php echo base_url('assets/img/logo_png.png'); ?>" alt="BASTB Logo" height="80" />
        </a>
        
      </div><!-- #logo end -->

      <!-- Primary Navigation
      ============================================= -->
      <nav id="primary-menu">

        <ul>
            <li class="current">
              <a href="<?php echo base_url('Home'); ?>">
                <!-- <i class="fa fa-home"></i> -->
                HOME 
              </a>
            </li>
            <?php 
              foreach($menulist as $menu){
                if($menu->menu_level == 'PARENT'){
            ?>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <?php echo $menu->menu_name; ?>
                    </a>
                    <ul class="dropdown-menu">
                      <?php 
                        foreach($menulist as $submenu){
                          if($submenu->menu_level == 'MENU' and $submenu->menu_parent == $menu->id){
                      ?>
                            <li>
                              <?php if($submenu->has_child == 1 ){ 
                                      $arr = explode("/", $submenu->menu_name, 2);
                                      $first = $arr[0];
                              ?>
                                <a href="#"><?php echo '<img src="'.base_url('assets/ico/'.$first.'.png').'" width="14" />&nbsp;&nbsp;'.$submenu->menu_name; ?></a>
                                <ul class="dropdown-submenu">
                                  <?php
                                    foreach($menulist as $submenu2){
                                      if($submenu2->menu_level == 'SUBMENU' and $submenu2->menu_parent == $submenu->id){
                                  ?>
                                        <li><a href="<?php echo ($submenu2->controller_name == '' ? '#' : base_url($submenu2->controller_name)); ?>"><?php echo $submenu2->menu_name; ?></a></li>
                                  <?php
                                      }
                                    }
                                  ?>
                                </ul>
                              <?php 
                                  }
                                  else{
                                    $arr = explode("/", $submenu->menu_name, 2);
                                    $first = $arr[0];
                                    echo '<a href="'.($submenu->controller_name == '' ? '#' : base_url($submenu->controller_name)).'"><img src="'.base_url('assets/ico/'.$first.'.png').'" width="14" />&nbsp;&nbsp;'.$submenu->menu_name.'</a>';
                                  }
                               ?>
                            </li>
                      <?php
                          }
                          
                        }
                      ?>
                    </ul>
                  </li>
            <?php
                }
              }
            ?>
         
          
        </ul>

        <!-- Top User Account
        ============================================= -->
        <div id="top-user-account">
          <a href="#" id="top-user-account-trigger">
            <img src="<?php echo base_url().'../upload/user_profile/'.$this->session->userdata('logged_in')->file_avatar; ?>" class="img-circle img-responsive" alt="User Photo" />
          </a>
          <div class="top-user-account-content">
            <div class="top-user-account-title">
              <h4><?php echo $this->session->userdata('logged_in')->id_pengguna; ?></h4>
            </div>
            <div class="top-user-account-menu">
              <a href="<?php echo base_url('UserProfile'); ?>">
                <span class="top-user-account-menu-item clearfix">Settings</span>
              </a>
              <a href="<?php echo base_url('Home/Logout'); ?>"><span class="top-user-account-menu-item clearfix">Log Out</span></a>
            </div>
          </div>
        </div><!-- #top-user-account end -->

      </nav><!-- #primary-menu end -->

    </div>

  </div>

</header><!-- #header end -->