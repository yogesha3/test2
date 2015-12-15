<?php
/**
 * Advertisement listing page
 * @author Laxmi Saini
 */

$this->Paginator->options(array('update' => '.panel-body', 'evalScripts' => true )); 
?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php echo $this->Html->link('Advertisements', array('controller' => 'advertisements', 'action' => 'index', 'admin' => true));?>
            </li>
            <li class="active"><?php echo "Advertisement List";?></li>
            <li class="search-box">
            <form class="sidebar-search">
                <div class="form-group">
                    <input type="text" id="searching" name="search" placeholder="Start Searching...">
                </div>
                <?php
                    $this->Js->get('#searching');
                    $this->Js->event('keyup',
                    $this->Js->request(array(
                            'controller'=>'advertisements',
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
            <h1><?php echo "Advertisement List";?>
            <?php echo $this->Element('records_per_page');?>                       
            </h1>
        </div>
        <?php
            $this->Js->get('#perpage');
            $this->Js->event('change',
            $this->Js->request(array(
                    'controller'=>'advertisements',
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
    <div class="col-md-12">
        <!-- start: BASIC TABLE PANEL -->
        <div class="panel panel-default">
            <div class="panel-body" >
                <table id="sample-table-1" class="table table-hover">
                    <thead>
                        <tr>
                            <th class="center">S.No.</th>
                            <th><?php echo 'Advertisement Title'; ?></th>
                            <th><?php echo $this->Paginator->sort('Profession.profession_name', 'Profession'); ?></th>
                            <th><?php echo $this->Paginator->sort('position', 'Position'); ?></th>
                            <th><?php echo $this->Paginator->sort('created', 'Added On'); ?></th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="AdvertisementContent">
                        <?php
                        $deleteUrl = 'admin/advertisements/delete/';
                        if (!empty($advertisements)) {                            
                            foreach ($advertisements as $ad) {                                
                                $adId = $ad['Advertisement']['id'];
                                $createdDate = date('m-d-Y h:i:s' , strtotime($ad['Advertisement']['created']));
                                switch ($ad['Advertisement']['position']) {
                                    case 0:
                                        $position_val = "Bottom";
                                        break;
                                    case 1:
                                        $position_val = "Right";
                                        break;
                                }
                        ?>
                                <tr>
                                    <td class="center"><?php echo $counter;?></td>
                                    <td class="hidden-xs"><?php echo ucfirst($ad['Advertisement']['title']); ?></td>
                                    <td class="hidden-xs"><?php echo (!empty($ad['Profession']['profession_name']))? ucfirst($ad['Profession']['profession_name']): "-"; ?></td>
                                    <td class="hidden-xs"><?php echo $position_val; ?></td>
                                    <td><?php echo $createdDate;?></td>
                                    <td class="center">
                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <?php
                                            echo $this->Html->link('<i class="fa fa-edit"></i>', array('controller' => 'advertisements', 'action' => 'admin_edit', $adId), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'Edit', 'data-placement' => 'top', 'escape' => false));
                                            echo '&nbsp;';
                                            echo $this->Html->link('<i class="fa fa-times fa fa-white"></i>', 'javascript:void(0)', 
                                                  array(
                                                        'class' => 'btn btn-xs btn-bricky tooltips delete',
                                                        'data-original-title' => 'Delete',
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop'=>'static',
                                                        'data-placement' => 'top',
                                                        'data-target' => '#popup',
                                                        'onclick'=>"popUp('".$deleteUrl."','".$adId."')",'escape' => false
                                                        ));
                                            ?>
                                        </div>
                                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                                            <div class="btn-group">
                                                <?php
                                                echo $this->Html->link('<i class="fa fa-cog"></i> <span class="caret"></span>', '#', array('data-toggle' => 'dropdown', 'class' => 'btn btn-primary dropdown-toggle btn-sm', 'escape' => false));

                                                $list = array(
                                                    $this->Html->link('<i class="fa fa-edit"></i> Edit', array('controller' => 'advertisements', 'action' => 'admin_edit', $adId), array('tabindex' => '-1', 'role' => 'menuitem', 'escape' => false)),
                                                    $this->Html->link('<i class="fa fa-times fa fa-white"></i> Delete', '#', array(
                                                        'class' => 'btn btn-xs  tooltips deleteAdvertisement',
                                                        'data-original-title' => 'Delete',
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop'=>'static',
                                                        'data-placement' => 'top',
                                                        'data-target' => '#popup',
                                                        'onclick'=>"popUp('".$deleteUrl."','".$adId."')",'escape' => false,
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
                        } else {
                            echo "<tr><td colspan='5' style='text-align:center'>No record found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <?php if ($this->Paginator->numbers()) { ?>
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
            <?php }
            echo $this->Js->writeBuffer(); 
            ?>
            </div>
        </div>
        <!-- end: BASIC TABLE PANEL -->
    </div>
</div>