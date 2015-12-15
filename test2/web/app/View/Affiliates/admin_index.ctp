<?php 
   $this->Paginator->options(array(
      'update' => '.panel-body',
      'evalScripts' => true
   )); 
?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Affiliates', array('controller' => 'affiliates', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active"><?php echo "Affiliate List";?></li>
            <li class="search-box">
            <form class="sidebar-search">
                <div class="form-group">
                    <input type="text" id="searching" name="search" placeholder="Start Searching...">
                </div>
                <?php
                    $this->Js->get('#searching');
                    $this->Js->event('keyup',
                    $this->Js->request(array(
                            'controller'=>'affiliates',
                            'action'=>'index'),
                            array('async'=>true,
                                  'update'=>'.panel-body',
                                  'dataExpression'=>true,
                                  'data' => '$(\'#searching,#perpage\').serializeArray()',
                                  'method'=>'post')
                         )
                    );
                ?>

            </form>
            </li>
        </ol>
        <div class="page-header">
            <h1><?php echo "Affiliate List";?>
                <?php echo $this->Element('records_per_page');?>     
            </h1>
        </div>
        <?php
            $this->Js->get('#perpage');
            $this->Js->event('change',
            $this->Js->request(array(
                    'controller'=>'affiliates',
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
            <div class="panel-body" >
                <table id="sample-table-1" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="center">S.No.</th>
                            <th><?php echo $this->Paginator->sort('Affiliate.name', 'Affiliate Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('Affiliate.email', 'Affiliate Email'); ?></th>
                            <th><?php echo $this->Paginator->sort('created', 'Added On'); ?></th>
                            <th>Conversion Rate(%)</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="professionContent">
                        <?php
                        $deleteUrl = 'admin/affiliates/affiliateDelete/';  
                        if (!empty($affiliates)) {                            
                            foreach ($affiliates as $affiliate) {
                                $affiliaterId 		= $affiliate['Affiliate']['id'];
                                $timestampForCreate = strtotime($affiliate['Affiliate']['created']);
                                $createdDate      	= date('m-d-Y' , $timestampForCreate);
                                $conversionRate		= (!empty($affiliate['Affiliate']['traffic_generated'])) ? ($affiliate['Affiliate']['total_conversion']/$affiliate['Affiliate']['traffic_generated']*100) : 0; 
                                ?>
                                <tr>
                                    <td class="center"><?php echo $counter;?></td>
                                    <td class="hidden-xs"><?php echo ucfirst($affiliate['Affiliate']['name']); ?></td>
                                    <td><?php echo $affiliate['Affiliate']['email'];?></td>
                                    <td><?php echo $createdDate;?></td> 
                                    <td><?php echo $this->Number->toPercentage($conversionRate);?></td>                                 
                                    <td class="center">
                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <?php                                            
                                            echo $this->Html->link('<i class="clip-search"></i>', '#', array('affiliate-val' => $affiliaterId,'class' => 'btn btn-xs btn-teal tooltips cursor', 'data-original-title' => 'View', 'data-placement' => 'top','data-backdrop'=>"static", 'data-toggle'=>"modal", 'data-target'=>"#popup",'escape' => false));
                                            echo "&nbsp;";
                                            echo $this->Html->link('<i class="fa fa-times fa fa-white"></i>', 'javascript:void(0)', 
                                                  array(
                                                        'class' => 'btn btn-xs btn-bricky tooltips delete deletesubscriber',
                                                        'data-original-title' => 'Delete',
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop'=>'static',
                                                        'data-placement' => 'top',
                                                        'data-target' => '#popup',
                                                        'onclick'=>"popUp('".$deleteUrl."','".$affiliaterId."')",'escape' => false
                                                        ));
                                            ?>
                                        </div>
                                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                                            <div class="btn-group">
                                                <?php
                                                echo $this->Html->link('<i class="fa fa-cog"></i> <span class="caret"></span>', '#', array('data-toggle' => 'dropdown', 'class' => 'btn btn-primary dropdown-toggle btn-sm', 'escape' => false, ));

                                                $list = array(
                                                    $this->Html->link('<i class="clip-search-2"></i> View', 'javascript:void(0)', array('tabindex' => '-1', 'data-toggle' => 'modal', 'escape' => false,'data-backdrop'=>'static', 'data-placement' => 'top', 'data-target' => '#popup','affiliate-val' => $affiliaterId, 'class' => 'btn btn-xs tooltips cursor affilates_mobile')),
                                                    $this->Html->link('<i class="fa fa-times fa fa-white"></i> Delete', '#', array(
                                                        'class' => 'btn btn-xs  tooltips deleteAdvertisement',
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop'=>'static',
                                                        'data-placement' => 'top',
                                                        'data-target' => '#popup',
                                                        'onclick'=>"popUp('".$deleteUrl."','".$affiliaterId."')",
                                                        'escape' => false,
                                                        'style'=>"text-align:left"), false)
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
                            echo "<tr><td colspan='5' style='text-align:center'>No record found</td></tr>";
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
    var affiliateDetailUrl = "<?php echo Router::url(array('controller'=>'affiliates','action'=>'affiliateDetail'));?>";
    var disableFor='';
    $(document).ready(function() {        
        $(".deletesubscriber").on("click", function() {
            var action = $(this).attr('data-action');
            disableFor=$(this).attr('data-id');
            $("form").attr('action', action);
        });
        $('.closeModel').click(function(){
          $("#"+disableFor).tooltip('disable');
        });
        $('.deletesubscriber').hover(function(){
            $('.deletesubscriber').tooltip('enable');
        });
        $('#deleteConfirmation').on('hide.bs.modal', function (e) {
           $("#"+disableFor).tooltip('disable');  
        });

        $('.cursor').click(function(){
    		affiliateId = $(this).attr('affiliate-val');
    		$.ajax({
    			url: affiliateDetailUrl,
    	        context: document.body,
    	        method: "POST",
    	        data: { affiliateId: affiliateId},
    	        success: function(data){
    	            $("#popup").html(data);
    	        },
    	    });
    	});

        $('.cursor').hover(function(){
            $('.cursor').tooltip('enable');
        });
        	
    });
</script>