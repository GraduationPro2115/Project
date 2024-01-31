<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Dashboard</title>
  <?php $this->load->view("admin/common/common_css"); ?>
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
              <h1><?php echo $this->lang->line("Photo"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Clinic"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Photo"); ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="card">
            <div class="card-header">
              <h1 class="card-title"><?php echo $this->lang->line("Edit"); ?></h1>
            </div>
            <div class="card-body">
              <form method="post" action="" enctype="multipart/form-data">
                <div class="col-md-4">
                  <?php if (isset($error)) {
                    echo $error;
                  }
                  echo $this->session->flashdata('message'); ?>
                  <!-- general form elements -->
                  <div class="">
                    <div class="">

                    </div><!-- /.box-header -->
                    <!-- form start -->

                    <div class="">
                      <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                          <div class="form-group">
                            <label for="photo_title"><?php echo $this->lang->line("Photo"); ?> <?php echo $this->lang->line("Title"); ?></label>
                            <input type="text" name="photo_title" id="photo_title" value="<?php echo $setbuss->photo_title; ?>" class="form-control input-sm" placeholder="<?php echo $this->lang->line("Photo"); ?> <?php echo $this->lang->line("Title"); ?>" data-toggle="floatLabel" data-value="no-js" />

                          </div>
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-sm-3">
                          <div class="bus-product">
                            <?php
                            $proicon = base_url($this->config->item('theme_folder') . "/image/generic-profile.png");
                            if ($setbuss->photo_image != "") {
                              $proicon = base_url('uploads/business/businessphoto/' . $setbuss->photo_image);
                            } ?>
                            <img src="<?php echo $proicon; ?>" style="height: 80px; width: 80px;" />
                          </div>
                        </div>

                      </div>
                      <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                          <div class="form-group">
                            <label for="bus_img"><?php echo $this->lang->line("Clinic"); ?> <?php echo $this->lang->line("Photo"); ?> </label>
                            <input type="file" name="bus_img" id="bus_img" />
                          </div>
                        </div>
                      </div>

                      <input type="submit" name="savebus" value="<?php echo $this->lang->line("Save"); ?>" class="btn btn-info btn-block" />

                    </div>


                  </div><!-- /.box -->
                </div>

              </form>

            </div>
          </div>
        </div>
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <?php $this->load->view("admin/common/common_footer"); ?>


    <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

  <?php $this->load->view("admin/common/common_js"); ?>


</body>

</html>