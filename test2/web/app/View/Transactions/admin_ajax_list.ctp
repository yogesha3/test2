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
//                            echo "<tr><td colspan='5' style='text-align:center'>No profession has been added yet. Please add a profession</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
		<?php  if($this->Paginator->numbers()){?>

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
		<script type="text/javascript">

			$(document).ready(function() {
				$('.delete').hover(function(){
					$('.delete').tooltip('enable');
				});
				$('.activeInactive').hover(function(){
					$('.activeInactive').tooltip('enable');
				});
			});
		</script>