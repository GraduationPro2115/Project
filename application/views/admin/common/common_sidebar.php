<aside class="main-sidebar  sidebar-dark-primary elevation-4">
  <!-- sidebar: style can be found in sidebar.less -->
  <div class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel  mt-3 pb-3 mb-3 d-flex">
      <div class="pull-left image">
        <img src="<?php echo _get_current_user_image($this); ?>" class="img-circle  elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?php echo _get_current_user_name($this); ?></a>
      </div>
    </div>

    <nav class="mt-2">
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu  nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="active treeview nav-item">
          <a href="<?php
                    if (_get_current_user_type_id($this) == 0) {
                      echo site_url("admin/dashboard");
                    } else if (_get_current_user_type_id($this) == 3) {
                      echo site_url("business/dashboard");
                    } else if (_get_current_user_type_id($this) == 1) {
                      echo site_url("doctor/dashboard");
                    } ?>" class="nav-link">
            <i class="fa fa-tachometer-alt nav-icon"></i> <p> <?php echo $this->lang->line("Dashboard"); ?></p>
          </a>
        </li>

        <?php if (_get_current_user_type_id($this) == 0) { ?>
          <li class="<?php echo _is_active_menu($this, array(), array("area_country", "area_city", "area_locality")); ?> nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-map-pin nav-icon"></i> <p> <?php echo $this->lang->line("Area Management"); ?><i class="fa fa-angle-right right"></i></p>  <small class="label pull-right bg-green"></small>
            </a>
            <ul class="nav-treeview">
              <li class="<?php echo _is_active_menu($this, array(), array("area_country")); ?> nav-item"><a class="nav-link" href="<?php echo site_url("admin/area_country"); ?>"><i class="far fa-circle nav-icon"></i><?php echo $this->lang->line("Country"); ?></a></li>
              <li class="<?php echo _is_active_menu($this, array(), array("area_city")); ?> nav-item"><a class="nav-link" href="<?php echo site_url("admin/area_city"); ?>"><i class="far fa-circle nav-icon"></i><?php echo $this->lang->line("Cities"); ?></a></li>
              <li class="<?php echo _is_active_menu($this, array(), array("area_locality")); ?> nav-item"><a class="nav-link" href="<?php echo site_url("admin/area_locality"); ?>"><i class="far fa-circle nav-icon"></i><?php echo $this->lang->line("Locality"); ?></a></li>

            </ul>
          </li>


          <li class="<?php echo _is_active_menu($this, array(), array("add_user", "listuser")); ?> nav-item">
            <a href="#" class="nav-link">
              <i class="fa fa-user nav-icon"></i> <p> <?php echo $this->lang->line("Users"); ?> <i class="fa fa-angle-right right"></i></p> <small class="label pull-right bg-green"></small>
            </a>
            <ul class="nav-treeview">
              <li class="<?php echo _is_active_menu($this, array(), array("listuser")); ?> nav-item"><a class="nav-link" href="<?php echo site_url("admin/listuser/2"); ?>"><i class="far fa-circle nav-icon"></i><?php echo $this->lang->line("App Register"); ?></a></li>
              <li class="<?php echo _is_active_menu($this, array(), array("listuser")); ?> nav-item"><a class="nav-link" href="<?php echo site_url("admin/listuser/3"); ?>"><i class="far fa-circle nav-icon"></i><?php echo $this->lang->line("Clinic"); ?> <?php echo $this->lang->line("Admin"); ?></a></li>
              <li class="<?php echo _is_active_menu($this, array(), array("listuser")); ?> nav-item"><a class="nav-link" href="<?php echo site_url("admin/listuser/1"); ?>"><i class="far fa-circle nav-icon"></i><?php echo $this->lang->line("Doctor"); ?></a></li>

            </ul>
          </li>
        <?php } ?>
        <?php if (_get_current_user_type_id($this) == 1) {
        ?>
          <li class="<?php echo _is_active_menu($this, array(), array("doctor_appointment")); ?> nav-item">
            <a class="nav-link" href="<?php echo site_url("doctor/doctor_appointment/"); ?>">
              <i class="fa fa-clock nav-icon"></i> <p> <?php echo $this->lang->line("Appointment"); ?></p> <i class="fa fa-angle-right right"></i><small class="label pull-right bg-green"></small>
            </a>
          </li>
          <li class="<?php echo _is_active_menu($this, array(), array("books")); ?> nav-item">
            <a class="nav-link" href="<?php echo site_url("doctor/books/"); ?>">
              <i class="fa fa-calendar nav-icon"></i> <p> <?php echo $this->lang->line("Calender"); ?></p> <i class="fa fa-angle-right right"></i><small class="label pull-right bg-green"></small>
            </a>
          </li>
        <?php
        }
        ?>
        <?php if (_get_current_user_type_id($this) == 3) { ?>
          <li class="<?php echo _is_active_menu($this, array(), array("list_business")); ?> nav-item">
            <a class="nav-link" href="<?php echo site_url("business/list_business"); ?>">
              <i class="fa fa-hospital-symbol nav-icon"></i> <p> <?php echo $this->lang->line("Clinic"); ?></p> <i class="fa fa-angle-right right"></i><small class="label pull-right bg-green"></small>
            </a>
          </li>
          <?php
          $bsness = $this->business_model->get_businesses(3);
          foreach ($bsness as $bs) { ?>
            <li class="<?php echo _is_active_menu($this, array(), array("business_appointment")); ?> nav-item">
              <a class="nav-link" href="<?php echo site_url("business/business_appointment/" . $bs->bus_id); ?>">
                <i class="fa fa-clock nav-icon"></i> <p> <?php echo $this->lang->line("Appointment"); ?></p> <i class="fa fa-angle-right right"></i><small class="label pull-right bg-green"></small>
              </a>
            </li>
            <li class="<?php echo _is_active_menu($this, array(), array("books")); ?> nav-item">
              <a class="nav-link" href="<?php echo site_url("business/books/" . $bs->bus_id); ?>">
                <i class="fa fa-calendar nav-icon"></i> <p> <?php echo $this->lang->line("Calender"); ?></p> <i class="fa fa-angle-right right"></i><small class="label pull-right bg-green"></small>
              </a>
            </li>

            <li class="<?php echo _is_active_menu($this, array(), array("business_review")); ?> nav-item">
              <a class="nav-link" href="<?php echo site_url("business/business_review/" . $bs->bus_id); ?>">
                <i class="fa fa-comment nav-icon"></i> <p> <?php echo $this->lang->line("Reviews"); ?></p> <i class="fa fa-angle-right right"></i><small class="label pull-right bg-green"></small>
              </a>
            </li>
            <li class="<?php echo _is_active_menu($this, array(), array("doctor", "doctor_list")); ?> nav-item">
              <a class="nav-link" href="<?php echo site_url("business/doctor/" . $bs->bus_id); ?>">
                <i class="fa fa-user-md nav-icon"></i> <p> <?php echo $this->lang->line("Doctor"); ?></p> <i class="fa fa-angle-right right"></i><small class="label pull-right bg-green"></small>
              </a>
              <ul class="nav-treeview">
                <li class="<?php echo _is_active_menu($this, array(), array("doctor")); ?> nav-item"><a class="nav-link" href="<?php echo site_url("business/doctor/" . $bs->bus_id); ?>"><i class="far fa-circle nav-icon"></i><?php echo $this->lang->line("New Doctor"); ?></a></li>
                <li class="<?php echo _is_active_menu($this, array(), array("doctor_list")); ?> nav-item"><a class="nav-link" href="<?php echo site_url("business/doctor_list/" . $bs->bus_id); ?>"><i class="far fa-circle nav-icon"></i><?php echo $this->lang->line("List Doctor"); ?></a></li>

              </ul>
            </li>
          <?php } ?>
        <?php } ?>
        <?php if (_get_current_user_type_id($this) == 3) { ?>

        <?php } ?>
        <?php if (_get_current_user_type_id($this) == 0) { ?>
          <li class="<?php echo _is_active_menu($this, array(), array("business_add", "list_business")); ?> nav-item">
            <a class="nav-link" href="#">
              <i class="fa fa-hospital-symbol nav-icon"></i> <p> <?php echo $this->lang->line("Clinic"); ?></p> <i class="fa fa-angle-right right"></i><small class="label pull-right bg-green"></small>
            </a>
            <ul class="nav-treeview">
              <li class="<?php echo _is_active_menu($this, array(), array("business_add")); ?> nav-item"><a class="nav-link" href="<?php echo site_url("admin/business_add"); ?>"><i class="far fa-circle nav-icon"></i><?php echo $this->lang->line("Add New"); ?></a></li>
              <li class="<?php echo _is_active_menu($this, array(), array("list_business")); ?> nav-item"><a class="nav-link" href="<?php echo site_url("admin/list_business"); ?>"><i class="far fa-circle nav-icon"></i><?php echo $this->lang->line("List"); ?> <?php echo $this->lang->line("Clinic"); ?></a></li>

            </ul>
          </li>
          <li class="<?php echo _is_active_menu($this, array(), array("add_appointment", "business_appointment")); ?> nav-item">
            <a class="nav-link" href="<?php echo site_url("business/business_appointment"); ?>">
              <i class="fa fa-clock nav-icon"></i> <p> <?php echo $this->lang->line("Appointment"); ?></p> <i class="fa fa-angle-right right"></i> <small class="label pull-right bg-green"></small>
            </a>
          </li>
          <li class="<?php echo _is_active_menu($this, array(), array("addcategories", "listcategories")); ?> nav-item">
            <a class="nav-link" href="#">
              <i class="fa fa-tags nav-icon"></i>
              <p> <?php echo $this->lang->line("Categories"); ?></p>
              <i class="fa fa-angle-right right"></i>
            </a>
            <ul class="nav-treeview">
              <li class="<?php echo _is_active_menu($this, array(), array("addcategories")); ?> nav-item"><?php echo anchor('admin/addcategories', '<i class="far fa-circle nav-icon"></i> ' . $this->lang->line("Add New"), array("title" => "Add Categories", "class" => "nav-link")); ?></li>
              <li class="<?php echo _is_active_menu($this, array(), array("listcategories")); ?> nav-item"><?php echo anchor('admin/listcategories', '<i class="far fa-circle nav-icon"></i> ' . $this->lang->line("List Categories"), array("title" => "List Categories", "class" => "nav-link")); ?></li>
            </ul>
          </li>
          <?php if(!IS_TEST) { ?>
<!--          <li class="nav-item">-->
<!--            <a href="#" class="nav-link">-->
<!--              <i class="fa fa-cog nav-icon"></i>-->
<!--              <p>Settings <i class="fa fa-angle-right right"></i></p> <small class="label pull-right bg-green"></small>-->
<!--            </a>-->
<!--            <ul class="nav-treeview">-->
<!--              <li><a href="--><?php //echo site_url("auth/app"); ?><!--" class="nav-link"><i class="far fa-circle nav-icon"></i>-->
<!--                  <p>Api Key</p>-->
<!--                </a></li>-->
<!--            </ul>-->
<!--          </li>-->
          <?php } ?>
        <?php } ?>
      </ul>
    </nav>
  </div>
  <!-- /.sidebar -->
</aside>