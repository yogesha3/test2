<?php if(array_sum($targetIndividualGraphInfo)>0 || array_sum($actualIndividualGraphInfo)>0){?>
<?php //echo $this->Html->script(array('Front/charts/Chart.min'))?>
<canvas id="canvas6" height="580" width="600"></canvas>
<?php
$targetData = (count($targetIndividualGraphInfo)>3) ? array_shift($targetIndividualGraphInfo) : $targetIndividualGraphInfo;
$actualData = (count($actualIndividualGraphInfo)>3) ? array_shift($actualIndividualGraphInfo) : $actualIndividualGraphInfo;
$barValueSpacing = 0;
switch (count($targetIndividualGraphInfo)){
	case 1:
		$barValueSpacing = 90;
		break;
	case 2:
		$barValueSpacing = 25;
		break;
	case 3:
		$barValueSpacing = 10;
		break;
	default:
		$barValueSpacing = 90;
		break;
}

?>
<script>
var options = { 
	responsive : true,
	barValueSpacing:<?php echo $barValueSpacing?>,
	barDatasetSpacing : 5,
	multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
};
    
var barChartData = {
	labels : ["<?php echo implode('","',array_keys($targetIndividualGraphInfo))?>"],
	datasets : [
		{
			label: "Target Goals",
			fillColor : "rgba(112,156,210,2)",
			strokeColor : "rgba(112,156,210,2.5)",
			data : [<?php echo implode(',',array_values($targetIndividualGraphInfo))?>]
		},
		{
			label: "Actual Goals",
			fillColor : "rgba(214,222,35,0.5)",
			strokeColor : "rgba(214,222,35,0.8)",
			data : [<?php echo implode(',',array_values($actualIndividualGraphInfo))?>]
		}
	]
}
var ctx = document.getElementById("canvas6").getContext("2d");
window.myBar = new Chart(ctx).Bar(barChartData, options);
</script>
<?php }else{?>
<div class="empty-graph"><?php echo $this->Html->image('no-data-graph6.jpg',array('class'=>'img-responsive'))?></div>
<?php }