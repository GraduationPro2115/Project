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
              <h1><?php echo $this->lang->line("Clinic"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Clinic"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Details"); ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>


      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <?php foreach ($business as $list) {
          ?>
            <div class="card card-solid">
              <div class="card-header with-border">
                <h3 class="card-title"><?php echo $list->bus_title; ?></h3>
                <div class="card-tools right">
                  <div class="right">
                    <button id="dLabel" class="btn btn-default btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-edit"></i>
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu pull-right" aria-labelledby="dLabel">
                      <li class="dropdown-item"><a href="<?php echo site_url("business/business_edit/" . $list->bus_id); ?>" class=""><i class="fa fa-edit"></i> <?php echo $this->lang->line("Modify Business"); ?></a></li>

                      <li class="dropdown-item"><a href="<?php echo site_url("business/business_schedule/" . $list->bus_id); ?>"><i class="fa fa-clock"></i> <?php echo $this->lang->line("Business Schedule"); ?></a></li>
                      <li class="dropdown-item"><a href="<?php echo site_url("business/business_service/" . $list->bus_id); ?>"><i class="fa fa-user-md"></i> <?php echo $this->lang->line("Business Service"); ?></a></li>
                      <li class="dropdown-item"><a href="<?php echo site_url("business/business_photo/" . $list->bus_id); ?>"><i class="fa fa-image"></i> <?php echo $this->lang->line("Business Photo"); ?></a></li>
                      <li class="dropdown-item"><a href="<?php echo site_url("business/business_delete/" . $list->bus_id); ?>" onclick="return confirm('Are you sure to delete..?')" class="btn  btn-default btn-sm"><i class="fa fa-remove"></i> <?php echo $this->lang->line("Delete"); ?></a></li>

                    </ul>
                  </div>


                </div>
              </div>
              <div class="card-body">

                <div class="row">
                  <div class="col-md-3 col-sm-3 col-xs-12">
                    <?php
                    $proicon = base_url($this->config->item('theme_folder') . "/images/generic-profile.png");
                    if ($list->bus_logo != "") {
                      $proicon = base_url('uploads/business/' . $list->bus_logo);
                    } ?>
                    <img src="<?php echo $proicon; ?>" class="profile-user-img img-fluid img-circle" />

                  </div>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item"><i class="fa fa-envelope"></i> <?php echo $list->bus_email; ?></li>
                        <li class="list-group-item"><i class="fa fa-phone"></i> <?php echo $list->bus_contact; ?></li>
                        <li class="list-group-item"><i class="fa fa-location-arrow"></i> <?php echo $list->bus_google_street; ?></li>
                    </ul>
                    
                    
                    <div class="form-group">
                      <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input tgl tgl-ios tgl_checkbox" data-table="business" data-status="bus_status" data-idfield="bus_id" data-id="<?php echo $list->bus_id; ?>" id='cb_<?php echo $list->bus_id; ?>' <?php echo ($list->bus_status == 1) ? "checked" : ""; ?> />
                        <label class="custom-control-label" for="cb_<?php echo $list->bus_id; ?>"><?php echo $this->lang->line("Status"); ?></label>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                      <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                          <div class="inner">
                            <p> <?php echo $this->lang->line("Appointment"); ?></p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-clock"></i>
                          </div>
                          <a href="<?php echo site_url("business/business_appointment/" . $list->bus_id); ?>" class="small-box-footer"> <?php echo $this->lang->line("More info"); ?><i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                      </div><!-- ./col -->
                      <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-blue">
                          <div class="inner">
                            <p> <?php echo $this->lang->line("Calender"); ?></p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <a href="<?php echo site_url("business/books/" . $list->bus_id); ?>" class="small-box-footer"><?php echo $this->lang->line("More info"); ?> <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                      </div><!-- ./col -->
                      <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                          <div class="inner">
                            <p> <?php echo $this->lang->line("Reviews"); ?></p>
                          </div>
                          <div class="icon">
                            <i class="fa fa-comment"></i>
                          </div>
                          <a href="<?php echo site_url("business/business_review/" . $list->bus_id); ?>" class="small-box-footer"><?php echo $this->lang->line("More info"); ?><i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                      </div><!-- ./col -->

                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php
          } ?>
        </div>
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <?php $this->load->view("admin/common/common_footer"); ?>


    <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

  <?php $this->load->view("admin/common/common_js"); ?>
  <script>
    $(function() {

      $("body").on("change", ".tgl_checkbox", function() {
        var table = $(this).data("table");
        var status = $(this).data("status");
        var id = $(this).data("id");
        var id_field = $(this).data("idfield");
        var bin = 0;
        if ($(this).is(':checked')) {
          bin = 1;
        }
        $.ajax({
            method: "POST",
            url: "<?php echo site_url("admin/change_status"); ?>",
            data: {
              table: table,
              status: status,
              id: id,
              id_field: id_field,
              on_off: bin
            }
          })
          .done(function(msg) {
            //  alert(msg);
          });
      });
    });
  </script>
</body>

</html>