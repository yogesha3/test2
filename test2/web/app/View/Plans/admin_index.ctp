<?php

/**
 * Plan listing page
 */
?>
<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php 
                 echo $this->Html->link('Plans',array('controller'=>'plans','action'=>'index','admin'=>true));
                ?>
            </li>
            <li class="active">
                Plan List
            </li>
        </ol>
        <div class="page-header">
            <?php 
            echo $this->Html->tag('h1', 'Manage Plans');
            ?>
            <!--<h1>Manage Plans<small></small></h1>-->
        </div>
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
                            <th>Plan Name</th>
                            <th class="hidden-xs">Membership Fee ($)</th>
                            <th>No. of Discounted Members</th>
                            <th class="hidden-xs">Discounted Price ($)</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                      
                        if (!empty($plans)) {
                            $counter = 0;
                            foreach ($plans as $plan) {
                                $counter++;
                                $planId=$plan['Plan']['id'];                               
                                ?>
                                <tr>
                                    <td class="center"><?php echo $counter; ?></td>
                                    <td class="hidden-xs"><?php echo $plan['Plan']['plan_name']; ?></td>
                                    <td class="hidden-xs"><?php echo $plan['Plan']['membership_price']; ?></td>
                                    <td class="hidden-xs"><?php echo $discount_member = (isset($plan['Plan']['discounted_members'])&& $plan['Plan']['discounted_members']!=0) ? $plan['Plan']['discounted_members'] : '-' ; ?></td>
                                    <td class="hidden-xs"><?php echo $discount_amt =  isset($plan['Plan']['discounted_amount']) ? $plan['Plan']['discounted_amount'] : '-'; ?></td>
                                    <td class="center">
                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <?php
                                            echo $this->Html->link('<i class="clip-search-2"></i>', array('controller'=>'plans','action'=>'admin_view',$planId), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'View', 'data-placement' => 'top', 'escape' => false));
                                            echo '&nbsp;';
                                            echo $this->Html->link('<i class="fa fa-edit"></i>', array('controller'=>'plans','action'=>'admin_edit',$planId), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'Edit', 'data-placement' => 'top', 'escape' => false));
                                            ?>
                                        </div>
                                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                                            <div class="btn-group">
                                                <?php
                                                echo $this->Html->link('<i class="fa fa-cog"></i> <span class="caret"></span>', '#', array('data-toggle' => 'dropdown', 'class' => 'btn btn-primary dropdown-toggle btn-sm', 'escape' => false));

                                                $list = array(
                                                    $this->Html->link('<i class="clip-search-2"></i> View', array('controller'=>'plans','action'=>'admin_view',$planId), array('tabindex' => '-1', 'role' => 'menuitem', 'escape' => false)),
                                                    $this->Html->link('<i class="fa fa-edit"></i> Edit', array('controller'=>'plans','action'=>'admin_edit',$planId), array('tabindex' => '-1', 'role' => 'menuitem', 'escape' => false)),
                                                );
                                                echo $this->Html->nestedList($list, array('class' => 'dropdown-menu pull-right', 'role' => 'menu'));
                                                ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            <?php
                            }
                        }
                       ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- end: BASIC TABLE PANEL -->
    </div>
</div>

