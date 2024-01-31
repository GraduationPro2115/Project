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
              <h1><?php echo $this->lang->line("Country"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Area"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Country"); ?></li>
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
                              <div class="">
                                <div class="form-group">
                                  <label for="country_name"> <?php echo $this->lang->line("Country Name"); ?></label>
                                  <input type="text" class="form-control" id="country_name" name="country_name" placeholder="<?php echo $this->lang->line("Country Name"); ?>" value="<?php echo $country->country_name; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="iso_2"> <?php echo $this->lang->line("ISO CODE 2"); ?></label>
                                    <input type="text" maxlength="2" class="form-control" id="iso_2" name="iso_2" placeholder="<?php echo $this->lang->line("ISO CODE 2"); ?>" value="<?php echo $country->iso_code_2; ?>" />
                                </div>

                                <div class="form-group">
                                    <label for="iso_3"> <?php echo $this->lang->line("ISO CODE 3"); ?></label>
                                    <input type="text" maxlength="3" class="form-control" id="iso_3" name="iso_3" placeholder="<?php echo $this->lang->line("ISO CODE 3"); ?>" value="<?php echo $country->iso_code_3; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="currency"> <?php echo $this->lang->line("Currency"); ?></label>
                                    <input type="text" maxlength="3" class="form-control" id="iso_3" name="currency" placeholder="<?php echo $this->lang->line("Currency"); ?>" value="<?php echo $country->currency; ?>" />
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                      <label for="status">
                                        <input type="checkbox" id="status" name="status" <?php if($country->status==1) { echo "checked"; } ?>  /> <?php echo $this->lang->line("Status"); ?>
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
        $("body").on("change",".tgl_checkbox",function(){
            var table = $(this).data("table");
            var status = $(this).data("status");
            var id = $(this).data("id");
            var id_field = $(this).data("idfield");
            var bin=0;
                                         if($(this).is(':checked')){
                                            bin = 1;
                                         }
            $.ajax({
              method: "POST",
              url: "<?php echo site_url("admin/change_status"); ?>",
              data: { table: table, status: status, id : id, id_field : id_field, on_off : bin }
            })
              .done(function( msg ) {
                alert(msg);
              }); 
        });
      });
    </script>
    <script>
    $(function(){
       $(".select2").select2();
       $(".select3").select2();
       $(".select4").select2();
       $("#country_id").change(function(){
        
            var country_id = $(this).val();
            $.ajax({
              method: "POST",
              url: '<?php echo site_url("admin/change_state"); ?>',
              data: { country_id: country_id }
            })
              .done(function( msg ) {
                
                    $("#state_id").html(msg);
                    $(".select3").select2();
              }); 
       }); 
       $("#state_id").change(function(){
        
            var state_id = $(this).val();
            var country_id = $("#country_id").val();     
            $.ajax({
              method: "POST",
              url: '<?php echo site_url("admin/change_city"); ?>',
              data: { state_id: state_id, country_id : country_id }
            })
              .done(function( msg ) {
               
                    $("#city_id").html(msg);
                    $(".select4").select2();
              }); 
       });
    });
    </script>
    
  </body>
</html>
