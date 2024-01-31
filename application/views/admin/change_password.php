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
                            <h1>Change Password</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">User</a></li>
                                <li class="breadcrumb-item active">Change Password</li>
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
              <div class="card">

                <div class="card-body">

                  <form role="form" action="" method="post">
                    <div class="box-body">
                      <?php
                      echo $this->session->flashdata("message");
                      ?>
                      <div class="form-group">
                        <div class="row">
                          <div class="col-md-12">
                            <label for="old_password">Old Password</label>
                            <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password" />
                          </div>
                          <div class="col-md-12">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" />
                          </div>
                          <div class="col-md-12">
                            <label for="confirm_password">Repeat New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Repeat New Password" />
                          </div>
                        </div>
                      </div>
                     
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                      <button type="submit" name="change_password" class="btn btn-primary">Reset</button>

                    </div>
                  </form>
                </div>
              </div>
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
  

</body>

</html>