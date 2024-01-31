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
              <h1>Api Key</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Settings</a></li>
                <li class="breadcrumb-item active">Auth</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6">
              <?php if (isset($error)) {
                echo $error;
              }
              echo $this->session->flashdata('message'); ?>
              <!-- general form elements -->
              <div class="card box-primary">
                <div class="card-header">
                  <h3 class="card-title">Init purchase code</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <div class="card-body">
                  <?php

                  echo form_open();

                  echo "<div class=''>";
                  ?>
                  <div class="form-group">
                    <label class="">Item ID : <span class="text-danger">*</span></label>
                    <input type="text" name="item_id" class="form-control" placeholder="Item Id" value="<?php echo (isset($api->item_id)) ? $api->item_id : ""; ?>" required />
                  </div>
                  <div class="form-group">
                    <label class="">Api Key (Purchase Code) : <span class="text-danger">*</span></label>
                    <input type="text" name="api_key" class="form-control" placeholder="Api Key (Purchase Code of Evanto Item)" value="<?php echo (isset($api->key)) ? $api->key : ""; ?>" required />
                  </div>

                  <?php
                  echo "<div class='row'>";
                  echo "<div class='col-md-12'>Replace <strong>AUTH_KEY=''</strong> value with Item Purchase Code value in your app url config file, Open libs/screen/utils/apiURLs.dart file</div>";
                  echo "<div class='col-md-12'><small>Note : this is important else app will not connect with backend</small></div>";
                  echo '<div class="col-md-12">
				    <button type="submit" class="btn btn-primary btn-flat">' . "Save" . '</button>&nbsp;';
                  echo '</div></div></div>';

                  echo form_close();
                  ?>
                </div>
              </div><!-- /.box -->
            </div>
          </div>
        </div>
        <!-- Main row -->
      </section><!-- /.content -->
      </aside><!-- /.right-side -->
    </div><!-- /.content-wrapper -->

    <?php $this->load->view("admin/common/common_footer"); ?>


    <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

  <?php $this->load->view("admin/common/common_js"); ?>
  <script>
    $(function() {

      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
      });
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
            alert(msg);
          });
      });
    });
  </script>
</body>

</html>