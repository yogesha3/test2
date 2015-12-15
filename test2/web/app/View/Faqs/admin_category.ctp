<?php $this->Paginator->options(array('update' => '.panel-body','evalScripts' => true)); ?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Pages', array('controller' => 'cms', 'action' => 'about', 'admin' => true));?>
            </li>
            <li class="active">FAQ Category List</li>
            <li class="search-box">
            <form class="sidebar-search">
                <div class="form-group">
                    <input type="text" id="searching" name="search" placeholder="Start Searching...">
                    <!-- <button class="submit"><i class="clip-search-3"></i></button>-->
                </div>
                <?php
                    $this->Js->get('#searching');
                    $this->Js->event('keyup',
                    $this->Js->request(array(
                            'controller'=>'faqs',
                            'action'=>'category',
                            'admin'=>true),
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
            <h1>FAQ Category List
                <?php echo $this->Element('records_per_page');?>        
            </h1>
        </div>
        <?php
            $this->Js->get('#perpage');
            $this->Js->event('change',
            $this->Js->request(array(
                    'controller'=>'faqs',
                    'action'=>'category',
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
    <?php echo $this->Html->link('<i class="fa fa-plus">&nbsp;</i>Add Category',array('controller' => 'faqs','action' => 'addCategory','admin'=>true,'full_base' => true), array('escape' => false,'style'=>'font-weight: bold;'));?>
    </div>
    <div class="col-md-12">
        <!-- start: BASIC TABLE PANEL -->
        <div class="panel panel-default">

            <div class="panel-body" >
                <table id="sample-table-1" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="center">S.No.</th>
                            <th><?php echo $this->Paginator->sort('category_name', 'Category Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('created', 'Created Date'); ?></th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="categoryContent">
                        <?php
                    
                        if (!empty($categories)) {  
                            $deleteUrl = 'admin/faqs/deleteCategory/';                         
                            foreach ($categories as $categorie) {                     
                                $createdDate = date('m-d-Y' , strtotime($categorie['Faqcategorie']['created']));
                                ?>
                                <tr>
                                    <td class="center"><?php echo $counter;?></td>
                                    <td class="hidden-xs"><?php echo ucfirst($categorie['Faqcategorie']['category_name']); ?></td>
                                    <td><?php echo $createdDate;?></td>
                                    <td class="center">
                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <?php
                                            echo $this->Html->link('<i class="fa fa-edit"></i>', array('controller' => 'faqs', 'action' => 'admin_editCategory', $categorie['Faqcategorie']['id']), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'Edit', 'data-placement' => 'top', 'escape' => false));
                                            echo '&nbsp;';
                                            echo $this->Html->link('<i class="fa fa-times fa fa-white"></i>', 'javascript:void(0)', 
                                                      array(
                                                            'class' => 'btn btn-xs btn-bricky tooltips delete',
                                                            'data-original-title' => 'Delete',
                                                            'data-toggle' => 'modal',
                                                            'data-backdrop'=>'static',
                                                            'data-placement' => 'top',
                                                            'data-target' => '#popup',
                                                            'onclick'=>"popUp('".$deleteUrl."','".$categorie['Faqcategorie']['id']."')",'escape' => false
                                                            ));
                                            ?>
                                        </div>
                                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                                            <div class="btn-group">
                                                <?php
                                                echo $this->Html->link('<i class="fa fa-cog"></i> <span class="caret"></span>', '#', array('data-toggle' => 'dropdown', 'class' => 'btn btn-primary dropdown-toggle btn-sm', 'escape' => false));

                                                $list = array(
                                                    $this->Html->link('<i class="fa fa-edit"></i> Edit', array('controller' => 'faqs', 'action' => 'admin_editCategory', $categorie['Faqcategorie']['id']), array('tabindex' => '-1', 'role' => 'menuitem', 'escape' => false)),
                                                    $this->Html->link('<i class="fa fa-times fa fa-white"></i> Delete', '#', array(
                                                        'class' => 'btn btn-xs tooltips delete',
                                                        'data-original-title' => 'Delete',
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop'=>'static',
                                                        'data-placement' => 'top',
                                                        'data-target' => '#popup',
                                                        'onclick'=>"popUp('".$deleteUrl."','".$categorie['Faqcategorie']['id']."')",
                                                        'escape' => false,'style'=>"text-align:left"), false)
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
