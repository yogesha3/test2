<?php 
echo $this->Paginator->options(array(
    'url' => array(
        "perpage"=>$perpage,
        "search"=>$search,
        'sort'=> $this->Session->read('sort'),
        'direction'=> $this->Session->read('direction'),
        'plan' => $plan,
        'start' => $startDate,
        'end' => $endDate
        ),
    'update' => '.panel-body',
    'evalScripts' => true
    ));
?>
<?php
echo $this->Html->css('../assets/plugins/bootstrap-datepicker/css/datepicker');
echo $this->Html->script('../assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker');
?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Transactions', array('controller' => 'transactions', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">Transaction List</li>
            <li class="search-box">
            <form class="sidebar-search">
                <div class="form-group">
                    <input type="text" id="searching" name="search" placeholder="Start Searching...">
                </div>
                <?php
                    $this->Js->get('#searching');
                    $this->Js->event('keyup',
                    $this->Js->request(array(
                            'controller'=>'transactions',
                            'action'=>'index'),
                            array('async'=>true,
                                  'update'=>'.panel-body',
                                  'dataExpression'=>true,
                                    'data' => '$(\'#searching,#perpage,#start,#end,#plan\').serializeArray()',
                                  'method'=>'post')
                         )
                    );
                ?>

            </form>
            </li>
        </ol>
        <div class="page-header">
            <h1>Transaction List
                <?php echo $this->Element('records_per_page');?>       
            </h1>
        </div>
        <?php
            $this->Js->get('#perpage');
            $this->Js->event('change',
            $this->Js->request(array(
                    'controller'=>'transactions',
                    'action'=>'index'),
                    array('async'=>true,
                          	'update'=>'.panel-body',
                          	'dataExpression'=>true,
                    		'data' => '$(\'#searching,#perpage,#start,#end,#plan\').serializeArray()',
                          	'method'=>'post')
                 )
            );
        ?>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>
