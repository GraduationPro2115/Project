<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Dashboard</title>
  <?php $this->load->view("admin/common/common_css"); ?>
  <?php echo link_tag($this->config->item('theme_admin') . '/plugins/chosen/chosen.min.css'); ?>
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
              <h1><?php echo $this->lang->line("Locality"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Area"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Locality"); ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-5">
              <div class="card">
                <div class="card-header">
                  <?php echo $this->lang->line("Add"); ?>
                </div>
                <div class="card-body">
                  <?php echo $this->session->flashdata("message"); ?>
                  <form role="form" action="" method="post">
                    <div class="">
                      <div class="form-group">
                        <label for="country_id"> <?php echo $this->lang->line("Country"); ?></label>
                        <select class="form-control select2" name="country_id" id="country_id" style="width: 100%;">
                          <option value=""> <?php echo $this->lang->line("Choose Country"); ?></option>
                          <?php foreach ($countries as $country) {
                          ?>
                            <option value="<?php echo $country->country_id; ?>"><?php echo $country->country_name; ?></option>
                          <?php
                          } ?>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="city_id"> <?php echo $this->lang->line("City"); ?></label>
                        <select class="form-control select2" name="city_id" id="city_id" style="width: 100%;">
                          <option value=""> <?php echo $this->lang->line("Choose City"); ?></option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="locality_name"> <?php echo $this->lang->line("Locality Name"); ?></label>
                        <input type="text" class="form-control" id="locality_name" name="locality_name" placeholder="<?php echo $this->lang->line("Locality Name"); ?>" />
                      </div>
                      <div class="form-group">
                        <label for="lat"> <?php echo $this->lang->line("Latitude"); ?></label>
                        <input type="text" class="form-control" id="lat" name="lat" placeholder="<?php echo $this->lang->line("Latitude"); ?>" />
                      </div>

                      <div class="form-group">
                        <label for="lon"> <?php echo $this->lang->line("Longitude"); ?></label>
                        <input type="text" class="form-control" id="lon" name="lon" placeholder="<?php echo $this->lang->line("Longitude"); ?>" />
                      </div>
                      <div class="form-group">
                        <div class="checkbox">
                          <label for="status">
                            <input type="checkbox" id="status" name="status" /> <?php echo $this->lang->line("Status"); ?>
                          </label>
                        </div>
                      </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                      <button type="submit" class="btn btn-primary"> <?php echo $this->lang->line("Add"); ?></button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-7">


              <div class="card">
                <div class="card-header">
                  <?php echo $this->lang->line("List"); ?>
                </div>
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th> <?php echo $this->lang->line("Locality Name"); ?></th>
                        <th> <?php echo $this->lang->line("City Name"); ?></th>
                        <th> <?php echo $this->lang->line("Lat, Lon"); ?></th>
                        <th> <?php echo $this->lang->line("Status"); ?></th>
                        <th width="80"> <?php echo $this->lang->line("Action"); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($localities as $locality) {
                      ?>
                        <tr>
                          <td><?php echo $locality->locality; ?></td>
                          <td><?php echo $locality->city_name; ?></td>
                          <td><?php echo $locality->locality_lat . "," . $locality->locality_lon; ?></td>
                          <td><input class='tgl tgl-ios tgl_checkbox' data-table="area_locality" data-status="status" data-idfield="locality_id" data-id="<?php echo $locality->locality_id; ?>" id='cb_<?php echo $locality->locality_id; ?>' type='checkbox' <?php echo ($locality->status == 1) ? "checked" : ""; ?> />
                            <label class='tgl-btn' for='cb_<?php echo $locality->locality_id; ?>'></label>
                          </td>
                          <td>
                            <div class="btn-group">
                            <a href="<?php echo site_url("admin/locality_edit/" . $locality->locality_id); ?>" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                            <a href="<?php echo site_url("admin/locality_delete/" . $locality->locality_id); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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

          </div>
        </div>
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <?php $this->load->view("admin/common/common_footer"); ?>
    <?php $this->load->view("admin/common/common_js"); ?>
    <?php $this->load->view("admin/common/components/datatable_js"); ?>
    <script src="<?php echo base_url() . "/" . $this->config->item('theme_admin'); ?>/plugins/chosen/chosen.jquery.min.js"></script>

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
              alert(msg);
            });
        });
      });
    </script>
    <script>
      $(function() {
        $(".select2").chosen();
      });
    </script>
    <script>
      $(function() {
        $("#country_id").chosen();
        $("#city_id").chosen();
        $("#country_id").change(function() {
          $('#city_id').html("");
          var country_id = $(this).val();

          $.ajax({
              method: "POST",
              url: '<?php echo site_url("admin/city_json"); ?>',
              data: {
                country_id: country_id
              }
            })
            .done(function(data) {

              $.each(data, function(index, element) {
                $('#city_id').append("<option value='" + element.city_id + "'>" + element.city_name + "</option>");
              });
              $("#city_id").trigger("chosen:updated");
            });
        });
      });
    </script>


</body>

</html>