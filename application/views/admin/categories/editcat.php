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
                            <h1><?php echo $this->lang->line("Categories"); ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Categories"); ?></a></li>
                                <li class="breadcrumb-item active"><?php echo $this->lang->line("Edit"); ?></li>
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
                            <!-- general form elements -->
                            <div class="card card-default">
                                <div class="card-header">
                                    <h3 class="card-title"><?php echo $this->lang->line("Edit"); ?></h3>
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class=""> <?php echo $this->lang->line("Categories Title :"); ?> <span class="text-danger">*</span></label>
                                            <input type="text" name="cat_title" class="form-control" placeholder="<?php echo $this->lang->line("Categories Title :"); ?>" value="<?php echo $getcat->title; ?>" />
                                            <input type="hidden" name="cat_id" class="form-control" placeholder="Categories id" value="<?php echo $getcat->id; ?>" />
                                        </div>
                                        <!--<div class="form-group">
                                            <label class="">  <?php echo $this->lang->line("Parent Category :"); ?><span class="text-danger">*</span></label>
                                            <select class="text-input form-control" name="parent">
                                                <option value="0">No Parent</option>
                                                <?php
                                                echo printCategory(0, 0, $this, $getcat);
                                                function printCategory($parent, $leval, $th, $getcat)
                                                {

                                                    $q = $th->db->query("SELECT a.*, Deriv1.count FROM `categories` a  LEFT OUTER JOIN (SELECT `parent`, COUNT(*) AS count FROM `categories` GROUP BY `parent`) Deriv1 ON a.`id` = Deriv1.`parent` WHERE a.`parent`=" . $parent);
                                                    $rows = $q->result();

                                                    foreach ($rows as $row) {
                                                        if ($row->count > 0) {

                                                            //print_r($row) ;
                                                            //echo "<option value='$row[id]_$co'>".$node.$row["alias"]."</option>";
                                                            printRow($row, $getcat);
                                                            printCategory($row->id, $leval + 1, $th, $getcat);
                                                        } elseif ($row->count == 0) {
                                                            printRow($row, $getcat);
                                                            //print_r($row);
                                                        }
                                                    }
                                                }
                                                function printRow($d, $getcat)
                                                {

                                                    // foreach($data as $d){

                                                ?>
                                                     <option value="<?php echo $d->id; ?>" <?php if ($getcat->parent == $d->id) {
                                                                                                echo 'selected=""';
                                                                                            } ?> ><?php for ($i = 0; $i < $d->leval; $i++) {
                                                                                                                                                                echo "_";
                                                                                                                                                            }
                                                                                                                                                            echo $d->title; ?></option>
                                                        
                                                     <?php } ?> 
                                            </select>
                                        </div>-->
                                        <div class="form-group">
                                            <label class=""><?php echo $this->lang->line("Categories Description :"); ?></label>
                                            <textarea name="cat_descri" class="textarea" placeholder="<?php echo $this->lang->line("Categories Description :"); ?>" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php echo $getcat->description; ?></textarea>
                                            <p class="help-block"> <?php echo $this->lang->line("Categories Description :"); ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label> <?php echo $this->lang->line("Categories Image:"); ?> </label>
                                            <div class="cat-img pull-right" style="width: 50px; height: 50px;"><img width="100%" height="100%" src="<?php echo base_url('/uploads/admin/category/' . $getcat->image); ?>" /></div>
                                            <input type="file" name="cat_img" />
                                        </div>
                                        <div class="form-group">
                                            <p class="help-block"> <?php echo $this->lang->line("Set Categories Status."); ?></p>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="cat_status" id="optionsRadios1" value="1" <?php if ($getcat->status == 1) {
                                                                                                                            echo 'checked=""';
                                                                                                                        } ?> />
                                                    <?php echo $this->lang->line("Active"); ?>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="cat_status" id="optionsRadios2" value="0" <?php if ($getcat->status == 0) {
                                                                                                                            echo 'checked=""';
                                                                                                                        } ?> />
                                                    <?php echo $this->lang->line("Deactive"); ?>
                                                </label>
                                            </div>
                                            
                                        </div>
                                    </div><!-- /.box-body -->

                                    <div class="card-footer">
                                        <input type="submit" class="btn btn-primary" name="savecat" value="<?php echo $this->lang->line("Save"); ?>" />

                                    </div>
                                </form>
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
    <script>
        $(function() {

            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
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