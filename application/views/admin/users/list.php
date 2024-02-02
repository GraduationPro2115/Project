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
              <h1><?php echo $this->lang->line("Users"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Users"); ?></a></li>
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
              <div class="card-header" >
                  <h1 class="card-title">Users</h1>
                  <div class="card-tools right">
                      <a href="<?php echo site_url("admin/add_user"); ?>" class="btn btn-default btn-sm">Add New</a>
                  </div>
              </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th> <?php echo $this->lang->line("Email"); ?></th>
                    <th> <?php echo $this->lang->line("Full Name"); ?></th>
                    <th> <?php echo $this->lang->line("User Type"); ?></th>
                    <th> <?php echo $this->lang->line("Status"); ?></th>

                    <th width="80"><?php echo $this->lang->line("Action"); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($users as $user) {
                  ?>
                    <tr>
                      <td><?php echo $user->user_email; ?></td>
                      <td><?php echo $user->user_fullname; ?></td>
                      <td><?php echo $user->user_type_title; ?></td>
                      <td><input class='tgl tgl-ios tgl_checkbox' data-table="users" data-status="user_status" data-idfield="user_id" data-id="<?php echo $user->user_id; ?>" id='cb_<?php echo $user->user_id; ?>' type='checkbox' <?php echo ($user->user_status == 1) ? "checked" : ""; ?> />
                        <label class='tgl-btn' for='cb_<?php echo $user->user_id; ?>'></label>
                      </td>
                      <td>
                        <div class="btn-group">
                        <a href="<?php echo site_url("admin/edit_user/" . $user->user_id); ?>" class="btn btn-success btn-sm"><i class="fa fa-edit"></i></a>
                            <a href="<?php echo site_url("admin/user_history/" . $user->user_id); ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                        <a href="<?php echo site_url("admin/delete_user/" . $user->user_id); ?>" onclick="return confirm('Are you sure to delete..?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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