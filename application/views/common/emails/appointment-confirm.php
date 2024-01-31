<p>Appointment Book for <?php echo $doctor->doct_name; ?></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table>
    <tr><td><?php echo $this->lang->line("App No :"); ?></td><td><?php echo $appointment->id;; ?></td></tr>
    <tr><td><?php echo $this->lang->line("Date :"); ?></td><td><?php echo date("d M, Y",strtotime($appointment->appointment_date)); ?></td></tr>
    <tr><td><?php echo $this->lang->line("Time :"); ?></td><td><?php echo date("H:i A",strtotime($appointment->start_time)); ?></td></tr>
    <tr><td><?php echo $this->lang->line("Patient Details:"); ?></td><td></td></tr>
    <tr><td><?php echo $this->lang->line("Name :"); ?></td><td><?php echo $appointment->app_name; ?></td></tr>
    <tr><td><?php echo $this->lang->line("Phone :"); ?></td><td><?php echo $appointment->app_phone; ?></td></tr>
    <tr><td><?php echo $this->lang->line("Email :"); ?></td><td><?php echo $appointment->app_email; ?></td></tr>
    <tr><td><?php echo $this->lang->line("Paid Amount :"); ?></td><td><?php echo $appointment->payment_amount; ?></td></tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Thanks</p>