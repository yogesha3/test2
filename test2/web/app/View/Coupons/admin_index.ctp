<?php
/**
 * Coupons listing landing page
 * @author Rohan Julka
 */
?>
<!-- start: PAGE HEADER -->
<?php 
   $this->Paginator->options(array(
      'update' => '.panel-body',
      'evalScripts' => true
   ));
   $this->assign('title','Coupon Code');
?>
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php
                echo $this->Html->link(' Coupon Code', array('controller' => 'coupons', 'action' => 'index', 'admin' => true));
                ?>
            </li>
            <li class="active">Coupon List</li>
            <li class="search-box">
	            <form class="sidebar-search">
	                <div class="form-group">
	                    <input type="text" id="searching" name="search" placeholder="Start Searching..." data-default="130">	                    
	                </div>
	                
	            </form>
            </li>
        </ol>
        <div class="page-header">
         <h1>Coupon List            
             <?php echo $this->Element('records_per_page');?>
                </h1>
        </div>
         <?php
            $this->Js->get('#perpage');
            $this->Js->event('change',
            $this->Js->request(array(
                    'controller'=>'coupons',
                    'action'=>'index'),
                    array('async'=>true,
                          'update'=>'.panel-body',
                          'dataExpression'=>true,
                          'data' => '$(\'#searching,#perpage\').serializeArray()',
                          'method'=>'post')
                 )
            );
        ?>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- start: BASIC TABLE PANEL -->
        <div class="panel panel-default">

            <div class="panel-body">
                <table id="sample-table-1" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="center">S.No.</th>
                            <th><?php echo $this->Paginator->sort('coupon_code', 'Coupon Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('coupon_type', 'Coupon Type'); ?></th>
                            <th><?php echo $this->Paginator->sort('start_date', 'Start Date'); ?></th>
                            <th><?php echo $this->Paginator->sort('expiry_date', 'End Date'); ?></th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                      
                        if (!empty($coupons)) {
                          $statusUrl='admin/coupons/status/';
                          $deleteUrl='admin/coupons/delete/';
                            $counter = 0;
                            foreach ($coupons as $coupon) {
                                $counter++;
                                $couponId=$coupon['Coupon']['id'];
                                $couponCode=$coupon['Coupon']['coupon_code'];
                                $couponType=$coupon['Coupon']['coupon_type'];
                       ?>
                                <tr>
                                	<td class="center"><?php echo $counter;?></td>
                                	<td class=""><?php echo $couponCode; ?></td>
                                        <td class=""><?php echo ucfirst($couponType); ?></td>
                                    <td class="hidden-xs"><?php echo date('m-d-Y',strtotime($coupon['Coupon']['start_date'])); ?></td>
                                    <td class="hidden-xs"><?php echo date('m-d-Y',strtotime($coupon['Coupon']['expiry_date']));  ?></td>
                                    <td class="center">
                                   
                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <?php
                                            $menuItem='';
                                            $cur_date=date('Y-m-d');
                                            if($coupon['Coupon']['is_active']) {
                                            	$class='btn btn-xs cpn_status activeInactive active_coupon';
                                            	if($cur_date > $coupon['Coupon']['expiry_date']){
                                            		$class.=" cpn_expired";
                                            	}
                                               echo $this->Html->link('<i class="fa fa-check-square-o"></i>',"#", array(
                                                                'class' => 'btn-teal tooltips '.$class,
                                                                'data-id'=>$couponId,
                                                                'data-original-title'=>"Deactivate",
                                                                'data-placement'=>"top",
                                                                'data-toggle'=>"modal",
                                                                'data-target'=>"#popup",
                                                                'onclick'=>"popUp('".$statusUrl."','".$couponId."')",'escape' => false
                                               		));
                                               $menuItem=$this->Html->link('<i class="fa fa-check-square-o"></i> Active',"#",array('class' => $class, 'data-id'=>$couponId, 'data-placement'=>"top", 'data-toggle'=>"modal", 'data-target'=>"#popup", 'onclick'=>"popUp('".$statusUrl."','".$couponId."')",'escape' => false, 'style'=>'text-align:left'));
                                            }
                                            else {
                                            	$class="btn btn-xs cpn_status activeInactive inactive_coupon";
                                            	if($cur_date > $coupon['Coupon']['expiry_date']){
                                            		$class.=" cpn_expired";
                                            	}
                                            	echo $this->Html->link('<i class="fa fa-square-o"></i>',"#", array(
                                                                'class' => "btn-teal tooltips ".$class,
                                                                'data-id'=>$couponId,
                                                                'data-original-title'=>"Activate",
                                                                'data-placement'=>"top",
                                                                'data-toggle'=>"modal",
                                                                'data-target'=>"#popup",
                                                                'onclick'=>"popUp('".$statusUrl."','".$couponId."')",'escape' => false
                                            			));
                                            	$menuItem=$this->Html->link('<i class="fa fa-square-o"></i> Active',"#", array('class' => $class, 'data-id'=>$couponId, 'data-placement'=>"top", 'data-toggle'=>"modal", 'data-target'=>"#popup", 'onclick'=>"popUp('".$statusUrl."','".$couponId."')",'escape' => false, 'style'=>'text-align:left'));
                                            }                                                         
                                            echo '&nbsp;';
                                            echo $this->Html->link('<i class="fa fa-times fa fa-white"></i>', 'javascript:void(0)', 
                                                  array(
                                                        'class' => 'btn btn-xs btn-bricky tooltips delete',
                                                        'data-original-title' => 'Delete',
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop'=>'static',
                                                        'data-placement' => 'top',
                                                        'data-target' => '#popup',
                                                        'onclick'=>"popUp('".$deleteUrl."','".$couponId."')",'escape' => false,
                                                       'style'=>'text-align:left'
                                                        ));
                                            ?>
                                        </div>
                                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                                            <div class="btn-group">
                                                <?php
                                                echo $this->Html->link('<i class="fa fa-cog"></i> <span class="caret"></span>', '#', array('data-toggle' => 'dropdown', 'class' => 'btn btn-primary dropdown-toggle btn-sm', 'escape' => false));

                                                $list = array(
                                                    $menuItem,
                                                    $this->Html->link('<i class="fa fa-times fa fa-white"></i> Delete', 'javascript:void(0)', 
                                                            array(
                                                                'class' => 'btn btn-xs delete',
                                                                'data-toggle' => 'modal',
                                                                'data-backdrop'=>'static',
                                                                'data-placement' => 'top',
                                                                'data-target' => '#popup',
                                                                'onclick'=>"popUp('".$deleteUrl."','".$couponId."')",'escape' => false,
                                                                'style'=>'text-align:left'
                                                                )));
                                                echo $this->Html->nestedList($list, array('class' => 'dropdown-menu pull-right', 'role' => 'menu'));
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            <?php
                            //$counter++;
                            }
                        }
                        else {
                            echo "<tr><td colspan='6' style='text-align:center'>No record found</td></tr>";
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
   <?php
           $this->Js->get('#searching');
           $this->Js->event('keyup',
           $this->Js->request(array(
                  'controller'=>'coupons',
                  'action'=>'index'),
                     array('async'=>true,
                        'update'=>'.panel-body',
                        'dataExpression'=>true,
                        'data' => '$(\'#searching,#perpage\').serializeArray()',
                        'method'=>'post')
                    )
            );
                ?>
    <?php echo $this->Js->writeBuffer(); ?>
            </div>
        </div>
        <!-- end: BASIC TABLE PANEL -->
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function() {
    	$('body').on('click','.popup_btn',function(){
				$(this).css('pointer-events','none');
            });
		$('.delete').hover(function(){
			$('.delete').tooltip('enable');
		});
		$('.activeInactive').hover(function(){
			$('.activeInactive').tooltip('enable');
		});
    });
</script>
