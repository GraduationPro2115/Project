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
                        <h1><?php echo "User History"; ?></h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#"><?php echo "User History"; ?></a></li>
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

                            <form role="form" action="<?php echo site_url("admin/save_user_history"); ?>" method="post" enctype="multipart/form-data">

                                <input type="hidden"  name="user_id" value="<?php echo $user_id; ?>">
                                <div class="box-body">
                                    <?php
                                    echo $this->session->flashdata("message");
                                    ?>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="name"> <?php echo "Name"; ?></label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo "Name"; ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="type"><?php echo "Type"; ?></label>
                                                <select class="form-control select2" name="type" id="type" style="width: 100%;" required>
                                                    <option value="<?php echo "prescription"; ?>"><?php echo "Prescription"; ?></option>
                                                    <option value="<?php echo "lab_report"; ?>"><?php echo "Lab Report"; ?></option>
                                                    <option value="<?php echo "x_ray"; ?>"><?php echo "X-Ray"; ?></option>
                                                    <option value="<?php echo "post_operation"; ?>"><?php echo "Post Operation"; ?></option>
                                                    <option value="<?php echo "other"; ?>"><?php echo "Other"; ?></option>

                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="doctor_name"> <?php echo "Doctor"; ?></label>
                                                <input type="text" class="form-control" id="doctor_name" name="doctor_name" placeholder="<?php echo "Doctor"; ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="hospital"> <?php echo "Hospital/Institution"; ?></label>
                                                <input type="text" class="form-control" id="hospital" name="hospital" placeholder="<?php echo "Hospital/Institution"; ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="date"> <?php echo "Date"; ?></label>
                                                <input type="date" class="form-control" id="date" name="date" placeholder="<?php echo "Date"; ?>" required>
                                            </div>
                                            <div class="col-sm-6">
                                                    <label class=""> <?php echo "Attachment"; ?></label>
                                                    <input type="file" name="attachment" onchange="readURL1(this)" class="form-control" accept="image/jpeg,pdf" required>
                                            </div>
                                            <div class="col-md-8">
                                                <label for="hospital"> <?php echo "Description"; ?></label>
                                                <textarea  class="form-control" rows="10" id="description" name="description" placeholder="<?php echo "Description"; ?>" ></textarea>
                                            </div>
                                        </div>
                                </div>

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
