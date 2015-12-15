				var options = {
					
				    tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value + '' %> Registration(s)",
				    multiTooltipTemplate: "<%= value + '' %> ",
				    responsive: true,
				};
				
                   if(!$.isEmptyObject(barChartData)){
                	   var ctx = document.getElementById("canvas123").getContext("2d");

       					var myBarChart = new Chart(ctx).Bar(barChartData, options);
                   } else {
                	   $(".redDataResponse").html('<div class="text-center" style="padding:110px;"><strong>No Data Available</strong></div>');
                   }
                   
                   if(prof_data_pie.length>0) {
                   
	                   $.plot('#placeholder8', prof_data_pie, {
	                       series: {
	                           pie: {
	                               show: true,
	                               radius: 1,
	                               label: {
	                                   show: true,
	                                   radius: 1,
	                                   formatter: function (label, series) {
	                                       return '<div style="font-size:8pt;text-align:center;padding:3px;color:white;">' + label + '<br/>(' + parseInt(series.percent) +"%, "+series.data[0][1] + ')</div>';
	
	                                   },
	                                   background: {
	                                       opacity: 0.5,
	                                       color: '#000'
	                                   }
	                               }
	                           }
	                       },
	                       grid: {
		           				hoverable: true,
		           			},
	                       legend: {
	                           show: false
	                       }
	                   });
                   } else {
                	   $("#placeholder8").html('<div class="text-center" style="padding:110px;"><strong>No Data Available</strong></div>');
                   }
                   
                   if(ref_data_pie.length>0) {
                   
	                   $.plot('#placeholder4', ref_data_pie, {
	                       series: {
	                       	points: {
	           					show: true
	           				},
	                           pie: {
	                           	innerRadius: .3,
	                               show: true,
	                               label: {
	                                   show: true,
	                                   radius: 3/4,
	                                   formatter: function (label, series) {
	                                       return '<div style="font-size:8pt;text-align:center;padding:3px;color:white;">' + label + '<br/>(' + parseInt(series.percent) +"%, "+series.data[0][1] + ')</div>';
	
	                                   },
	                                   threshold: 0.1,	
	                                   background: {
	                                       opacity: 0.5,
	                                       color: '#000'
	                                   }
	                               }
	                           }
	                       },
	                       grid: {
	           				hoverable: true,
	           			},
	                   });
                   } else {
                	   $("#placeholder4").html('<div class="text-center" style="padding:110px;"><strong>No Data Available</strong></div>');
                   }