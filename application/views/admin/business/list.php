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
              <h1><?php echo $this->lang->line("Clinic"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Clinic"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("List"); ?></li>
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
                <h1 class="card-title">List</h1>
                <div class="card-tools right">
                <?php if (_get_current_user_type_id($this) == 0) { ?>
                  <a href="<?php echo site_url("admin/business_add"); ?>" class="btn btn-default btn-sm"><?php echo $this->lang->line("Add"); ?></a>
                  <?php } ?>
                </div>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th> <?php echo $this->lang->line("Clinic Title"); ?></th>
                    <th> <?php echo $this->lang->line("Image"); ?></th>
                    <th> <?php echo $this->lang->line("Email and Phone"); ?></th>
                    <th> <?php echo $this->lang->line("User Name"); ?></th>
                    <!-- <th> <?php echo $this->lang->line("Clinic Location"); ?></th> -->
                    <th> <?php echo $this->lang->line("Status"); ?></th>
                    <th> <?php echo $this->lang->line("Recommonded"); ?></th>
                    <!-- <th> <?php echo $this->lang->line("Appointment"); ?></th>
                    <th> <?php echo $this->lang->line("Clinic Option"); ?></th> -->
                    <th width="80"> <?php echo $this->lang->line("Action"); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($business as $list) { ?>
                    <tr>
                      <td><?php echo $list->bus_title; ?></td>
                      <td>
                        <?php
                        $proicon = base_url($this->config->item('theme_folder') . "/images/generic-profile.png");
                        if ($list->bus_logo != "") {
                          $proicon = base_url('uploads/business/' . $list->bus_logo);
                        } ?>
                        <img src="<?php echo $proicon; ?>" style=" width: 80px;" />
                      </td>
                      <td><?php echo $list->bus_email . "<br />" . $list->bus_contact; ?></td>
                      <td><?php echo $list->user_fullname; ?></td>

                      <!-- <td><?php echo $list->bus_google_street; ?></td> -->
                      <td><input class='tgl tgl-ios tgl_checkbox' data-table="business" data-status="bus_status" data-idfield="bus_id" data-id="<?php echo $list->bus_id; ?>" id='cb_<?php echo $list->bus_id; ?>' type='checkbox' <?php echo ($list->bus_status == 1) ? "checked" : ""; ?> />
                        <label class='tgl-btn' for='cb_<?php echo $list->bus_id; ?>'></label>
                      </td>
                      <td><input class='tgl tgl-ios tgl_checkbox' data-table="business" data-status="is_recommonded" data-idfield="bus_id" data-id="<?php echo $list->bus_id; ?>" id='cb_r_<?php echo $list->bus_id; ?>' type='checkbox' <?php echo ($list->is_recommonded == 1) ? "checked" : ""; ?> />
                        <label class='tgl-btn' for='cb_r_<?php echo $list->bus_id; ?>'></label>
                      </td>
                     
                      <td>
                        <div class="btn-group">
                          <a href="<?php echo site_url("business/business_appointment/" . $list->bus_id); ?>" class="btn btn-default btn-sm"><?php echo $this->lang->line("Appointment"); ?> </a>
                          <a href="<?php echo site_url("business/business_edit/" . $list->bus_id); ?>" class="btn btn-success  btn-sm"><i class="fa fa-edit"></i></a>
                        <a href="<?php echo site_url("business/business_delete/" . $list->bus_id); ?>" onclick="return confirm('Are you sure to delete..?')" class="btn btn-danger  btn-sm"><i class="fa fa-trash"></i></a>
                          <button class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false"> <?php echo $this->lang->line("More Option"); ?> <span class="caret"></span></button>
                          <ul class="dropdown-menu">
                            <li class="nav-item"><a href="<?php echo site_url("business/business_schedule/" . $list->bus_id); ?>" class="nav-link"><i class="fa fa-list"></i> <?php echo $this->lang->line("Business Schedule"); ?></a></li>
                            <li class="nav-item"><a href="<?php echo site_url("business/business_review/" . $list->bus_id); ?>" class="nav-link"><i class="fa fa-list"></i> <?php echo $this->lang->line("Business Review"); ?></a></li>
                            <li class="nav-item"><a href="<?php echo site_url("business/business_service/" . $list->bus_id); ?>" class="nav-link"><i class="fa fa-list"></i> <?php echo $this->lang->line("Business Service"); ?></a></li>
                            <li class="nav-item"><a href="<?php echo site_url("business/business_photo/" . $list->bus_id); ?>" class="nav-link"><i class="fa fa-list"></i> <?php echo $this->lang->line("Business Photo"); ?></a></li>
                          </ul>
                        </div>
                       
                      </td>
                    </tr>
                  <?php
                  } ?>
                </tbody>
              </table>
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
  <?php $this->load->view("admin/common/components/datatable_js"); ?>
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