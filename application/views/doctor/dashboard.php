<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Dashboard</title>
  <?php $this->load->view("admin/common/common_css"); ?>
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin") . "/plugins/morris/morris.css"); ?>">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin") . "/plugins/daterangepicker/daterangepicker.css"); ?>">
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
              <h1><?php echo $this->lang->line("Dashboard"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Clinic"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Dashboard"); ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <a href="<?php echo site_url("doctor/doctor_appointment"); ?>" class="small-box-footer">
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3><?php echo $app_count; ?></h3>
                    <p><?php echo $this->lang->line("Appointment"); ?></p>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>

                </div>
              </a>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $user_count; ?></h3>
                  <p><?php echo $this->lang->line("User Registrations"); ?></p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
              </div>
            </div><!-- ./col -->
            <!--<div class="col-lg-3 col-xs-6">
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>65</h3>
                  <p>Unique Visitors</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div> ./col -->
          </div><!-- /.row -->

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">

                  <h3><?php echo $this->lang->line("Todays Appointments :"); ?> </h3>
                  <table id="example1" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th><?php echo $this->lang->line("Username"); ?></th>
                        <th><?php echo $this->lang->line("Appointment Date"); ?></th>
                        <th><?php echo $this->lang->line("Time"); ?></th>
                        <th><?php echo $this->lang->line("Appointment Service"); ?></th>
                        <th><?php echo $this->lang->line("Status"); ?></th>
                        <th><?php echo $this->lang->line("Status"); ?></th>
                        <th width="80"><?php echo $this->lang->line("Action"); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($appointments as $app) {
                        $this->load->view("admin/common/buss_list_row", array("list" => $app));
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="card">

                <div class="card-body">
                  <form action="" method="post">
                    <input type="hidden" name="date_range" id="date_range_field" />
                    <input type="hidden" name="date_range_lable" id="date_range_lable" />
                    <div class="form-group">
                      <label><?php echo $this->lang->line("Date range button:"); ?></label>
                      <div class="input-group">
                        <button class="btn btn-default" type="button" id="daterange-btn">
                          <i class="fa fa-calendar"></i> <span id="reportrange"><?php if (!empty($date_range_lable)) {
                                                                                  echo $date_range_lable;
                                                                                } else {
                                                                                  echo date("M , d Y");
                                                                                } ?></span>
                          <i class="fa fa-caret-down"></i>
                        </button>
                        <input type="submit" name="filter" class="btn btn-default" value="<?php echo $this->lang->line("Filter"); ?>" />

                      </div>
                    </div>
                  </form>
                  <strong><?php echo $this->lang->line("Appointment charts"); ?></strong>
                  <div class="chart" id="revenue-chart" style="position: relative; height: 300px;"></div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <!-- quick notification widget -->
              <form action="" method="post" enctype="multipart/form-data">
                <div class="card card-info">
                  <div class="card-header">


                    <h1 class="card-title"> <i class="fa fa-bell"></i> <?php echo $this->lang->line("Quick Notification"); ?></h1>
                    <!-- tools box -->
                    <div class="pull-right card-tools">
                      <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                        <i class="fa fa-times"></i></button>
                    </div>
                    <!-- /. tools -->
                  </div>
                  <div class="card-body">


                    <div class="form-group">
                      <input type="text" class="form-control" name="subject" placeholder="<?php echo $this->lang->line("Subject"); ?>*" required="">
                    </div>
                    <div class="form-group">
                      <span> <?php echo $this->lang->line("Notification Banner :"); ?></span>
                      <input type="file" class="form-control" name="file" placeholder="Attachment Image">
                    </div>
                    <div>
                      <textarea class="textarea" name="message" placeholder="<?php echo $this->lang->line("Message"); ?>*" required="" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                    </div>
                    <input type="hidden" name="type" value="notification" />
                  </div>
                  <div class="card-footer clearfix">
                    <button type="submit" class="pull-right btn btn-default" id="sendEmail"> <?php echo $this->lang->line("Send"); ?>
                      <i class="fa fa-arrow-circle-right"></i></button>
                  </div>
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

  <!-- Morris.js charts -->
  <script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/morris/raphael-min.js"); ?>"></script>
  <script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/morris/morris.min.js"); ?>"></script>


  <?php $this->load->view("admin/common/components/datatable_js"); ?>
  <!-- daterangepicker -->
  <script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/moment/moment.min.js"); ?>"></script>
  <script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/daterangepicker/daterangepicker.js"); ?>"></script>


  <script>
    $(function() {
      // LINE CHART
      var line = new Morris.Line({
        element: 'revenue-chart',
        resize: true,
        data: <?php echo json_encode($chart_appointment); ?>,
        xkey: 'appointment_date',
        ykeys: ['count_app'],
        labels: ['App'],
        lineColors: ['#3c8dbc'],
        hideHover: 'auto'
      });

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

    });
  </script>
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

      //Date range as a button

    });
  </script>



</body>

</html>