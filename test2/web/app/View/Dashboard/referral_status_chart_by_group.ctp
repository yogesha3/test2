<?php if(!empty($graphInfo) && array_sum($graphInfo)>0){?>
<?php //echo $this->Html->script(array('Front/charts/Chart.min'))?>
<canvas id="canvas" height="580" width="600"></canvas>
<?php 
$received 	= (empty($graphInfo['received'])) ? "0" : $graphInfo['received'];
$contacted 	= (empty($graphInfo['contacted'])) ? "0" : $graphInfo['contacted'];
$proposal 	= (empty($graphInfo['proposal'])) ? "0" : $graphInfo['proposal'];
$success 	= (empty($graphInfo['success'])) ? "0" : $graphInfo['success'];
$kaput 		= (empty($graphInfo['kaput'])) ? "0" : $graphInfo['kaput'];
?>
<script>
var options = { 
	responsive : true,
	<?php if($searchEntity==2){?>
	tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= formatCurrency(value) %>",
	scaleLabel: function(label){return  ' $' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");}
	<?php }?>        
};
    
var barChartData = {
	labels : ["Received","Contacted","Proposal","Success","Kaput"],
	datasets : [
		{
			fillColor : "rgba(112,156,210,2)",
			strokeColor : "rgba(112,156,210,2.5)",
			data : [<?php echo $received.",".$contacted.",".$proposal.",".$success.",".$kaput?>]
		}
	]
}
var ctx = document.getElementById("canvas").getContext("2d");
window.myBar = new Chart(ctx).Bar(barChartData, options);
<?php if($searchEntity==2){?>
// manage tooltip
function formatCurrency(value){
    return "$" +value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
<?php }?> 
</script>
<?php }else{?>
<div class="empty-graph"><?php echo $this->Html->image('no-data.jpg',array('class'=>'img-responsive'))?></div>
<?php }