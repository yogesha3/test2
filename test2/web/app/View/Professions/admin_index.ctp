<?php
/**
 * professions listing landing page
 * @author Laxmi Saini
 */
?>
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
                <?php echo $this->Html->link('Professions', array('controller' => 'professions', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active">Profession List</li>
            <li class="search-box">
            <form class="sidebar-search">
                <div class="form-group">
                    <input type="text" id="searching" name="search" placeholder="Start Searching...">
                </div>
                <?php
                    $this->Js->get('#searching');
                    $this->Js->event('keyup',
                    $this->Js->request(array(
                            'controller'=>'professions',
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
            <h1>Profession List
                <?php echo $this->Element('records_per_page');?>       
            </h1>
        </div>
        <?php
            $this->Js->get('#perpage');
            $this->Js->event('change',
            $this->Js->request(array(
                    'controller'=>'professions',
                    'action'=>'index'),
                    array('async'=>true,
                          'update'=>'.panel-body',
                          'dataExpression'=>true,
                          /*'data' => $this->Js->serializeForm(array(
                                        'isForm' => true,
                                        'inline' => true
                                    )),*/
                            'data' => '$(\'#searching,#perpage\').serializeArray()',
                          'method'=>'post')
                 )
            );
        ?>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>
<div class="row">
    <div align="right" class="col-md-12"><?php echo $this->Html->link(
    '<i class="fa fa-plus">&nbsp;</i>Add Profession',
    array(
        'controller' => 'professions',
        'action' => 'addProfession',
        'full_base' => true
    ), array('escape' => false,'style'=>'font-weight: bold;'));?></div>
	<div align="right" class="col-md-12"><?php echo $this->Html->link(
    '<i class="clip-folder-download">&nbsp;</i>Download List',
    array(
        'controller' => 'professions',
        'action' => 'exportProfession',
        'full_base' => true
    ), array('escape' => false,'style'=>'font-weight: bold;'));?></div>
    <div class="col-md-12">
        <!-- start: BASIC TABLE PANEL -->
        <div class="panel panel-default">

            <div class="panel-body" >
                <table id="sample-table-1" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="center">S.No.</th>
                            <th><?php echo $this->Paginator->sort('ProfessionCategory.name', 'Category Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('profession_name', 'Profession Name'); ?></th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="professionContent">
                        <?php
                        //$deleteUrl = 'admin/professions/delete/';
                        if (!empty($professions)) {
                            foreach ($professions as $profession) {
                                $professionId = $profession['Profession']['id'];
                        ?>
                                <tr>
                                    <td class="center"><?php echo $counter;?></td>
                                    <td class="hidden-xs"><?php echo ucfirst($profession['ProfessionCategory']['name']); ?></td>
                                    <td class="hidden-xs"><?php echo ucfirst($profession['Profession']['profession_name']); ?></td>
                                    <td class="center">
                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <?php
                                            echo $this->Html->link('<i class="fa fa-edit"></i>', array('controller' => 'professions', 'action' => 'admin_edit', $professionId), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'Edit', 'data-placement' => 'top', 'escape' => false));
                                            //echo '&nbsp;';
                                            //echo $this->Html->link('<i class="fa fa-times fa fa-white"></i>', 'javascript:void(0)', 
                                            //      array(
                                            //            'class' => 'btn btn-xs btn-bricky tooltips delete',
                                            //            'data-original-title' => 'Delete',
                                            //            'data-toggle' => 'modal',
                                            //            'data-backdrop'=>'static',
                                            //            'data-placement' => 'top',
                                            //            'data-target' => '#popup',
                                            //            'onclick'=>"popUp('".$deleteUrl."','".$professionId."')",'escape' => false
                                            //            ));
                                            ?>
                                        </div>
                                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                                            <div class="btn-group">
                                                <?php
                                                echo $this->Html->link('<i class="fa fa-cog"></i> <span class="caret"></span>', '#', array('data-toggle' => 'dropdown', 'class' => 'btn btn-primary dropdown-toggle btn-sm', 'escape' => false));

                                                $list = array(
                                                    $this->Html->link('<i class="fa fa-edit"></i> Edit', array('controller' => 'professions', 'action' => 'admin_edit', $professionId), array('tabindex' => '-1', 'role' => 'menuitem', 'escape' => false)),
                                                    $this->Html->link('<i class="fa fa-times fa fa-white"></i> Delete', '#', array(
                                                        'class' => 'btn btn-xs  tooltips deleteProfession',
                                                        'data-original-title' => 'Delete',
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop'=>'static',
                                                        'data-placement' => 'top',
                                                        'data-target' => '#popup',
                                                        'onclick'=>"popUp('".$deleteUrl."','".$professionId."')",'escape' => false,
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
//                            echo "<tr><td colspan='5' style='text-align:center'>No profession has been added yet. Please add a profession</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <?php
      /*$options = array(
                   'url'=> array(
                       'controller' => 'users', 
                       'action' => 'index', 
                       'perpage' => $('#perpage').val()
                    )
               );
      echo $this->Paginator->options($options);*/
      //echo $this->Paginator->options(array('url' => array("perPage"=>1)));
      
      //echo $this->Paginator->numbers();
      //echo $this->Paginator->next('Proximo', null, null, array('class' => 'disable'));
      //echo $this->Paginator->current();
    // pr($this->Paginator->numbers());
//                if($this->Paginator->counter('{:count}')>0){
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

 <div id="deleteConfirmation" class="modal fade modal-sm" tabindex="-1" data-width="350" style="display: none;">
    <div class="modal-header">
        <?php
        echo $this->Form->button('&times;', array('class' => 'close closeModel', 'data-dismiss' => 'modal', 'aria-hidden' => true));
        echo $this->Html->tag('h4', 'Delete Confirmation', array('class' => 'Delete Confirmation'));
        ?>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <?php
                echo $this->Html->tag('p', 'Are you sure you want to delete this profession?');
                ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?php
        echo $this->Form->button('Cancel', array('data-dismiss' => 'modal', 'class' => 'btn btn-light-grey closeModel'));
        echo '&nbsp;';
        echo $this->Form->postLink('Confirm', array('action' => 'delete', $professionId), array('class' => 'btn  btn-bricky tooltips', 'data-placement' => 'top', 'escape' => false)
        );
        ?>

    </div>
</div>

<script type="text/javascript">
    var disableFor='';
    $(document).ready(function() {        
        $(".deleteProfession").on("click", function() {
            var action = $(this).attr('data-action');
            disableFor=$(this).attr('data-id');
            $("form").attr('action', action);
        });
        $('.closeModel').click(function(){
          $("#"+disableFor).tooltip('disable');
        });
        $('.deleteProfession').hover(function(){
            $('.deleteProfession').tooltip('enable');
        });
        $('#deleteConfirmation').on('hide.bs.modal', function (e) {
           $("#"+disableFor).tooltip('disable');  
        });
    });
</script>
