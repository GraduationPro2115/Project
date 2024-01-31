<table class="table" >
    <thead>
        <tr>
            <td><?php echo $this->lang->line("Person :"); ?> <?php echo $appointment->app_name; ?><br />
                <?php echo $this->lang->line("Phone :"); ?> <?php echo $appointment->app_phone; ?><br />
                <?php echo $this->lang->line("Email :"); ?> <?php echo $appointment->app_email; ?></td>
            <td><?php echo $this->lang->line("Appointment :"); ?> <?php echo $appointment->appointment_date; ?><br />
                <?php echo $this->lang->line("Booked Time :"); ?> <?php echo $appointment->start_time; ?><br />
                <?php echo $this->lang->line("Slot :"); ?> <?php if ($appointment->time_token == "1") {
                                                                echo "Morning";
                                                            } else if ($appointment->time_token == "2") {
                                                                echo "After Noon";
                                                            } else if ($appointment->time_token == "1") {
                                                                echo "Evening";
                                                            } ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <?php if (!empty($doctor->doct_name)) { ?>
                    <?php echo $this->lang->line("Book For :"); ?> <span><?php echo $doctor->doct_name; ?></span>
                <?php } ?>
            </td>
        </tr>
    </thead>
    
</table>
<table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>

                            <th><?php echo $this->lang->line("Service"); ?></th>
                            <th><?php echo $this->lang->line("Amount"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $list) {
                            $amount = $list->service_price * $list->service_qty;
                            $discount = $list->service_discount * $amount / 100;
                            $amount = $amount - $discount;
                        ?>
                            <tr>

                                <td><?php echo $list->service_title; ?></td>
                                <td><?php echo $amount; ?></td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>