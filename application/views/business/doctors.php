<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Dashboard</title>
  <?php $this->load->view("admin/common/common_css"); ?>
  <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin")); ?>/plugins/richtexteditor/rte_theme_default.css">
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
          <div class="">
            <?php if (isset($error)) {
              echo $error;
            }
            echo $this->session->flashdata('message'); ?>
            <form method="post" action="" enctype="multipart/form-data">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><?php echo $this->lang->line("Doctor Login Details :"); ?></h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <label for="doct_email"><?php echo $this->lang->line("Doctor"); ?> <?php echo $this->lang->line("Email"); ?></label>
                        <input type="email" name="doct_email" id="doct_email" class="form-control input-sm" required="" placeholder="<?php echo $this->lang->line("Doctor"); ?> <?php echo $this->lang->line("Email"); ?>" data-toggle="floatLabel" data-value="no-js" />
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <div class="form-group">
                        <label for="doct_password"><?php echo $this->lang->line("Doctor"); ?> <?php echo $this->lang->line("Password"); ?></label>
                        <input type="password" name="doct_password" id="doct_password" class="form-control input-sm" required="" placeholder="<?php echo $this->lang->line("Doctor"); ?> <?php echo $this->lang->line("Password"); ?>" data-toggle="floatLabel" data-value="no-js" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="">
                <div class="card card-default">

                  <div class="card-body">


                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <label for="doct_name"><?php echo $this->lang->line("Doctor Name"); ?></label>
                          <input type="text" name="doct_name" id="doct_name" class="form-control input-sm" required="" placeholder="<?php echo $this->lang->line("Doctor Name"); ?>" data-toggle="floatLabel" data-value="no-js" />
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <label for="doct_phone"><?php echo $this->lang->line("Doctor Phone"); ?></label>
                          <input type="text" name="doct_phone" id="doct_phone" class="form-control input-sm" required="" placeholder="<?php echo $this->lang->line("Doctor Phone"); ?>" data-toggle="floatLabel" data-value="no-js" />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <label for="doct_degree"><?php echo $this->lang->line("Doctor Degree"); ?></label>
                          <input type="text" name="doct_degree" id="doct_degree" class="form-control input-sm" required="" placeholder="<?php echo $this->lang->line("Doctor Degree"); ?>" data-toggle="floatLabel" data-value="no-js" />
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                          <label for="doct_speciality"><?php echo $this->lang->line("Doctor Speciality"); ?></label>
                          <input type="text" name="doct_speciality" id="doct_speciality" class="form-control input-sm" required="" placeholder="<?php echo $this->lang->line("Doctor Speciality"); ?>" data-toggle="floatLabel" data-value="no-js" />
                        </div>
                      </div>
                    </div>


                    <div class="row">
                      <div class="col-sm-3">
                        <div class="bus-product thumbnail">

                          <?php
                          $proicon = $this->config->item('base_url') . "/" . $this->config->item('theme_folder') . "/image/generic-profile.png";
                          ?>
                          <div class="doct-pro-icon" style="background-image: url('<?php echo $proicon; ?>'); height: 100px; width: 100%; background-size: contain;"></div>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label class=""><?php echo $this->lang->line("Doctor Photo"); ?></label>
                          <input type="file" name="doct_logo" onchange="readDoctURL(this)" />
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label><?php echo $this->lang->line("Doctor Details :"); ?></label>
                      <textarea id="editor1" class="form-control" name="doct_about" placeholder="<?php echo $this->lang->line("Doctor Details :"); ?>" style="height:100px ;"></textarea>
                    </div>


                  </div>
                  <div class="card-footer">
                  <input type="submit" name="savebus" value="<?php echo $this->lang->line("Add"); ?>" class="btn btn-info btn-block" />
                  </div>
                </div>
                
              </div>
            </form>

          </div>
        </div>
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
 
    <?php $this->load->view("admin/common/common_footer"); ?>

    <?php $this->load->view("admin/common/common_js"); ?>
    <script>
      function readDoctURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function(e) {
            jQuery('.doct-pro-icon').css("background-image", "url(" + e.target.result + ")");
          };

          reader.readAsDataURL(input.files[0]);
        }
      }
    </script>

    <script src="<?php echo base_url($this->config->item("theme_admin")); ?>/plugins/richtexteditor/rte.js"></script>
    <script src="<?php echo base_url($this->config->item("theme_admin")); ?>/plugins/richtexteditor/plugins/all_plugins.js"></script>
    <script>
      var editor1cfg = {}
      editor1cfg.toolbar = "mytoolbar";
      editor1cfg.toolbar_mytoolbar = "{bold,italic}|{fontsize}|{justifyleft,justifycenter,justifyright,justifyfull}|{insertorderedlist,insertunorderedlist,inserthorizontalrule}|removeformat" +
        "#{undo,redo,fullscreenenter,fullscreenexit}";
      var editor1 = new RichTextEditor("#editor1", editor1cfg);
      //{fontname}|{forecolor,backcolor}
    </script>

</body>

</html>