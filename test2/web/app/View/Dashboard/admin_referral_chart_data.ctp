 <script>
        // Send Referrals VS Received Referral
        <?php if(!empty($refStat)) {?>
        var ref_data_pie = [];
        	ref_data_pie[0] = {
                label: "Sent",
                data: <?php echo $refStat['sent']?>
            };
        	ref_data_pie[1] = {
                label: "Received",
                data: <?php echo $refStat['received']?>
            };
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
                                return '<div style="font-size:8pt;text-align:center;padding:3px;color:white;">' + label + '<br/>' + parseInt(series.percent) +"%("+series.data[0][1] + ')</div>';

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
            <?php } else {?>
            $('#placeholder4').html('<div class="text-center" style="padding:110px;"><strong>No Data Available</strong></div>');
            <?php }?>
            
         </script>