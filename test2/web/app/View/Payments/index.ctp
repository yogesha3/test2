<h1>Please wait payment in process</h1>
<?php echo $this->Form->create('BusinessOwner',array('id'=>'PaymentDetailForm','type'=>'post','url'=>array('controller'=>'payments','action'=>'process')));?>
<?php echo $this->Form->input('CC_Number',array('type'=>'hidden'));?>
<?php echo $this->Form->input('CC_Name',array('type'=>'hidden'));?>
<?php echo $this->Form->input('cvv',array('type'=>'hidden'));?>
<?php echo $this->Form->input('expiration',array('type'=>'hidden'));?>
<?php echo $this->Form->input('memberShipPrice',array('type'=>'hidden'));?>
<script type="text/javascript">
    document.getElementById('PaymentDetailForm').submit(); 
</script>