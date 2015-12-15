<?php /*echo $this->Form->select($modelName.'.state_id',$data,
	array(
		'label' => false,
		'class' => 'form-control', 
		'id' => 'state',
		'required'=>false,
		'tabindex' => 8,
		'empty' => false)
	);*/
   echo $this->Form->input('state',array('type'=>'text','id'=>'state','placeholder'=>'State','class'=>'form-control', 'required' => false, 'label' => false, 'div' => false));?>
            <?php echo $this->Form->input($modelName.'][state_id',array('type'=>'hidden','id'=>'state_id','class'=>'form-control'));