<?php $datea = date("Y-m-d",strtotime(key($timeslot))); ?>
<div class="t_s_row">
        <div class="t_s_col_morning t_s_col">
            <span><?php echo $this->lang->line("Morning :"); ?></span>
        </div>
        <div class="t_s_col_afternoon t_s_col">
            <span><?php echo $this->lang->line("Afternoon :"); ?></span>
        </div>
        <div class="t_s_col_evening t_s_col">
            <span><?php echo $this->lang->line("Evening :"); ?></span>
        </div>
</div>
    <div class="t_s_row">
        
        <div class="t_s_col_morning t_s_col">
            <?php 
            $t_s = $timeslot["morning"];
            foreach($t_s as $slot){
                if($slot["time_token"] == 1){
                ?>
                <button class="t_s_slot btn <?php if($slot["is_booked"]){ echo "booked"; } ?>" <?php if(!$slot["is_booked"]) { ?> data-toggle="modal" data-target="#exampleModal"  data-date="<?php echo $date; ?>" data-slot="<?php echo $slot["slot"]; ?>" data-timetoken="<?php echo $slot["time_token"]; ?>" <?php } ?> ><?php echo $slot["slot"]; ?></button>
                <?php
                }
            } ?>
        </div>
        
        <div class="t_s_col_afternoon t_s_col">
            <?php 
            $t_s = $timeslot["afternoon"];
            foreach($t_s as $slot){
                if($slot["time_token"] == 2){
                ?>
                <button class="t_s_slot btn <?php if($slot["is_booked"]){ echo "booked"; } ?>" <?php if(!$slot["is_booked"]) { ?> data-toggle="modal" data-target="#exampleModal"  data-date="<?php echo $date; ?>" data-slot="<?php echo $slot["slot"]; ?>" data-timetoken="<?php echo $slot["time_token"]; ?>" <?php } ?>  ><?php echo $slot["slot"]; ?></button>
                <?php
                }
            } ?>
        </div>
        
        <div class="t_s_col_evening t_s_col">
            <?php 
            $t_s = $timeslot["evening"];
            foreach($t_s as $slot){
                if($slot["time_token"] == 3){
                ?>
                <button class="t_s_slot btn <?php if($slot["is_booked"]){ echo "booked"; } ?>" <?php if(!$slot["is_booked"]) { ?> data-toggle="modal" data-target="#exampleModal"  data-date="<?php echo $date; ?>" data-slot="<?php echo $slot["slot"]; ?>" data-timetoken="<?php echo $slot["time_token"]; ?>" <?php } ?>  ><?php echo $slot["slot"]; ?></button>
                <?php
                }                
            } ?>
        </div>
    </div>
  
