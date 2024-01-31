<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Dashboard</title>
    <?php  $this->load->view("admin/common/common_css"); ?>
    <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datepicker/jquery.timepicker.min.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datepicker/datepicker3.css"); ?>">
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
              <h1><?php echo $this->lang->line("Schedule"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Clinic"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Schedule"); ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
              
                <div class="row">
                <form method="post" action="" enctype="multipart/form-data">
                        <div class="col-md-6">
                            <?php  if(isset($error)){ echo $error; }
                                    echo $this->session->flashdata('message'); ?>
                            <!-- general form elements -->
                            <div class="card card-primary">
                               
                                <!-- form start -->
                                
                                    <div class="card-body">
                                        <div class="row">
            			    				<div class="col-xs-12 col-sm-12 col-md-12">
        			    					    <div class="form-group">
                                                    <label for="morning_from"><?php echo $this->lang->line("Morning Time Schedule"); ?></label>
                                                    <div class="row">
                                                    <label class="col-md-4"><?php echo $this->lang->line("From"); ?></label>
                                                    <label class="col-md-4"><?php echo $this->lang->line("To"); ?></label>
                                                    <label class="col-md-4"><?php echo $this->lang->line("Interval"); ?></label>
                                                    <div class="col-md-4">
                                                        <input type="text" name="morning_from" id="morning_from" value="<?php echo (!empty($schedule) &&  $schedule->morning_time_start != "" ) ?  date("h:i A",strtotime( $schedule->morning_time_start )) :  _get_post_back($this,"morning_from"); ?>" class="form-control input-sm" placeholder="<?php echo $this->lang->line("HH:MM PP"); ?>"  />
                                                    </div>
                                                    <div class="col-md-4">
                                                      <input type="text" name="morning_to" id="morning_to" value="<?php echo (!empty($schedule) && $schedule->morning_time_end != "") ?  date("h:i A",strtotime( $schedule->morning_time_end )) :   _get_post_back($this,"morning_to"); ?>" class="form-control input-sm" placeholder="<?php echo $this->lang->line("HH:MM PP"); ?>"  />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="morning_interval" id="morning_interval"  class="form-control input-sm"  >
                                                            <option <?php if(!empty($schedule) && $schedule->morning_tokens == 05) { echo "selected"; } ?> >05</option>
                                                            <option <?php if(!empty($schedule) && $schedule->morning_tokens == 10) { echo "selected"; } ?> >10</option>
                                                            <option <?php if(!empty($schedule) && $schedule->morning_tokens == 15) { echo "selected"; } ?> >15</option>
                                                            <option <?php if(!empty($schedule) && $schedule->morning_tokens == 20) { echo "selected"; } ?> >20</option>
                                                            <option <?php if(!empty($schedule) && $schedule->morning_tokens == 25) { echo "selected"; } ?> >25</option>
                                                            <option <?php if(!empty($schedule) && $schedule->morning_tokens == 30) { echo "selected"; } ?> >30</option>
                                                        </select>
                                                    </div>

                                                    </div>
                                                </div>
        			                        </div>
                                            
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-12">
        			    					    <div class="form-group">
                                                    <label for="afternoon_from"><?php echo $this->lang->line("Afternoon Time Schedule"); ?></label>
                                                    <div class="row">
                                                    <label class="col-md-4"><?php echo $this->lang->line("From"); ?></label>
                                                    <label class="col-md-4"><?php echo $this->lang->line("To"); ?></label>
                                                    <label class="col-md-4"><?php echo $this->lang->line("Interval"); ?></label>
                                                    <div class="col-md-4">
                                                        <input type="text" name="afternoon_from" id="afternoon_from" value="<?php echo (!empty($schedule) && $schedule->afternoon_time_start != "") ?  date("h:i A",strtotime( $schedule->afternoon_time_start )) :  _get_post_back($this,"afternoon_from") ?>" class="form-control input-sm" placeholder="<?php echo $this->lang->line("HH:MM PP"); ?>"  />
                                                    </div>
                                                    <div class="col-md-4">
                                                      <input type="text" name="afternoon_to" id="afternoon_to" value="<?php echo (!empty($schedule) && $schedule->afternoon_time_end != "") ?  date("h:i A",strtotime( $schedule->afternoon_time_end )) :  _get_post_back($this,"afternoon_to") ?>" class="form-control input-sm" placeholder="<?php echo $this->lang->line("HH:MM PP"); ?>"  />
                                                    </div>
                                                    <div class="col-md-4">
                                                      <select name="afternoon_interval" id="afternoon_interval"  class="form-control input-sm"  >
                                                            <option <?php if(!empty($schedule) && $schedule->afternoon_tokens == 05) { echo "selected"; } ?> >05</option>
                                                            <option <?php if(!empty($schedule) && $schedule->afternoon_tokens == 10) { echo "selected"; } ?> >10</option>
                                                            <option <?php if(!empty($schedule) && $schedule->afternoon_tokens == 15) { echo "selected"; } ?> >15</option>
                                                            <option <?php if(!empty($schedule) && $schedule->afternoon_tokens == 20) { echo "selected"; } ?> >20</option>
                                                            <option <?php if(!empty($schedule) && $schedule->afternoon_tokens == 25) { echo "selected"; } ?> >25</option>
                                                            <option <?php if(!empty($schedule) && $schedule->afternoon_tokens == 30) { echo "selected"; } ?> >30</option>
                                                        </select>
                                                    </div>

                                                    </div>
                                                </div>
        			                        </div>
                                            
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-12">
        			    					    <div class="form-group">
                                                    <label for="evening_from"><?php echo $this->lang->line("Evening Time Schedule"); ?></label>
                                                    <div class="row">
                                                    <label class="col-md-4"><?php echo $this->lang->line("From"); ?></label>
                                                    <label class="col-md-4"><?php echo $this->lang->line("To"); ?></label>
                                                    <label class="col-md-4"><?php echo $this->lang->line("Interval"); ?></label>
                                                    <div class="col-md-4">
                                                        <input type="text" name="evening_from" id="evening_from" value="<?php echo (!empty($schedule) && $schedule->evening_time_start != "") ?  date("h:i A",strtotime( $schedule->evening_time_start)) :  _get_post_back($this,"evening_from") ?>" class="form-control input-sm" placeholder="<?php echo $this->lang->line("HH:MM PP"); ?>"  />
                                                    </div>
                                                    <div class="col-md-4">
                                                      <input type="text" name="evening_to" id="evening_to" value="<?php echo (!empty($schedule) && $schedule->evening_time_end != "") ?  date("h:i A",strtotime( $schedule->evening_time_end )) :  _get_post_back($this,"evening_to") ?>" class="form-control input-sm" placeholder="<?php echo $this->lang->line("HH:MM PP"); ?>"  />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select name="evening_interval" id="evening_interval"  class="form-control input-sm"  >
                                                            <option <?php if(!empty($schedule) && $schedule->evening_tokens == 05) { echo "selected"; } ?> >05</option>
                                                            <option <?php if(!empty($schedule) && $schedule->evening_tokens == 10) { echo "selected"; } ?> >10</option>
                                                            <option <?php if(!empty($schedule) && $schedule->evening_tokens == 15) { echo "selected"; } ?> >15</option>
                                                            <option <?php if(!empty($schedule) && $schedule->evening_tokens == 20) { echo "selected"; } ?> >20</option>
                                                            <option <?php if(!empty($schedule) && $schedule->evening_tokens == 25) { echo "selected"; } ?> >25</option>
                                                            <option <?php if(!empty($schedule) && $schedule->evening_tokens == 30) { echo "selected"; } ?> >30</option>
                                                        </select>
                                                    </div>

                                                    </div>
                                                </div>
        			                        </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12">
        			    					    <div class="form-group">
                                                    <label for="evening_from"><?php echo $this->lang->line("Working Days"); ?></label>
                                                    <?php 
                                                    $days = array();
                                                    if(!empty($schedule))
                                                        $days = explode(",",$schedule->working_days); ?>
                                                    <label for="sun">
                                                        <input type="checkbox" name="day[]" value="sun" <?php if(in_array('sun',$days)) { echo "checked"; } ?> id="sun" /> Sun
                                                    </label>
                                                    <label for="mon">
                                                        <input type="checkbox" name="day[]" value="mon" <?php if(in_array('mon',$days)) { echo "checked"; } ?> id="mon" /> Mon
                                                    </label>
                                                    <label for="tue">
                                                        <input type="checkbox" name="day[]" value="tue" <?php if(in_array('tue',$days)) { echo "checked"; } ?> id="tue" /> Tue
                                                    </label>

                                                    <label for="wed">
                                                        <input type="checkbox" name="day[]" value="wed" <?php if(in_array('wed',$days)) { echo "checked"; } ?> id="wed" /> Wed
                                                    </label>

                                                    <label for="thu">
                                                        <input type="checkbox" name="day[]" value="thu" <?php if(in_array('thu',$days)) { echo "checked"; } ?> id="thu" /> Thu
                                                    </label>
                                                    
                                                    <label for="fri">
                                                        <input type="checkbox" name="day[]" value="fri" <?php if(in_array('fri',$days)) { echo "checked"; } ?> id="fri" /> Fri
                                                    </label>
                                                    
                                                    <label for="sat">
                                                        <input type="checkbox" name="day[]" value="sat" <?php if(in_array('sat',$days)) { echo "checked"; } ?> id="sat" /> Sat
                                                    </label>

                                                </div>
                                            </div>
            			    			</div>
                                        	    <!--<div class="form-group">
                                                    <label for="evening_from">Working Days</label>
                                                    <select class="form-control" name="book_type">
                                                        <option value="queue" <?php if(!empty($schedule) && $schedule->book_type == "queue"){ echo "selected"; } ?> >Queue</option>
                                                        <option value="slot" <?php if(!empty($schedule) && $schedule->book_type == "slot"){ echo "selected"; } ?> >Slot</option>
                                                    </select>
                                                </div> -->
                                        <input type="submit" name="savebus" value="<?php echo $this->lang->line("Add"); ?>" class="btn btn-info btn-block"/>
            			    		 
            			    		</div>
           			    		
                                    
                            </div><!-- /.box -->
                        </div>
                        <div class="col-md-8">
            
                        </div>
                     
                        </form>

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
    <script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/datepicker/jquery.timepicker.min.js"); ?>"></script>

   <script>
   

$('#morning_from,#morning_to').timepicker({
    timeFormat: 'h:mm p',
    interval: 30,
    minTime: '6',
    maxTime: '12:00pm',
    startTime: '06:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});

$('#afternoon_from,#afternoon_to').timepicker({
    timeFormat: 'h:mm p',
    interval: 30,
    minTime: '12',
    maxTime: '06:00pm',
    startTime: '12:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});

$('#evening_from,#evening_to').timepicker({
    timeFormat: 'h:mm p',
    interval: 30,
    minTime: '18',
    maxTime: '10:00pm',
    startTime: '18:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});

   </script>      
    
  </body>
</html>
