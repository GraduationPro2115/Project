<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Dashboard</title>
  <?php $this->load->view("admin/common/common_css"); ?>
  <?php echo link_tag($this->config->item('theme_admin') . '/plugins/datepicker/datepicker3.css'); ?>
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <?php $this->load->view("admin/common/common_header"); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <?php $this->load->view("admin/common/common_sidebar"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1><?php echo $this->lang->line("Users"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Users"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Edit"); ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <form role="form" action="" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h1 class="card-title"><?php echo $this->lang->line("Edit"); ?></h1>
                </div>

                <div class="card-body">


                  <div class="">
                    <?php
                    echo $this->session->flashdata("message");
                    ?>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6">
                          <label for="user_fullname"> <?php echo $this->lang->line("Full Name"); ?></label>
                          <input type="text" class="form-control" id="user_fullname" value="<?php echo $user->user_fullname; ?>" name="user_fullname" placeholder="<?php echo $this->lang->line("Full Name"); ?>" />
                        </div>
                        <div class="col-md-6">
                          <label for="user_email"> <?php echo $this->lang->line("User Email"); ?></label>
                          <input type="email" class="form-control" id="user_email" disabled="" readonly="" value="<?php echo $user->user_email; ?>" name="user_email" placeholder="<?php echo $this->lang->line("User Email"); ?>" />
                        </div>
                        <div class="col-md-6">
                          <label for="user_password"> <?php echo $this->lang->line("Password"); ?></label>
                          <input type="password" class="form-control" id="user_password" value="<?php echo _decrypt_val($user->user_password); ?>" name="user_password" placeholder="<?php echo $this->lang->line("Password"); ?>" />
                        </div>

                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-xs-6 col-sm-6">
                          <label for="user_bdate"> <?php echo $this->lang->line("Birth Date(dd-mm-yyyy) :"); ?> </label>
                          <input type="text" class="form-control" id="user_bdate" data-validation-optional="true" value="<?php echo date("d-m-Y", strtotime($user->user_bdate)); ?>" name="user_bdate" placeholder="<?php echo $this->lang->line("Birth Date(dd-mm-yyyy) :"); ?>" data-validation="date" data-validation-format="dd-mm-yyyy" />
                        </div>

                        <div class="col-md-6 col-xs-6 col-sm-6">
                          <label for="user_phone"> <?php echo $this->lang->line("Contact No"); ?> </label>
                          <input type="text" class="form-control" id="user_phone" data-validation-optional="true" value="<?php echo $user->user_phone; ?>" name="user_phone" placeholder="<?php echo $this->lang->line("Contact No"); ?>" data-validation="number" data-validation-optional-if-answered="home-phone, work-phone" />
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-4">
                        <div class="bus-product thumbnail">
                          <?php
                          $proicon = base_url($this->config->item('theme_folder') . "/images/generic-profile.png");
                          if ($user->user_image != "") {
                            $proicon = base_url("uploads/profile/" . $user->user_image);
                          }
                          ?>
                          <div class="pro-icon1" style="background-image: url('<?php echo $proicon; ?>'); height: 150px; width: 100%; background-size: contain; background-repeat: no-repeat; background-position: center;"></div>
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class=""> <?php echo $this->lang->line("Choose User Photo"); ?></label>
                          <input type="file" name="user_image" onchange="readURL1(this)" value="<?php echo $user->user_image;  ?>" />
                        </div>
                      </div>
                    </div>
                    <?php if ($user->user_id != _get_current_user_id($this)) { ?>
                      <div class="form-group">
                        <div class="col-md-12">
                          <div class="checkbox">
                            <label for="status">
                              <input type="checkbox" id="status" name="status" <?php echo ($user->user_status == 1) ? "checked" : ""; ?> /> Status
                            </label>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  </div><!-- /.box-body -->
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary"> <?php echo $this->lang->line("Save"); ?></button>

                </div>
              </div>
            </div>
          </div>
        </form>
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <?php $this->load->view("admin/common/common_footer"); ?>


    <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

  <?php $this->load->view("admin/common/common_js"); ?>
  <script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>

  <script>
    function readURL1(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          jQuery('.pro-icon1').css("background-image", "url(" + e.target.result + ")");
        };

        reader.readAsDataURL(input.files[0]);
      }
    }
    $(function() {
      $('#user_bdate').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
      });

    });
  </script>
  

</body>

</html>