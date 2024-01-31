<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Dashboard</title>
  <?php $this->load->view("admin/common/common_css"); ?>
  <?php $this->load->view("admin/common/components/datatable_css"); ?>
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin") . "/plugins/daterangepicker/daterangepicker-bs3.css"); ?>">
<!-- Daterange picker -->
<link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin") . "/plugins/daterangepicker/daterangepicker.css"); ?>">
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <?php $this->load->view("admin/common/common_header"); ?>
    <!-- Left side column. contains the logo and sidebar -->
    <?php $this->load->view("admin/common/common_sidebar"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1><?php echo $this->lang->line("Appointment"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Clinic"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Appointment"); ?></li>
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
              <form action="" method="post">
                <input type="hidden" name="date_range" id="date_range_field" />
                <input type="hidden" name="date_range_lable" id="date_range_lable" />
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label><?php echo $this->lang->line("Date range button:"); ?></label>
                      <div class="input-group" style="width: 100%;">
                        <button class="btn btn-default form-control" style="width: 100%;" type="button" id="daterange-btn">
                          <i class="fa fa-calendar"></i> <span id="reportrange"><?php if (!empty($date_range_lable)) {
                                                                                  echo $date_range_lable;
                                                                                } else {
                                                                                  echo date("M , d Y");
                                                                                } ?></span>
                          <i class="fa fa-caret-down"></i>
                        </button>


                      </div>
                    </div>
                  </div>
                  <?php if (!empty($doctors)) { ?>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label><?php echo $this->lang->line("Choose Doctor"); ?></label>
                        <select class="form-control choosen" name="filter_doct">
                          <option value=""><?php echo $this->lang->line("Choose Doctor"); ?></option>
                          <?php foreach ($doctors as $doctor) {
                          ?>
                            <option value="<?php echo $doctor->doct_id ?>" <?php if ($this->input->post("filter_doct") == $doctor->doct_id) {
                                                                              echo "selected";
                                                                            } ?>><?php echo $doctor->doct_name; ?></option>
                          <?php
                          } ?>
                        </select>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="col-md-1">
                    <label style="line-height: 20px; display: block;">&nbsp;</label>
                    <input type="submit" name="filter" class="btn btn-default " value="<?php echo $this->lang->line("Filter"); ?>" />
                  </div>

                </div>


              </form>
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th><?php echo $this->lang->line("Username"); ?></th>
                    <th><?php echo $this->lang->line("Appointment Date"); ?></th>
                    <th><?php echo $this->lang->line("Time"); ?></th>
                    <th><?php echo $this->lang->line("Payment"); ?></th>
                    <th><?php echo $this->lang->line("Status"); ?></th>
                    <th><?php echo $this->lang->line("Status"); ?></th>
                    <th width="80"><?php echo $this->lang->line("Action"); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($business as $list) {
                    $this->load->view("admin/common/buss_list_row", array("list" => $list));
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
  <!-- daterangepicker -->
  <script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/moment/moment.min.js"); ?>"></script>
  <script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/daterangepicker/daterangepicker.js"); ?>"></script>

  <script>
    $(function() {

      $('#daterange-btn').daterangepicker({
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Tommorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Next 7 Days': [moment(), moment().add(6, 'days')],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'Next 30 Days': [moment(), moment().add(29, 'days')],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function(start, end) {
          $('#reportrange').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          $('#date_range_lable').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          $('#date_range_field').val(start.format('YYYY-MM-D') + ',' + end.format('YYYY-MM-D'));
        }
      );

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