<div class="input-group date">
  <div class="input-group-addon">
    <i class="fa fa-calendar"></i>
  </div>
  <input type="text" class="form-control pull-right" value="<?php echo $date; ?>" id="datepicker">
</div>
<?php echo $date; ?>
<?php
if (isset($morning_appointment)) {
?>
  <h3><?php echo $this->lang->line("Morning :"); ?></h3>
  <button class="btn-choose-time" data-time="<?php echo $morning_time_slot; ?>" data-token="1" data-timeslot="<?php echo date("H:i A", strtotime($morning_time_slot)) . " Morning"; ?>"><?php echo date("H:i A", strtotime($morning_time_slot)); ?></button>
<?
}
?>
<?php
if (isset($afternoon_time_slot)) {
?>
  <h3><?php echo $this->lang->line("Afternoon :"); ?></h3>
  <button class="btn-choose-time" data-time="<?php echo $afternoon_time_slot; ?>" data-token="2" data-timeslot="<?php echo date("H:i A", strtotime($afternoon_time_slot)) . " Afternoon"; ?>"><?php echo date("H:i A", strtotime($afternoon_time_slot)); ?></button>
<?
}
?>
<?php
if (isset($evening_time_slot)) {
?>
  <h3><?php echo $this->lang->line("Evening :"); ?></h3>
  <button class="btn-choose-time" data-time="<?php echo $evening_time_slot; ?>" data-token="3" data-timeslot="<?php echo date("H:i A", strtotime($evening_time_slot)) . " Evening"; ?>"><?php echo date("H:i A", strtotime($evening_time_slot)); ?></button>
<?
}
?>
<script>
  //Date picker
  $('#datepicker').datepicker({
    autoclose: true,
    format: 'yyyy-m-d'
  }).on('changeDate', function(ev) {
    $("#date_choose").val($('#datepicker').val());
    onChooseTimeClick();
  });
</script>