
<?php 
  if(isset($passedArgs)){
    $this->Paginator->options(array('url' => $passedArgs,
    'update' => '#resultsDiv',
    'evalScripts' => true));
  }
?>
<div class="paging-counter">
  <?php
  if($this->Paginator->counter('{:count}')>0){
		echo $this->Paginator->counter(array(
			  'format' => 'Showing {:start} to {:end} of {:count} Record(s)'
// 			'format' => 'range'
// 			'separator' => ' of a total of '
		));
  }
	?>

</div>
<?php
	if ($this->params['paging'][$this->Paginator->defaultModel()]['pageCount'] > 1) {
?>  

<div class="paging" style="float:right;">
  <ul class="pagination" style="margin:0px;">
    <li>
      <?php	echo $this->Paginator->prev(__('Previous',true)); ?>      
    </li>
    <li>
      <?php echo $this->Paginator->numbers(array('separator'=>false)); ?>      
    </li>
    <li>
      <?php echo $this->Paginator->next(__('Next',true)); ?>
    </li>
  </ul>
</div>
<?php
  }