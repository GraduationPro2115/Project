<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Dashboard</title>
  <?php $this->load->view("admin/common/common_css"); ?>
  <?php $this->load->view("admin/common/components/datatable_js"); ?>
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
              <h1><?php echo $this->lang->line("Doctor"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Clinic"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Doctor"); ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="card">
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th><?php echo $this->lang->line("Image"); ?> </th>
                    <th><?php echo $this->lang->line("Doctor Name"); ?> </th>
                    <th><?php echo $this->lang->line("Degree"); ?> </th>
                    <th><?php echo $this->lang->line("Contact No"); ?> </th>
                    <th><?php echo $this->lang->line("Email"); ?> </th>
                    <th><?php echo $this->lang->line("Speciality"); ?> </th>
                  
                    <th width="80"><?php echo $this->lang->line("Action"); ?> </th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($doctors as $list) { ?>
                    <tr>

                      <td>
                        <?php
                        $proicon = base_url($this->config->item('theme_folder') . "/images/generic-profile.png");
                        if ($list->doct_photo != "") {
                          $proicon = base_url('uploads/business/' . $list->doct_photo);
                        } ?>
                        <img src="<?php echo $proicon; ?>" style=" width: 80px;" />
                      </td>
                      <td><?php echo $list->doct_name; ?></td>
                      <td><?php echo $list->doct_degree; ?></td>

                      <td><?php echo $list->doct_phone; ?></td>
                      <td><?php echo $list->doct_email; ?></td>
                      <td><?php echo $list->doct_speciality; ?></td>
                      <td>
                        <div class="btn-group">
                        <a href="<?php echo site_url("business/business_appointment/" . $list->bus_id . "/" . $list->doct_id); ?>" class="btn btn-default btn-sm"><?php echo $this->lang->line("Appointment"); ?> </a>
                        <a href="<?php echo site_url("business/doctor_edit/" . $list->doct_id); ?>" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                        <a href="<?php echo site_url("business/doctor_delete/" . $list->doct_id); ?>" onclick="return confirm('Are you sure to delete..?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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