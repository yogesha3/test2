<?php if(!empty($donut) ){?>
<?php //echo $this->Html->script(array('Front/charts/Chart.min'))?>
<div style="width: 230px;float: none;margin: 0 auto;"><canvas id="canvas989" height="230" width="230"></canvas></div>
<div id="js-legend" class="chart-legend"></div>
<?php $dataString = "";
foreach($donut as $pie) {
    extract($pie);
    $dataString .='{
                value: '.$value.',
                color:"'.$color.'",
                highlight: "'.$highlight.'",
                label: "'.$label.'"
            },'; 
}
?>
<script type="text/javascript">
var options = { 
	 animation: false,
	 animateScale : true,
	 tooltipTemplate: "<%= value %> Referral(s)"
};    
var data = [<?php echo $dataString;?>]
var c = $('#canvas989');
var ct = c.get(0).getContext('2d');
var ctx = document.getElementById("canvas989").getContext("2d");
myNewChart = new Chart(ct).Doughnut(data, options);
document.getElementById('js-legend').innerHTML = myNewChart.generateLegend();
</script>
<style>
.doughnut-legend{list-style: none;height: 9px;}
.doughnut-legend li{display: block;float: left;}
.doughnut-legend li:FIRST-CHILD{margin-right: 85px;}
.chart-legend li span{
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-right: 5px;
    
}
</style>
<?php }else{?>
<div class="empty-graph"><?php echo $this->Html->image('referral_graph_placeholder.jpg',array('class'=>'center-block img-responsive placeholder_img'))?></div>
<?php }