<div class="row">
	<div align="right" class="col-md-12"><?php echo $this->Html->link(
    '<i class="clip-file-excel"> </i>Export to excel',
    array(
        'controller' => 'transactions',
        'action' => 'exportTransaction',
        'admin' => true,
        'full_base' => true
    ), array('escape' => false,'style'=>'font-weight: bold;'));?></div>
    <div class="col-md-12">
        <!-- start: BASIC TABLE PANEL -->
        <div class="panel panel-default">
         
            <div>
               <?php
                echo $this->Form->create('Transaction', array('url' => array('controller' => 'transactions', 'action' => 'index', 'admin'=>true), 'class' => 'smart-wizard form-horizontal', 'id' => 'filterBusinessOwners','inputDefaults' => array('error' => false)));
                ?> <p style="padding: 15px 5px 5px 20px;"><strong style="color:#707070;">Filter Transactions By :</strong></p>
                <table id="filter-table-1" class="table table-hover">
                    <thead></thead>
                    <tr>
                        <td>
                            <div id="meetingTimeDiv">
                                <div class="input-group bootstrap-timepicker col-md-12">
                                <label>Transaction From:</label>
                                <?php
                                    echo $this->Form->input('start',array('type'=>'text','label'=>false,'data-date-viewmode'=>'years','data-date-format'=>'dd-mm-yyyy','class'=>'form-control date-picker','id'=>'start','readonly'=>'readonly','autocomplete'=>"off"));
                                ?>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div id="meetingTimeDiv">
                                <div class="input-group bootstrap-timepicker col-md-12">
                                <label>Transaction To:</label>
                                <?php
                                    echo $this->Form->input('end',array('type'=>'text','label'=>false,'data-date-viewmode'=>'years','data-date-format'=>'dd-mm-yyyy','class'=>'form-control date-picker','id'=>'end','readonly'=>'readonly','autocomplete'=>"off"));
                                ?>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div id="meetingTimeDiv">
                                <div class="input-group bootstrap-timepicker col-md-12">
                                <label>Membership Plan:</label>
                                <?php 
                                $plan = array('local'=>'Local','global'=>'Global');
                            		echo $this->Form->select('plan', $plan,
                                	array(
                                        'label' => false, 
                                        'class' => 'form-control filter', 
                                        'id' => 'plan',
                                        'empty' => 'Select Plan'));
                            ?>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="input text filterd">
                                    <?php echo $this->Html->link($this->Form->button('<i class="fa fa-circle-arrow-left"></i>Clear Filter', array('type' => 'button', 'class' => 'btn btn-light-grey go-back cancel')), array('controller' => 'transactions', 'action' => 'index', 'admin' => true), array('escape' => false)); ?>
                                    <?php echo $this->Form->button('Filter <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-bricky '));?>
                            </div>
                        </td>
                       
                    </tr>
                </table>
                <?php echo $this->Form->end(); ?>
                
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <!-- start: BASIC TABLE PANEL -->
        <div class="panel panel-default">

            <div class="panel-body" >
                <table id="sample-table-1" class="table table-hover">
                    <thead>
                        <tr>
                        	<th>S.no</th>
                            <th><?php echo $this->Paginator->sort('transaction_id', 'Id'); ?></th>
                            <th><?php echo $this->Paginator->sort('created', 'Date'); ?></th>
                            <th><?php echo $this->Paginator->sort('modified', 'Recurring On'); ?></th>
                            <th><?php echo $this->Paginator->sort('BusinessOwner.fname', 'Billed To'); ?></th>
                            <th><?php echo $this->Paginator->sort('Transaction.status', 'Status'); ?></th>
                            <th><?php echo $this->Paginator->sort('Transaction.group_type', 'Plan'); ?></th>
                            <th><?php echo $this->Paginator->sort('Transaction.amount_paid', 'Amount'); ?></th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="professionContent">
                        <?php 
                        if (!empty($transaction)) {
                            
                            foreach ($transaction as $transactions) {
                                $transactionId = $transactions['Transaction']['transaction_id'];                             
                                ?>
                                <tr>
                                    <td class="center"><?php echo $counter;?></td>
                                    <td class="hidden-xs"><?php echo $transactionId; ?></td>
                                    <td><?php echo date('m-d-Y',strtotime($transactions['Transaction']['purchase_date']));?></td>
                                    <td><?php  echo date('m-d-Y',strtotime($transactions['Subscription']['next_subscription_date']));?></td>
                                    <td><?php echo $transactions['BusinessOwner']['fname'].' '.$transactions['BusinessOwner']['lname'];?></td>
                                    <td><?php echo ($transactions['Transaction']['status'] == 'settled') ? 'Success' : 'Failed';?></td>
                                    <td><?php echo (!empty($transactions['Transaction']['group_type'])) ? ucfirst($transactions['Transaction']['group_type']) : '-';?></td>
                                    <td><?php echo '$'.number_format($transactions['Transaction']['amount_paid'], 2);?></td>
                                    <td class="center">
                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <?php echo $this->Html->link('<i class="clip-search"></i>', array('controller' => 'transactions', 'action' => 'transactionDetail', $transactions['Transaction']['id'], 'admin' => true), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'View', 'data-placement' => 'top', 'escape' => false)); ?>
                                        </div>
                                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                                            <div class="btn-group">
                                                <?php
                                                echo $this->Html->link('<i class="fa fa-cog"></i> <span class="caret"></span>', '#', array('data-toggle' => 'dropdown', 'class' => 'btn btn-primary dropdown-toggle btn-sm', 'escape' => false, ));

                                                $list = array(
                                                    $this->Html->link('<i class="clip-search-2"></i> View', array('controller' => 'transactions', 'action' => 'transactionDetail', $transactions['Transaction']['id'], 'admin' => true), array('tabindex' => '-1', 'role' => 'menuitem','data-toggle' => 'modal', 'escape' => false,'data-backdrop'=>'static')),
                                                    
                                                );
                                                echo $this->Html->nestedList($list, array('class' => 'dropdown-menu pull-right', 'role' => 'menu'));
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                                $counter++;
                            }
                        }else{
                            echo "<tr><td colspan='9' style='text-align:center'>No record found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <?php
     if($this->Paginator->numbers()){
    ?>

    <div class="paging" style="float:right;">
        <ul class="pagination" style="margin:0px;">
            <li>
              <?php echo $this->Paginator->prev(__('Previous',true)); ?>      
          </li>
          <li>
              <?php echo $this->Paginator->numbers(array('separator'=>false)); ?>      
          </li>
          <li>
             <?php echo $this->Paginator->next(__('Next',true)); ?>
          </li>
        </ul>
    </div>
                <?php } ?>
    <?php echo $this->Js->writeBuffer(); ?>
            </div>
        </div>
        <!-- end: BASIC TABLE PANEL -->
    </div>
</div>
<script type="text/javascript">
    var disableFor='';	
    $(document).ready(function() {
    	$('#end').datepicker({
        	autoclose: true,
            format:"mm-dd-yyyy",
        }).on('changeDate', function(ev){
		if($('#start').val() != '' && $('#end').val() != '') {
		var start = new Date($('#start').val().replace(/-/g, "/"));
		var end = new Date($('#end').val().replace(/-/g, "/"));
		if (start.getTime() > end.getTime()) {
			//alert("The first date is after the second date!");
			$("#start").datepicker( "option", "maxDate", null );
			$("#start").datepicker( "option", "minDate", null );
			$("#start").val('');
			} 
		}
            var date = $(this).datepicker( "getDate" );
            $('#start').datepicker('setEndDate',date);          
        });

		$('#start').datepicker({
            autoclose: true,
            format:"mm-dd-yyyy",
        }).on('changeDate', function(ev){        
		if($('#end').val() != '') {
		var start = new Date($('#start').val().replace(/-/g, "/"));
		var end = new Date($('#end').val().replace(/-/g, "/"));
		if (start.getTime() > end.getTime()) {
			$("#end").datepicker( "option", "maxDate", null );
			$("#end").datepicker( "option", "minDate", null );
			$("#end").val('');
			} 
		}
			var date2 = $(this).datepicker( "getDate" );
            $('#end').datepicker('setStartDate',date2);          
        });
});
        
</script>
