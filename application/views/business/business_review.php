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
              <h1><?php echo $this->lang->line("Reviews"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Clinic"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Reviews"); ?></li>
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


              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-body">
                    <table id="example1" class="table table-bordered table-hover">
                      <thead>
                        <tr>

                          <th><?php echo $this->lang->line("User Name"); ?></th>
                          <th><?php echo $this->lang->line("Clinic Review"); ?></th>
                          <th><?php echo $this->lang->line("Clinic Ratings"); ?></th>
                          <th><?php echo $this->lang->line("Action"); ?></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($business_review as $list) { ?>
                          <tr>

                            <td><?php echo $list->user_fullname; ?></td>
                            <td><?php echo $list->reviews; ?></td>
                            <td><?php echo $list->ratings; ?></td>
                            <td>
                              <a href="<?php echo site_url("business/delete_business_review/" . $list->id); ?>" onclick="return confirm('Are you sure to delete..?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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


</body>

</html>