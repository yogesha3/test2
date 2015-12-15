<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li><i class="clip-file"></i>
                <?php echo $this->Html->link('Shuffling', array('controller' => 'groupShuffles', 'action' => 'shufflingStep1', 'admin' => true));?>
            </li>
            <li class="active"> Shuffling List</li>
            
        </ol>
        <div class="page-header">
            <h1> Shuffling List</h1>
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
                            <th class="col-md-5 center">Group Shuffle Date</th>
                            <th>Status</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php         
                        if (!empty($dateRanges)) {
                        foreach ($dateRanges as $key => $dateRanges) {
                        ?>
                        <tr>
                            <td class="center"><?php echo $key+1;?></td>
                            <td class="col-md-5 center"><?php echo date('m-d-Y', $dateRanges['date']);?></td>
                            <td><?php echo $dateRanges['status'];?></td>
                            <td class="center">
                                <div class="visible-md visible-lg hidden-sm">
                                 <?php echo $this->Html->link('<i class="clip-search"></i>', array('controller' => 'groupShuffles', 'action' => 'shufflingStep2', $dateRanges['date'],'admin'=>true), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'View', 'data-placement' => 'top', 'escape' => false)); ?>           
                                </div>
                                <div class="visible-xs visible-sm hidden-md hidden-lg">
                                    <div class="btn-group">
                                        <?php echo $this->Html->link('<i class="fa fa-cog"></i> <span class="caret"></span>', '#', array('data-toggle' => 'dropdown', 'class' => 'btn btn-primary dropdown-toggle btn-sm', 'escape' => false));?>
                                        <?php $list = array( $this->Html->link('<i class="clip-search-2"></i> View', array('controller' => 'groupShuffles', 'action' => 'shufflingStep2', $dateRanges['date'],'admin'=>true), array('tabindex' => '-1', 'role' => 'menuitem', 'escape' => false)) );
                                            echo $this->Html->nestedList($list, array('class' => 'dropdown-menu pull-right', 'role' => 'menu')); ?>
                                        
                                    </div>
                                </div>
                            </td>
                        </tr>
						<?php
                        }
                        }else{
                            echo "<tr><td colspan='4' style='text-align:center'>No record found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>                
            </div>
        </div>
        <!-- end: BASIC TABLE PANEL -->
    </div>
</div>