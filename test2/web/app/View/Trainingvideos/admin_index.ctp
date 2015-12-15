<!-- start: PAGE HEADER -->
<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-file"></i>
                <?php
                echo $this->Html->link('Training Videos', array('controller' => 'trainingvideos', 'action' => 'index', 'admin' => true));
                ?>
            </li>
            <li class="active">Video List</li>
        </ol>
        <div class="page-header">
            <?php echo $this->Html->tag('h1', 'Video List');?>
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
                            <th>Video Title</th>
                            <th>Uploaded On</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                      
                        if (!empty($videos)) {
                            $counter = 0;
                            foreach ($videos as $video) {
                                $counter++;
                                $videoId=$video['Trainingvideo']['id'];
                                $videoName=$video['Trainingvideo']['video_name'];
                               
                                ?>
                                <tr>
                                    <td class="center"><?php echo $counter; ?></td>
                                    <td class="hidden-xs"><?php echo $video['Trainingvideo']['video_name']; ?></td>
                                    <?php 
                                    $time = explode(' ',$video['Trainingvideo']['created']);
                                    ?>
                                    <td class="hidden-xs"><?php echo $time = date('m-d-Y',strtotime($time[0])) .' '. $time[1] ;  ?></td>
                                    <td class="center">
                                    <?php $activeUrl = 'admin/trainingvideos/activate/';?>
                                    <?php $playUrl = 'admin/trainingvideos/play/';?>
                                    <?php $deleteUrl = 'admin/trainingvideos/delete/';
                                    $hiddenMenu='';
                                    ?>
                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                            <?php
                                            if($video['Trainingvideo']['is_active'] == 'yes')
                                            {
                                                echo $this->Html->link('<i class="fa fa-check-square-o"></i>', 'javascript:void(0)', 
                                                            array(
                                                                'class' => 'btn btn-xs btn-teal tooltips activeInactive',
                                                                //'data-original-title' => 'Active',
                                                                //'data-placement' => 'top',
                                                                'escape' => false
                                                                ));
                                                $hiddenMenu=$this->Html->link('<i class="fa fa-check-square-o"></i> Active', 'javascript:void(0)', 
                                                            array(
                                                                'class' => 'btn btn-xs activeInactive',
                                                                'data-placement' => 'top',
                                                                'escape' => false,
                                                                'style'=>'text-align:left'
                                                                ));
                                            }
                                            else
                                            {
                                                echo $this->Html->link('<i class="fa fa-square-o"></i>', 'javascript:void(0)', 
                                                            array(
                                                                'class' => 'btn btn-xs btn-teal tooltips tooltips activeInactive',
                                                                'data-original-title' => 'Active',
                                                                'data-toggle' => 'modal',
                                                                'data-backdrop'=>'static',
                                                                'data-placement' => 'top',
                                                                'data-id'=>$videoId,
                                                                'data-target' => '#popup',
                                                                'onclick'=>"popUp('".$activeUrl."','".$videoId."')",'escape' => false
                                                                ));
                                                $hiddenMenu=$this->Html->link('<i class="fa fa-square-o"></i> Inactive', 'javascript:void(0)', 
                                                            array(
                                                                'class' => 'btn btn-xs activeInactive',
                                                                'data-toggle' => 'modal',
                                                                'data-backdrop'=>'static',
                                                                'data-placement' => 'top',
                                                                'data-id'=>$videoId,
                                                                'data-target' => '#popup',
                                                                'onclick'=>"popUp('".$activeUrl."','".$videoId."')",'escape' => false,
                                                                'style'=>'text-align:left'
                                                                ));
                                            }  
                                            echo '&nbsp';
                                            echo $this->Html->link('<i class="fa fa-video-camera"></i>', 'javascript:void(0)', 
                                                            array(
                                                                'class' => 'btn btn-xs btn-primary tooltips video',
                                                                'data-original-title' => 'Play',
                                                                'data-toggle' => 'modal',
                                                                'data-backdrop'=>'static',
                                                                'data-placement' => 'top',
                                                                'data-id'=>$videoId,
                                                                'data-target' => '#popup',
                                                                'onclick'=>"popUp('".$playUrl."','".$videoName."')",'escape' => false
                                                                ));                                          
                                            echo '&nbsp;';
                                            echo $this->Html->link('<i class="fa fa-times fa fa-white"></i>', 'javascript:void(0)', 
                                                      array(
                                                            'class' => 'btn btn-xs btn-bricky tooltips delete',
                                                            'data-original-title' => 'Delete',
                                                            'data-toggle' => 'modal',
                                                            'data-backdrop'=>'static',
                                                            'data-placement' => 'top',
                                                            'id'=>$videoId,
                                                            'data-target' => '#popup',
                                                            'onclick'=>"popUp('".$deleteUrl."','".$videoId."')",'escape' => false
                                                            ));
                                            ?>
                                        </div>
                                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                                            <div class="btn-group">
                                                <?php
                                                echo $this->Html->link('<i class="fa fa-cog"></i> <span class="caret"></span>', '#', array('data-toggle' => 'dropdown', 'class' => 'btn btn-primary dropdown-toggle btn-sm', 'escape' => false));

                                                $list = array(
                                                    $hiddenMenu,
                                                    $this->Html->link('<i class="fa fa-video-camera"></i> Play', 'javascript:void(0)', 
                                                            array(
                                                                'class' => 'btn btn-xs video',
                                                                'data-toggle' => 'modal',
                                                                'data-backdrop'=>'static',
                                                                'data-placement' => 'top',
                                                                'data-id'=>$videoId,
                                                                'data-target' => '#popup',
                                                                'onclick'=>"popUp('".$playUrl."','".$videoName."')",'escape' => false,
                                                                'style'=>'text-align:left',
                                                                )),
                                                $this->Html->link('<i class="fa fa-times fa fa-white"></i> Delete', 'javascript:void(0)', 
                                                      array(
                                                            'class' => 'btn btn-xs delete',
                                                            'data-toggle' => 'modal',
                                                            'data-backdrop'=>'static',
                                                            'data-placement' => 'top',
                                                            'id'=>$videoId,
                                                            'data-target' => '#popup',
                                                            'onclick'=>"popUp('".$deleteUrl."','".$videoId."')",'escape' => false,'style'=>"text-align:left"), false)
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
                        else{
                            echo "<tr><td colspan='5' style='text-align:center'>No record found</td></tr>";
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
    
    $(document).ready(function() {
        $('body').on('click','.popup_btn',function(){
                $(this).css('pointer-events','none');
            });
        $('.video').hover(function(){
            $('.video').tooltip('enable');
        });
    });
</script>
