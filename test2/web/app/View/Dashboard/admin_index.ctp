					<!-- start: PAGE HEADER -->
					<div class="row">
						<div class="col-sm-12">
							
							<!-- start: PAGE TITLE & BREADCRUMB -->
							<ol class="breadcrumb">
								<li>
									<i class="clip-file"></i>
									<a href="<?php echo Router::url(array('controller'=>'dashboard','action'=>'index','admin'=>true));?>">
										Dashboard
									</a>
								</li>
							</ol>
							<div class="page-header">
								<h1>Dashboard</h1>
							</div>
							<!-- end: PAGE TITLE & BREADCRUMB -->
						</div>
					</div>
					<!-- end: PAGE HEADER -->
					<!-- start: PAGE CONTENT -->
					
					<div class="row">
					<!-- <div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="fa fa-external-link-square"></i>
									Real-time
									<div class="panel-tools hidden">
										<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
										</a>
										<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
											<i class="fa fa-wrench"></i>
										</a>
										<a class="btn btn-xs btn-link panel-refresh" href="#">
											<i class="fa fa-refresh"></i>
										</a>
										<a class="btn btn-xs btn-link panel-expand" href="#">
											<i class="fa fa-resize-full"></i>
										</a>
										<a class="btn btn-xs btn-link panel-close" href="#">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="panel-body">
									<div class="flot-small-container">
										<div id="placeholder1" class="flot-placeholder"></div>
									</div>
								</div>
							</div>
						</div> -->
					</div>
					<div class="row">
						<div class="col-md-6">
							<!-- start: CATEGORIES PANEL -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="fa fa-line-chart"></i>
									Registrations Count
									<div class="panel-tools hidden">
										<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
										</a>
										<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
											<i class="fa fa-wrench"></i>
										</a>
										<a class="btn btn-xs btn-link panel-refresh" href="#">
											<i class="fa fa-refresh"></i>
										</a>
										<a class="btn btn-xs btn-link panel-expand" href="#">
											<i class="fa fa-resize-full"></i>
										</a>
										<a class="btn btn-xs btn-link panel-close" href="#">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="panel-body">
									<div class="graph_container_bar redDataResponse">
										<canvas id="canvas123" ></canvas>
									</div>
								</div>
							</div>
							<!-- end: CATEGORIES PANEL -->
						</div>
						<div class="col-md-6">
							<!-- start: LABEL FORMATTER PANEL -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="fa fa-line-chart"></i>
									Top 5 Professions
									<div class="panel-tools hidden">
										<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
										</a>
										<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
											<i class="fa fa-wrench"></i>
										</a>
										<a class="btn btn-xs btn-link panel-refresh" href="#">
											<i class="fa fa-refresh"></i>
										</a>
										<a class="btn btn-xs btn-link panel-expand" href="#">
											<i class="fa fa-resize-full"></i>
										</a>
										<a class="btn btn-xs btn-link panel-close" href="#">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="panel-body">
									<div class="flot-small-container">
										<div id="placeholder8" class="flot-placeholder"></div>
									</div>
								</div>
							</div>
							<!-- end: LABEL FORMATTER PANEL -->
						</div>						
					</div>
					<div class="row">
						<div class="col-md-6">
							<!-- start: DEFAULT PIE PANEL -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="fa fa-line-chart"></i>
									Received Referrals v/s Sent Referrals
									<div class="panel-tools hidden">
										<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
										</a>
										<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
											<i class="fa fa-wrench"></i>
										</a>
										<a class="btn btn-xs btn-link panel-refresh" href="#">
											<i class="fa fa-refresh"></i>
										</a>
										<a class="btn btn-xs btn-link panel-expand" href="#">
											<i class="fa fa-resize-full"></i>
										</a>
										<a class="btn btn-xs btn-link panel-close" href="#">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="panel-body">
									<div class="flot-small-container">
										<div id="placeholder4" class="flot-placeholder"></div>
									</div>
								</div>
							</div>
							<!-- end: DEFAULT PIE PANEL -->
						</div>
						<!-- start: ANNOTATIONS PANEL
						<div class="col-md-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="fa fa-external-link-square"></i>
									Annotations
									<div class="panel-tools hidden">
										<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
										</a>
										<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
											<i class="fa fa-wrench"></i>
										</a>
										<a class="btn btn-xs btn-link panel-refresh" href="#">
											<i class="fa fa-refresh"></i>
										</a>
										<a class="btn btn-xs btn-link panel-expand" href="#">
											<i class="fa fa-resize-full"></i>
										</a>
										<a class="btn btn-xs btn-link panel-close" href="#">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="panel-body">
									<div class="flot-small-container">
										<div id="placeholder6" class="flot-placeholder"></div>
									</div>
								</div>
							</div>
						</div>
							end: ANNOTATIONS PANEL -->
					</div>
					<div class="row">
						<div class="col-md-6 hidden">
							<!-- start: LABEL STYLE PANEL -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="fa fa-external-link-square"></i>
									Label Style
									<div class="panel-tools">
										<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
										</a>
										<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
											<i class="fa fa-wrench"></i>
										</a>
										<a class="btn btn-xs btn-link panel-refresh" href="#">
											<i class="fa fa-refresh"></i>
										</a>
										<a class="btn btn-xs btn-link panel-expand" href="#">
											<i class="fa fa-resize-full"></i>
										</a>
										<a class="btn btn-xs btn-link panel-close" href="#">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="panel-body">
									<div class="flot-small-container">
										<div id="placeholder9" class="flot-placeholder"></div>
									</div>
								</div>
							</div>
							<!-- end: LABEL STYLE PANEL -->
						</div>
						<div class="col-md-6 hidden">
							<!-- start: RECTANGULAR PIE PANEL -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="fa fa-external-link-square"></i>
									Rectangular Pie
									<div class="panel-tools">
										<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
										</a>
										<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
											<i class="fa fa-wrench"></i>
										</a>
										<a class="btn btn-xs btn-link panel-refresh" href="#">
											<i class="fa fa-refresh"></i>
										</a>
										<a class="btn btn-xs btn-link panel-expand" href="#">
											<i class="fa fa-resize-full"></i>
										</a>
										<a class="btn btn-xs btn-link panel-close" href="#">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="panel-body">
									<div class="flot-small-container">
										<div id="placeholder10" class="flot-placeholder"></div>
									</div>
								</div>
							</div>
							<!-- end: RECTANGULAR PIE PANEL -->
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 hidden">
							<!-- start: TILTED PIE PANEL -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="fa fa-external-link-square"></i>
									Tilted Pie
									<div class="panel-tools">
										<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
										</a>
										<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
											<i class="fa fa-wrench"></i>
										</a>
										<a class="btn btn-xs btn-link panel-refresh" href="#">
											<i class="fa fa-refresh"></i>
										</a>
										<a class="btn btn-xs btn-link panel-expand" href="#">
											<i class="fa fa-resize-full"></i>
										</a>
										<a class="btn btn-xs btn-link panel-close" href="#">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="panel-body">
									<div class="flot-small-container">
										<div id="placeholder11" class="flot-placeholder"></div>
									</div>
								</div>
							</div>
							<!-- end: TILTED PIE PANEL -->
						</div>
						<div class="col-md-6 hidden">
							<!-- start: INTERACTIVITY PANEL -->
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="fa fa-external-link-square"></i>
									Interactivity
									<div class="panel-tools">
										<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
										</a>
										<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
											<i class="fa fa-wrench"></i>
										</a>
										<a class="btn btn-xs btn-link panel-refresh" href="#">
											<i class="fa fa-refresh"></i>
										</a>
										<a class="btn btn-xs btn-link panel-expand" href="#">
											<i class="fa fa-resize-full"></i>
										</a>
										<a class="btn btn-xs btn-link panel-close" href="#">
											<i class="fa fa-times"></i>
										</a>
									</div>
								</div>
								<div class="panel-body">
									<div class="flot-small-container">
										<div id="placeholder12" class="flot-placeholder"></div>
									</div>
								</div>
							</div>
							<!-- end: INTERACTIVITY PANEL -->
						</div>
					</div>
					
		<?php // Charts.js
        echo $this->Html->script('../assets/plugins/flot/jquery.flot.js');
        echo $this->Html->script('../assets/plugins/flot/jquery.flot.pie.js');
        echo $this->Html->script('../assets/plugins/flot/jquery.flot.resize.min.js');
        echo $this->Html->script('../assets/plugins/jquery.sparkline/jquery.sparkline.js');
        echo $this->Html->script('../assets/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.js');
       
        $i=1;
        $regString = '';
        $monthLabel = '';
        //pr($regData);exit;
        if(!empty($regData)) {
            $regString.='labels : ["'.implode('","',array_keys($regData)).'"],';
            $regString.='datasets : [{';
            $regString.='label: "Registerations",';
            $regString.='fillColor : "rgba(112,156,210,2)",';
            $regString.='strokeColor : "rgba(112,156,210,2.5)",';
            $regString.='data : ['.implode(',',array_values($regData)).']}]';
            						
        }
        $i=0;
        $prof_data_pie = '';
        if(!empty($professionsCount)) {
            foreach($professionsCount as $profData) {
                $prof_data_pie.='{
                    label: "'.$profData['ProfJoin']['profession_name'].'",
                    data: '.$profData['BusinessOwner']['professions_count'].'
                },';
                $i++;
            }
        }
        $ref_data_pie = '';
        if(!empty($refStat)) {
        	$ref_data_pie.='{
                label: "Sent",
                data: '.$refStat['sent'].'
            },{
                label: "Received",
                data: '.$refStat['received'].'
            }';                	
         }
        ?>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		
		
        <script>
        //Reg stats Data
        var barChartData = {
					<?php echo $regString;?>
				};
        
        // Profesions count data
        var prof_data_pie = [<?php echo $prof_data_pie;?>],
            series = 5;
        
        // Send Referrals VS Received Referral
        var ref_data_pie = [<?php echo $ref_data_pie;?>];
        
         </script>
         <?php echo $this->HTML->script('Front/charts/Chart.min');
         echo $this->HTML->script('charts');
         ?>
         
        