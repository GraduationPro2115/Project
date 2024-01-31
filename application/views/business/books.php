<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Calendar</title>
  
  <?php  $this->load->view("admin/common/common_css"); ?>  
  
  <?php echo link_tag($this->config->item('theme_admin') . '/plugins/fullcalendar/fullcalendar.min.css'); ?>
  <?php echo link_tag($this->config->item('theme_admin') . '/plugins/datepicker/datepicker3.css'); ?>
  <!-- bootstrap datepicker -->
  <?php echo link_tag($this->config->item('theme_admin') . '/plugins/chosen/chosen.min.css'); ?>

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
              <h1><?php echo $this->lang->line("Calender"); ?></h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Clinic"); ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo $this->lang->line("Appointment"); ?></a></li>
                <li class="breadcrumb-item active"><?php echo $this->lang->line("Calender"); ?></li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <!-- /. box -->
          <div class="card card-solid">
            <div class="card-header with-border">
              <h3 class="card-title"><?php echo $this->lang->line("Add New"); ?></h3>
            </div>
            <div class="card-body">
              <div class="" style="width: 100%; margin-bottom: 10px;">
                <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                <div class="form-group">
                    <label for="name"><?php echo $this->lang->line("Full Name"); ?></label>
                    <input type="text" class="form-control input-sm" required="" name="name" id="u_name" placeholder="<?php echo $this->lang->line("Full Name"); ?>" />
                </div>
                <div class="form-group">
                    <label for="email"><?php echo $this->lang->line("Email"); ?></label>
                    <input type="email" class="form-control input-sm" required="" name="email" id="u_email" placeholder="<?php echo $this->lang->line("Email"); ?>" />
                </div>
                <div class="form-group">
                    <label for="phone"><?php echo $this->lang->line("Contact No"); ?></label>
                    <input type="tel" class="form-control input-sm" required="" name="phone" id="u_phone" placeholder="<?php echo $this->lang->line("Contact No"); ?>" />
                </div>
                <div class="form-group">
                    <label for="date_choose"><?php echo $this->lang->line("Choose Date"); ?></label>
                    <input type="text" class="form-control input-sm" required="" name="date_choose" id="date_choose" placeholder="<?php echo $this->lang->line("Choose Date"); ?>" />
                </div>
                <?php if(_get_current_user_type_id($this) == 1){
                    ?>
                    <input type="hidden" name="doct_id" value="<?php echo _get_current_user_id($this); ?>" id="doct_id" />
                    <?php
                }else{ ?>
                <div class="form-group">
                    <label class=""><?php echo $this->lang->line("Choose Doctor"); ?></label>
                    <select data-placeholder="<?php echo $this->lang->line("Choose Doctor"); ?>" id="doct_id" class="form-control chosen-select"  style="width:100%;" name="doct_id"  >
                        <option value=""></option>
                        <?php foreach($doctors as $doctor){ ?>
                            <option  value="<?php echo $doctor->doct_id; ?>"  > <?php echo $doctor->doct_name; ?> </option>
                        <?php } ?>
                    </select> 
                </div>
                <?php } ?>
                <div class="form-group">
                    <label class=""><?php echo $this->lang->line("Choose Services"); ?></label>
                    <select data-placeholder="<?php echo $this->lang->line("Choose Services"); ?>" id="service_ids" class="form-control chosen-select" multiple style="width:100%;" name="buscat[]"  >
                        <option value=""></option>
                        <?php foreach($services as $service){ ?>
                            <option  value="<?php echo $service->id; ?>" data-time="<?php echo $service->business_approxtime; ?>" > <?php echo $service->service_title; ?> </option>
                        <?php } ?>
                    </select> 
                </div>
                <div class="form-group">
                    <label for="time"><?php echo $this->lang->line("Choose Time"); ?></label>
                    <input type="hidden" name="bus_id" value="<?php echo $bus_id; ?>" hidden="" id="bus_id" />
                    <input type="hidden" name="time" hidden="" id="time_choose" />
                    <input type="hidden" name="token" hidden="" id="token_choose" />
                    <button class="btn btn-default form-control" id="btnChooseTime" onclick="onChooseTimeClick()"> No time choosen </button>
                </div>
                <button id="add-new-app" type="button" class="btn btn-primary btn-flat"><?php echo $this->lang->line("Add"); ?></button>
              </div>
              <!-- /input-group -->
            </div>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-primary">
            <div class="card-body">
            <?php if(_get_current_user_type_id($this) != 1){ ?>
              <form action="" method="post">
              <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">
                            <label class=""><?php echo $this->lang->line("Choose Doctor"); ?></label>
                            <select data-placeholder="<?php echo $this->lang->line("Choose Doctor"); ?>" id="doct_filter" class="form-control chosen-select"  style="width:100%;" name="doct_filter"  >
                                <option value=""><?php echo $this->lang->line("All Doctors"); ?></option>
                                <?php foreach($doctors as $doctor){ ?>
                                    <option  value="<?php echo $doctor->doct_id; ?>" <?php if($this->input->post("doct_filter") == $doctor->doct_id){ echo "selected"; } ?>  > <?php echo $doctor->doct_name; ?> </option>
                                <?php } ?>
                            </select> 
                           
                      </div>
                  </div>
                  <div class="col-md-1">
                     <input type="submit" style="margin-top: 20px;" name="filter" value="<?php echo $this->lang->line("Filter"); ?>" class="btn btn-small btn-default" />
                  </div>
              </div>
              </form>
            <?php } ?>
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php  $this->load->view("admin/common/common_footer"); ?>  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line("Appointment Details"); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
            <div class="app_details"></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="timeModal" tabindex="-1" role="dialog" aria-labelledby="timeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="timeModalLabel"><?php echo $this->lang->line("Appointment Time Slot"); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
            <div class="timeslot_details"></div>
      </div>
    </div>
  </div>
