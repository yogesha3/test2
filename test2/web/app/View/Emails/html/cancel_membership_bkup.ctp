Dear <?php echo $businessowner;?>,<br/><br/>
<?php
if($case == 'participant') { ?>
content for participant
<?php } else if($case == 'co-leader'){ ?>
content for co-leader
<?php } else if($case == 'leader'){ ?>
content for leader
<?php }?>

<br/>
<br/>
Thanks,<br/>
Foxhopr Team