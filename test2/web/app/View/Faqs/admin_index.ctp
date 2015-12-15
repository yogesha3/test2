<!-- start: PAGE HEADER -->
<?php $this->Paginator->options(array('update' => '.panel-body','evalScripts' => true));?>
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php
                echo $this->Html->link(' Pages', array('controller' => 'cms', 'action' => 'about', 'admin' => true));
                ?>
            </li>
            <li class="active">FAQ List</li>
            <li class="search-box">
	            <form class="sidebar-search">
	                <div class="form-group">
	                    <input type="text" id="searching" name="search" placeholder="Start Searching..." data-default="130">
	                </div>	                
	            </form>
            </li>
        </ol>
        <div class="page-header">
         <h1>FAQ List 
         <?php echo $this->Element('records_per_page');?>
                </h1>
        </div>

         <?php
            $this->Js->get('#perpage');
            $this->Js->event('change',
            $this->Js->request(array(
                    'controller'=>'faqs',
                    'action'=>'index',
                    'admin'=>true),
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
	<div align="right" class="col-md-12">
	<?php echo $this->Html->link('<i class="fa fa-plus">&nbsp;</i>Add FAQ',array('controller' => 'faqs','action' => 'add','admin'=>true,'full_base' => true), array('escape' => false,'style'=>'font-weight: bold;'));?>
	</div>
    <div class="col-md-12">
        <!-- start: BASIC TABLE PANEL -->
        <div class="panel panel-default">

            <div class="panel-body">
                <table id="sample-table-1" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="center">S.No.</th>
                            <th><?php echo $this->Paginator->sort('Faqcategorie.category', 'Category'); ?></th>
                            <th><?php echo $this->Paginator->sort('Faqcategorie.question', 'Question'); ?></th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                      
                        if (!empty($faqs)) {
                          //$statusUrl='admin/coupons/status/';
                          $deleteUrl='admin/faqs/delete/';
                            $counter = 0;
                            foreach ($faqs as $faq) {
                                $counter++;
                                $faqId=$faq['Faq']['id'];
                       ?>
                                <tr>
                                	<td class="center"><?php echo $counter;?></td>
                                	<td><?php echo $faq['Faqcategorie']['category_name']; ?></td>
                                    <td><?php echo $faq['Faq']['question']; ?></td>
                                    <td class="center">
                                   
                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <?php
                                            $cur_date=date('Y-m-d');
                                            echo $this->Html->link('<i class="fa fa-edit"></i>', array('controller' => 'faqs', 'action' => 'admin_edit', $faq['Faq']['id']), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'Edit', 'data-placement' => 'top', 'escape' => false));
                                            echo '&nbsp';
                                            echo $this->Html->link('<i class="fa fa-times fa fa-white"></i>', 'javascript:void(0)', 
                                                  array(
                                                        'class' => 'btn btn-xs btn-bricky tooltips delete',
                                                        'data-original-title' => 'Delete',
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop'=>'static',
                                                        'data-placement' => 'top',
                                                        'data-target' => '#popup',
                                                        'onclick'=>"popUp('".$deleteUrl."','".$faq['Faq']['id']."')",'escape' => false
                                                        ));
                                            ?>
                                        </div>
                                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                                            <div class="btn-group">
                                                <?php
                                                echo $this->Html->link('<i class="fa fa-cog"></i> <span class="caret"></span>', '#', array('data-toggle' => 'dropdown', 'class' => 'btn btn-primary dropdown-toggle btn-sm', 'escape' => false));

                                                $list = array(
                                                    $this->Html->link('<i class="fa fa-edit"></i> Edit', array('controller' => 'faqs', 'action' => 'admin_edit', $faq['Faq']['id']), array('tabindex' => '-1', 'role' => 'menuitem', 'escape' => false)),
                                                    $this->Html->link('<i class="fa fa-times fa fa-white"></i> Delete', '#', array(
                                                        'class' => 'btn btn-xs tooltips delete',
                                                        'data-original-title' => 'Delete',
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop'=>'static',
                                                        'data-placement' => 'top',
                                                        'data-target' => '#popup',
                                                        'onclick'=>"popUp('".$deleteUrl."','".$faq['Faq']['id']."')",
                                                        'escape' => false,'style'=>"text-align:left"), false)
                                                );
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
                  'controller'=>'faqs',
                  'action'=>'index',
                  'admin'=>true),
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