</div>


<?php  $this->load->view("admin/common/common_js"); ?> 
<!-- fullCalendar 2.2.5 -->
<script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/moment/moment.min.js"); ?>"></script>
  <script src="<?php echo base_url($this->config->item("theme_admin") . "/plugins/fullcalendar/fullcalendar.min.js"); ?>"></script>
  <script src="<?php echo base_url() . "/" . $this->config->item('theme_admin'); ?>/plugins/chosen/chosen.jquery.min.js"></script>
  <!-- bootstrap datepicker -->
  <script src="<?php echo base_url() . "/" . $this->config->item('theme_admin'); ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
  <script src="<?php echo base_url() . "/" . $this->config->item('theme_admin'); ?>/plugins/formvalidate/jquery.form-validator.min.js"></script>
  
<script type="text/javascript">
            var config = {
              '.chosen-select'           : {},
              '.chosen-select-deselect'  : {allow_single_deselect:true},
              '.chosen-select-no-single' : {disable_search_threshold:10},
              '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
              '.chosen-select-width'     : {width:"95%"}
            }
            for (var selector in config) {
              $(selector).chosen(config[selector]);
            }
          </script>
<!-- Page specific script -->
<script>
function onChooseTimeClick(){
    var foo = new Array();
    $('#service_ids :selected').each(function(i, selected){ 
                foo.push( $(selected).data("time") ); 
                  
    });
    var choose_date = $("#date_choose").val();
    var doct_id = $("#doct_id").val();
    //alert(foo.length);
    if(foo.length < 1){
        alert("Please choose service");
    }else if(choose_date == ""){ 
        alert("Please choose appointment date");
    }else if(doct_id == ""){ 
        alert("Please choose doctor");
    }else{
        var times_slot = foo.join(", ");
        var bus_id = $("#bus_id").val();
        
        var dates = $("#date_choose").val();
        $.ajax({
          method: "POST",
          url: "<?php echo site_url("business/get_schedule_slot"); ?>",
          data: { bus_id: bus_id,doct_id: doct_id, days : '3', start_date : dates }
        })
          .done(function( msg ) {
            $(".timeslot_details").html(msg);
                $('#timeModal').modal('show');        
          });
    }
    
}
function onEvenClick(appid){
    $.ajax({
      method: "POST",
      url: "<?php echo site_url("business/app_details"); ?>",
      data: { appid: appid }
    })
      .done(function( msg ) {
        $(".app_details").html(msg);
            $('#myModal').modal('show');        
    });
    
};
  $(function () {
    $("#date_choose").datepicker({
      startDate : '<?php echo date("m-d-Y"); ?>'
    });
    $("body").on("click","#add-new-app",function(){
        var foo = [];
        $('#service_ids :selected').each(function(i, selected){ 
                    foo.push( $(selected).val() ); 
                      
        });
        var name = $("#u_name").val();
        var email = $("#u_email").val();
        var phone = $("#u_phone").val();
        var doct_id = $("#doct_id").val();    
        if(foo.length < 1){
            alert("Please choose service");
        }else if(name == ""){
            alert("Please choose fullname");
        }else if(email == ""){
            alert("Please choose email");
        }else if(phone == ""){
            alert("Please choose phone");
        }else if(doct_id == ""){ 
            alert("Please choose doctor");
        }else{
            var services = foo.join(",");
            
            var time_slot = $("#time_choose").val();
            var time_token = $("#token_choose").val();
            var time_date = $("#date_choose").val();
            var bus_id = $("#bus_id").val();
            $.ajax({
              method: "POST",
              url: "<?php echo site_url("business/add_appointment"); ?>",
              data: {  user_id : '<?php echo _get_current_user_id($this); ?>', fullname : name, email : email, phone : phone, appointment_date : time_date, start_time : time_slot, time_token : time_token , services : services, bus_id : bus_id, doct_id : doct_id }
            })
              .done(function( msg ) {
              
                        if(msg.responce){
                        // retrieve the dropped element's stored Event Object
                        var originalEventObject = {
          title: msg.data.app_name // use the element's text as the event title
        };
                        
                        var copiedEventObject = $.extend({}, originalEventObject);
                
                        
                        copiedEventObject.start = msg.data.appointment_date+'T'+msg.data.start_time;
                        copiedEventObject.allDay = false;
                        //copiedEventObject.backgroundColor = $(this).css("background-color");
                        //copiedEventObject.borderColor = $(this).css("border-color");
                
                        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                        $("#u_name").val("");
                        $("#u_email").val("");
                        $("#u_phone").val("");
                        $("#service_ids option:selected").removeAttr("selected");
                        $("#doct_id option:selected").removeAttr("selected");
                        $("#service_ids").trigger("chosen:updated");
                        $("#doct_id").trigger("chosen:updated");
                        $("#time_choose").val("");
                        $("#token_choose").val("");
                        $("#date_choose").val("");
                        $("#btnChooseTime").html("No time choosen");  
                        
                        }else{
                            alert(msg.error);
                        }          
            });    
        }
    });
    $("body").on("click",".t_s_slot",function(){
       var time = $(this).data("slot");
       var token =  $(this).data("timetoken");
       $("#time_choose").val(time);
       $("#token_choose").val(token);
       $("#btnChooseTime").html($(this).data("slot"));
       $('#timeModal').modal('hide');
    });
    /*$("#doct_filter").change(function(){
        if($(this).val() != ""){
            var doct = $(this).val().split("_");
            var doct_id = doct[0];
            var bus_id = doct[1];
           
                $.ajax({
                  method: "POST",
                  url: "<?php echo site_url("api/get_appointments_business"); ?>",
                  data: {  doct_id : doct_id, bus_id : bus_id  }
                }).done(function( msg ) {
                        if(msg.responce){
                        alert(msg.data);
                           
                            $('#calendar').fullCalendar( {events : msg.data } );
                        }else{
                            alert(msg);
                        }          
                });
        }
    });
    */
    /* initialize the external events
     -----------------------------------------------------------------*/
    /*function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex: 1070,
          revert: true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        });

      });
    }*/

    //ini_events($('#external-events div.external-event'));

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
        $('#calendar').fullCalendar({
              header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
              },
              buttonText: {
                today: 'today',
                month: 'month',
                week: 'week',
                day: 'day'
              },
              //Random default events
              events: <?php echo json_encode($appointments) ?>,
              editable: true,
              droppable: true,
              slotDuration : "00:15", // this allows things to be dropped onto the calendar !!!
              dayClick: function(date, jsEvent, view) {
          if(view.name != 'month')
            return;
        
          $('#calendar').fullCalendar('changeView', 'agendaDay');
                        $('#calendar').fullCalendar('gotoDate', date);
        },
    });

   
  });
</script>
</body>
</html>
