<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Dashboard</title>
    <?php  $this->load->view("admin/common/common_css"); ?>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php  $this->load->view("admin/common/common_header"); ?>
      <!-- Left side column. contains the logo and sidebar -->
      <?php  $this->load->view("admin/common/common_sidebar"); ?>

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
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Add"); ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                           
                        </div>
                        <div class="box-body">
                        
                            <form role="form" action="" method="post">
                              <div class="box-body">
                              <?php 
                                echo $this->session->flashdata("message");
                               ?>
                                <div class="form-group">
                                    <div class="row">
                                    <div class="col-md-6">
                                        <label for="user_fullname"> <?php echo $this->lang->line("Full Name"); ?></label>
                                        <input type="text" class="form-control" id="user_fullname" name="user_fullname" placeholder="<?php echo $this->lang->line("Full Name"); ?>" />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="user_email"> <?php echo $this->lang->line("User Email"); ?></label>
                                        <input type="email" class="form-control" id="user_email" name="user_email" placeholder="<?php echo $this->lang->line("User Email"); ?>" />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="user_password"> <?php echo $this->lang->line("Password"); ?></label>
                                        <input type="password" class="form-control" id="user_password" name="user_password" placeholder="<?php echo $this->lang->line("Password"); ?>" />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="user_type"><?php echo $this->lang->line("User Type"); ?></label>
                                        <select class="form-control select2" name="user_type" id="user_type" style="width: 100%;">
                                            <?php foreach($user_types as $user_type){
                                                if($user_type->user_type_id == 2 || $user_type->user_type_id == 3){
                                                ?>
                                                <option value="<?php echo $user_type->user_type_id; ?>"><?php echo $user_type->user_type_title; ?></option>
                                                <?php
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                      <label for="status">
                                        <input type="checkbox" id="status" name="status"  />  <?php echo $this->lang->line("Status"); ?>
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
               
                
            </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      <?php  $this->load->view("admin/common/common_footer"); ?>  

      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <?php  $this->load->view("admin/common/common_js"); ?>
    <script>
      $(function () {
        
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });

      });
    </script>
    <script>
    $(function(){
       $(".select2").select2();
    });
    </script>
    
  </body>
</html>
