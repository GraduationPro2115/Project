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
              <h1><?php echo $this->lang->line("Categories"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Categories"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("List"); ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <?php if (isset($error)) {
                echo $error;
              }
              echo $this->session->flashdata('success_req'); ?>
              <div class="card card-primary">
                <div class="card-body table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center"><?php echo $this->lang->line("Cat ID"); ?></th>
                        <th> <?php echo $this->lang->line("Title"); ?></th>
                        <!--<th>Parent ID</th>-->
                        <th> <?php echo $this->lang->line("Image"); ?></th>
                        <th style="width: 40%;">Description</th>
                        <th> <?php echo $this->lang->line("Status"); ?></th>
                        <th class="text-center" style="width: 100px;"> <?php echo $this->lang->line("Action"); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($allcat as $acat) { ?>
                        <tr>
                          <td class="text-center"><?php echo $acat->id; ?></td>
                          <td><?php for ($i = 0; $i < $acat->leval; $i++) {
                                echo "- ";
                              }
                              echo $acat->title; ?></td>
                          <!--<td><?php echo $acat->parent; ?></td>-->
                          <td><?php if ($acat->image != "") { ?><div class="cat-img" style="width: 50px; height: 50px;"><img width="100%" height="100%" src="<?php echo base_url('/uploads/admin/category/' . $acat->image); ?>" /></div> <?php } ?></td>
                          <td><?php echo substr($acat->description, 0, 100); ?></td>
                          <td><?php if ($acat->status == "1") { ?><span class="label label-success"><?php echo $this->lang->line("Active"); ?></span><?php } else { ?><span class="label label-danger"><?php echo $this->lang->line("Deactive"); ?></span><?php } ?></td>
                          <td class="text-center">
                            <div class="btn-group">
                              <?php echo anchor('admin/editcategory/' . $acat->id, '<i class="fa fa-edit"></i>', array("class" => "btn btn-success btn-sm")); ?>
                              <?php echo anchor('admin/deletecat/' . $acat->id, '<i class="fa fa-times"></i>', array("class" => "btn btn-danger btn-sm", "onclick" => "return confirm('Are you sure delete?')")); ?>

                            </div>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>
          <!-- Main row -->
        </div>
      </section><!-- /.content -->
      </aside><!-- /.right-side -->
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
            alert(msg);
          });
      });
    });
  </script>
</body>

</html>