<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Dashboard</title>
    <?php  $this->load->view("admin/common/common_css"); ?>
    <?php echo link_tag($this->config->item('theme_admin') . '/plugins/chosen/chosen.min.css'); ?>
  <?php $this->load->view("admin/common/components/datatable_css"); ?>
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
              <h1><?php echo $this->lang->line("City"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Area"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("City"); ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                             <?php echo $this->lang->line("Edit"); ?>
                        </div>
                        <div class="card-body">
                            <?php echo $this->session->flashdata("message"); ?>
                            <form role="form" action="" method="post">
                              <div class="box-body">
                                <div class="form-group">
                                    <label for="country_id"> <?php echo $this->lang->line("Country"); ?> </label>
                                    <select class="form-control select2" name="country_id" id="country_id" style="width: 100%;">
                                      <?php foreach($countries as $country){
                                        ?>
                                        <option value="<?php echo $country->country_id; ?>" <?php if($country->country_id == $city->country_id) { echo "selected"; } ?> ><?php echo $country->country_name; ?></option>
                                        <?php
                                      } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                  <label for="city_name"> <?php echo $this->lang->line("City Name"); ?></label>
                                  <input type="text" class="form-control" id="city_name" name="city_name" placeholder="<?php echo $this->lang->line("City Name"); ?>" value="<?php echo $city->city_name; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="lat"> <?php echo $this->lang->line("Latitude"); ?></label>
                                    <input type="text"  class="form-control" id="lat" name="lat" placeholder="<?php echo $this->lang->line("Latitude"); ?>"  value="<?php echo $city->city_lat; ?>" />
                                </div>

                                <div class="form-group">
                                    <label for="lon"> <?php echo $this->lang->line("Longitude"); ?></label>
                                    <input type="text" class="form-control" id="lon" name="lon" placeholder="<?php echo $this->lang->line("Longitude"); ?>"  value="<?php echo $city->city_lon; ?>" />
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                      <label for="status">
                                        <input type="checkbox" id="status" name="status" <?php if($city->status == 1){ echo "checked"; } ?> /> <?php echo $this->lang->line("Status"); ?>
                                      </label>
                                    </div>
                                </div>
                              </div><!-- /.box-body -->
            
                              <div class="box-footer">
                                <button type="submit" class="btn btn-primary"> <?php echo $this->lang->line("Save"); ?></button>
                              </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                
                
                </div>
                
            </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      <?php  $this->load->view("admin/common/common_footer"); ?>  
      <?php  $this->load->view("admin/common/common_js"); ?>
      <?php $this->load->view("admin/common/components/datatable_js"); ?>
    <script src="<?php echo base_url() . "/" . $this->config->item('theme_admin'); ?>/plugins/chosen/chosen.jquery.min.js"></script>

    <script>
    $(function(){
       $(".select2").chosen();
    });
    </script>
    
  </body>
</html>
