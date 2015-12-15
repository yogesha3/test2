<?php if(!empty($graphInfo) && array_sum($graphInfo)>0){?>
<?php //echo $this->Html->script(array('Front/charts/Chart.min'))?>
<canvas id="canvas4" height="580" width="600"></canvas>
<?php
$totalBar = count($graphInfo);
$graphValues = $graphLabels = array();
foreach($graphInfo as $label => $value){
    $label = html_entity_decode($label);
    if(strlen($label)>15) {
        $label = substr($label, 0, 12).'...';
    }
	$graphLabels[] = '"'.$label.'"';
	$graphValues[] = (empty($value)) ? "0" : $value;
}
switch ($totalBar){
	case 1:
		$barValueSpacing = 80;
		break;
	case 2:
		$barValueSpacing = 45;
		break;
	case 3:
		$barValueSpacing = 15;
		break;
	case 4:
		$barValueSpacing = 8;
		break;
	case 5:
		$barValueSpacing = 4;
		break;
}
?>
<script>
var options = { 
	responsive : true,
	barValueSpacing:<?php echo $barValueSpacing?>,
	<?php if($searchEntity==2){?>
	tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= formatCurrency(value) %>",
	scaleLabel: function(label){return  ' $' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");}
	<?php }?>        
};
    
var barChartData = {
	labels : [<?php echo implode(",",$graphLabels)?>],
	datasets : [
		{
			fillColor : "rgba(112,156,210,2)",
			strokeColor : "rgba(112,156,210,2.5)",
			data : [<?php echo implode(",",$graphValues)?>]
		}
	]
}
var ctx = document.getElementById("canvas4").getContext("2d");
window.myBar = new Chart(ctx).Bar(barChartData, options);
<?php if($searchEntity==2){?>
// manage tooltip
function formatCurrency(value){
    return "$" +value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
<?php }?> 
</script>
<?php }else{?>
<div class="empty-graph"><?php echo $this->Html->image('no-data-graph4.jpg',array('class'=>'img-responsive'))?></div>
<?php }