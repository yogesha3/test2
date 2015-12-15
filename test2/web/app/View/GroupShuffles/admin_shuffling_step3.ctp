<style>
#shuffling_group a{color:#000000}
.cursor {cursor:pointer;}
</style>
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
                            <th class="col-md-5 center">Group Name</th>
                            <th class="center">Group Type</th>
                            <th class="center">Group Member Count</th>                            
                        </tr>
                    </thead>
                    <tbody id="shuffling_group">
                        <?php         
                        if (!empty($localGroupList)) {
                        foreach ($localGroupList as $key => $group) {
                        ?>
                        <tr>
                            <td class="center"><?php echo $key+1;?></td>
                            <td class="col-md-5 center"><a class="cursor" group-val="<?php echo $group['Group']['id']?>" data-toggle="modal" data-target="#popup"><?php echo Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($group['Group']['id']);?></a></td>
                            <td class="center"><a class="cursor" group-val="<?php echo $group['Group']['id']?>" data-toggle="modal" data-target="#popup"><?php echo ucfirst($group['Group']['group_type']);?></a></td>
                            <td class="center"><a class="cursor" group-val="<?php echo $group['Group']['id']?>" data-toggle="modal" data-target="#popup"><?php echo $group['Group']['total_member'];?></a></td>                         
                        </tr>
						<?php
                        }
                        ?>
                        <tr>
                            <td colspan='4' class="center">
                            <?php if(time()>=$shufflingDate && count($localGroupList)>1){?>
			                <div class="paging" style="float:right;">
						        <div class="col-sm-7">
			                        <?php echo $this->Form->button('Let\'s Shuffle  <i class="fa fa-arrow-circle-right"></i>', array('id'=>'local','type' => 'button', 'class' => 'btn btn-bricky pull-right letgroupshuffle')); ?>
			                    </div>
						    </div>
						    <div id="local-shuffle-params" shuffle-date="<?php echo $shufflingDate?>" shuffle-time="<?php echo $startTimeSlot?>"></div>
						    <?php }?> 
                            </td>                         
                        </tr>                        
                        <?php }?>
                        
                        <?php        
                        if (!empty($globalGroupList)) {
                        foreach ($globalGroupList as $key1 => $group) {
                        ?>
                        <tr>
                            <td class="center"><?php echo $key1+1;?></td>
                            <td class="col-md-5 center"><a class="cursor" group-val="<?php echo $group['Group']['id']?>" data-toggle="modal" data-target="#popup"><?php echo Configure::read('GROUP_PREFIX').' '.$this->Encryption->decode($group['Group']['id']);?></a></td>
                            <td class="center"><a class="cursor" group-val="<?php echo $group['Group']['id']?>" data-toggle="modal" data-target="#popup"><?php echo ucfirst($group['Group']['group_type']);?></a></td>
                            <td class="center"><a class="cursor" group-val="<?php echo $group['Group']['id']?>" data-toggle="modal" data-target="#popup"><?php echo $group['Group']['total_member'];?></a></td>                         
                        </tr>
						<?php
                        }
                        ?>
                        <tr>
                            <td colspan='4' class="center">
                            <?php if(time()>=$shufflingDate && count($globalGroupList)>1){?>
			                <div class="paging" style="float:right;">
						        <div class="col-sm-7">
			                        <?php echo $this->Form->button('Let\'s Shuffle  <i class="fa fa-arrow-circle-right"></i>', array('id'=>'global','type' => 'button', 'class' => 'btn btn-bricky pull-right letgroupshuffle')); ?>
			                    </div>
						    </div>
						    <div id="global-shuffle-params" shuffle-date="<?php echo $shufflingDate?>" shuffle-time="<?php echo $startTimeSlot?>"></div>
						    <?php }?> 
                            </td>                         
                        </tr>                        
                        <?php } ?>
                        
                        <?php if(empty($localGroupList) && empty($globalGroupList)){
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
<script type="text/javascript">
var showGroupMemberList = "<?php echo Router::url(array('controller'=>'groupShuffles','action'=>'showGroupMemberList'));?>";
var groupShuffling = "<?php echo Router::url(array('controller'=>'groupShuffles','action'=>'groupShuffling'));?>";
var shuffleRedirectUrl = "<?php echo Router::url(array('controller'=>'groupShuffles','action'=>'shufflingStep3',$shufflingDate,$startTimeSlot),true);?>";
</script>
<?php echo $this->Html->script('shuffling');?>