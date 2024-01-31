<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Dashboard</title>
  <?php $this->load->view("admin/common/common_css"); ?>
  <?php $this->load->view("admin/common/components/datatable_css"); ?>
  <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datepicker/jquery.timepicker.min.css"); ?>">
  <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datepicker/datepicker3.css"); ?>">

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
              <h1><?php echo $this->lang->line("Service"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Clinic"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Service"); ?></li>
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
                          <label for="bus_title"><?php echo $this->lang->line("Service"); ?> <?php echo $this->lang->line("Title"); ?></label>
                          <input type="text" name="bus_title" id="bus_title" value="<?php echo _get_post_back($this, "bus_title") ?>" class="form-control input-sm" placeholder="<?php echo $this->lang->line("Service"); ?> <?php echo $this->lang->line("Title"); ?>" data-toggle="floatLabel" data-value="no-js" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                          <label for="bus_title"><?php echo $this->lang->line("Service"); ?> <?php echo $this->lang->line("Time"); ?></label>
                          <input type="text" name="bus_time" id="timepicker" class="form-control input-sm timepicker" placeholder="<?php echo $this->lang->line("Service"); ?> <?php echo $this->lang->line("Time"); ?>" data-toggle="floatLabel" data-value="no-js" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                          <label for="bus_price"><?php echo $this->lang->line("Service Price"); ?></label>
                          <input type="text" name="bus_price" id="bus_price" value="<?php echo _get_post_back($this, "bus_price") ?>" class="form-control input-sm" placeholder="<?php echo $this->lang->line("Service Price"); ?>" data-toggle="floatLabel" data-value="no-js" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                          <label for="bus_discount"><?php echo $this->lang->line("Service Discount"); ?></label>
                          <input type="text" name="bus_discount" id="bus_discount" value="<?php echo _get_post_back($this, "bus_discount") ?>" class="form-control input-sm" placeholder="<?php echo $this->lang->line("Service Discount"); ?>" data-toggle="floatLabel" data-value="no-js" />
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
                    <table id="example1" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th><?php echo $this->lang->line("Service"); ?> <?php echo $this->lang->line("Title"); ?></th>
                          <th><?php echo $this->lang->line("Service Approx Time"); ?></th>
                          <th><?php echo $this->lang->line("Service Price"); ?></th>
                          <th><?php echo $this->lang->line("Service Discount"); ?></th>
                          <th><?php echo $this->lang->line("Action"); ?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($business_service as $list) { ?>
                          <tr>
                            <td><?php echo $list->service_title; ?></td>
                            <td><?php echo $list->business_approxtime; ?></td>
                            <td><?php echo $list->service_price; ?></td>
                            <td><?php echo $list->service_discount . " %"; ?></td>
                            <td>
                              <div class="btn-group">
                              <a href="<?php echo site_url("business/edit_service/" . $list->id); ?>" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                              <a href="<?php echo site_url("business/delete_business_service/" . $list->id); ?>" onclick="return confirm('Are you sure to delete..?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                              </div>
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
  <script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datepicker/jquery.timepicker.min.js"); ?>"></script>

  <script>
    $('.timepicker').timepicker({
      timeFormat: 'HH:mm:ss',
      interval: 5,
      minTime: '00:05:00'

    });
  </script>


</body>

</html>