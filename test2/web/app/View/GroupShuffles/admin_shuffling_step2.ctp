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
                            <th class="col-md-4 center">Group Meeting Time</th>
                            <th class="center">Group Count</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php         
                        if (!empty($timeslots)) {
                        foreach ($timeslots as $key => $timeslots) {
                        ?>
                        <tr>
                            <td class="center"><?php echo $key+1;?></td>
                            <td class="col-md-4 center"><?php echo date('h:i A', $timeslots['timeslot']['startTime'])." - ".date('h:i A', $timeslots['timeslot']['endTime']);?></td>
                            <td class="center"><?php echo $timeslots['timeslot']['group_count'];?></td>
                            <td class="center">
                                <div class="visible-md visible-lg hidden-sm">
                                 <?php echo $this->Html->link('<i class="clip-search"></i>', array('controller' => 'groupShuffles', 'action' => 'shufflingStep3', $shufflingDate,$timeslots['timeslot']['startTime'],'admin'=>true), array('class' => 'btn btn-xs btn-teal tooltips', 'data-original-title' => 'View', 'data-placement' => 'top', 'escape' => false)); ?>           
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