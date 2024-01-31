<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <li class="nav-item user user-menu">
      <a class="dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button">
        <img src="<?php echo _get_current_user_image($this); ?>" class="user-image" alt="User Image">
        <span class="hidden-xs"><?php echo _get_current_user_name($this); ?></span>
      </a>
      <ul class="dropdown-menu  dropdown-menu-lg dropdown-menu-right">
        <!-- User image -->
        <li class=" dropdown-itemuser-header ">
          <div class="text-center box-profile">
            <div class="">
            <img src="<?php echo _get_current_user_image($this); ?>" class="profile-user-img img-fluid img-circle center" alt="User Image">
            <p>
              <?php echo _get_current_user_name($this); ?>

            </p>
            </div>
          </div>
        </li>
        <!-- Menu Body -->
        <li class="dropdown-item"><?php $user_id = _get_current_user_id($this); ?>
          <a href="<?php echo site_url("admin/edit_user/" . $user_id); ?>" class="nav-link"><i class="glyphicon glyphicon-edit"></i> Profile</a>
          <a href="<?php echo site_url("change_password"); ?>" class="nav-link"><i class="glyphicon glyphicon-edit"></i> Change Password</a>
        </li>
        <!-- Menu Footer-->
        <li class="dropdown-item user-footer">


          <a href="<?php echo site_url("admin/signout"); ?>" class="btn-default btn flat-btn">Sign out</a>

        </li>
      </ul>
    </li>
  </ul>

</nav>