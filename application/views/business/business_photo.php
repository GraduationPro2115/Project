<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Dashboard</title>
  <?php $this->load->view("admin/common/common_css"); ?>
  <?php $this->load->view("admin/common/components/datatable_css"); ?>
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
          <form method="post" action="" enctype="multipart/form-data">
            <div class="row">

              <div class="col-md-4">
                <?php if (isset($error)) {
                  echo $error;
                }
                echo $this->session->flashdata('message'); ?>
                <!-- general form elements -->
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title"><?php echo $this->lang->line("Add New"); ?></h3>
                  </div><!-- /.box-header -->
                  <!-- form start -->

                  <div class="card-body">
                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                          <label for="photo_title"><?php echo $this->lang->line("Clinic Photo Title"); ?></label>
                          <input type="text" name="photo_title" id="photo_title" value="<?php echo _get_post_back($this, "photo_title") ?>" class="form-control input-sm" placeholder="<?php echo $this->lang->line("Clinic Photo Title"); ?>" data-toggle="floatLabel" data-value="no-js" />
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                          <label for="bus_img"> <?php echo $this->lang->line("Clinic"); ?><?php echo $this->lang->line("Photo"); ?></label>
                          <input type="file" name="bus_img" id="bus_img" />
                        </div>
                      </div>
                    </div>

                    <input type="submit" name="savebus" value="<?php echo $this->lang->line("Add"); ?>" class="btn btn-info btn-block" />

                  </div>
                </div><!-- /.box -->
              </div>
              <div class="col-md-8">
                <div class="card card-primary">

                  <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th><?php echo $this->lang->line("Photo"); ?> <?php echo $this->lang->line("Title"); ?></th>
                          <th> <?php echo $this->lang->line("Clinic"); ?> <?php echo $this->lang->line("Photo"); ?></th>
                          <th><?php echo $this->lang->line("Action"); ?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($business_photo as $list) { ?>
                          <tr>
                            <td><?php echo $list->photo_title; ?></td>
                            <td>
                              <?php
                              $proicon = base_url($this->config->item('theme_folder') . "/image/generic-profile.png");
                              if ($list->photo_image != "") {
                                $proicon = base_url('uploads/business/businessphoto/' . $list->photo_image);
                              } ?>
                              <img src="<?php echo $proicon; ?>" style="height: 80px; width: 80px;" />
                            </td>

                            <td>
                              <a href="<?php echo site_url("business/edit_photo/" . $list->id); ?>" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                              <a href="<?php echo site_url("business/delete_business_photo/" . $list->id); ?>" onclick="return confirm('Are you sure to delete..?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>


                </div>
              </div>



            </div>
          </form>
        </div>
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <?php $this->load->view("admin/common/common_footer"); ?>


    <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

  <?php $this->load->view("admin/common/common_js"); ?>
  <?php $this->load->view("admin/common/components/datatable_js"); ?>

</body>

</html>