<style>.modal-dialog {width: 710px;} .table > tbody > tr > th,td{border:none;}</style>
<div class="modal-dialog modal-lg GroupMemberPopup">
<div class="model_header">
<button aria-label="Close" data-dismiss="modal" class="close closeModel" type="button"><span aria-hidden="true">×</span></button>
<h2 class="center"><u>Affiliate Details</u></h2>
<table class="table table-hover">	
		<tr>
			<th class="col-md-5">Affiliate Name</th>
			<th class="col-md-4">Affiliate Email</th>			
		</tr>
		<tr>
			<td><?php echo $affiliateDetail['Affiliate']['name']?></td>
			<td><?php echo $affiliateDetail['Affiliate']['email']?></td>
		</tr>
		<tr>
			<th colspan="2" class="col-md-10">Affiliate Link</th>		
		</tr>
		<tr>
			<td colspan="2"><?php echo $affiliateDetail['Affiliate']['link']?></td>
		</tr>
		<tr>
			<th class="col-md-5">Added On</th>
			<th class="col-md-4">Traffic Generated</th>			
		</tr>
		<tr>
			<td><?php echo date('m-d-Y' , strtotime($affiliateDetail['Affiliate']['created']))?></td>
			<td><?php echo $affiliateDetail['Affiliate']['traffic_generated']?></td>
		</tr>
		<tr>
			<th class="col-md-5">Total Conversion</th>
			<th class="col-md-4">Conversion Rate (%)</th>			
		</tr>
		<tr>
			<td><?php echo $affiliateDetail['Affiliate']['total_conversion']?></td>
			<?php $conversionRate = (!empty($affiliateDetail['Affiliate']['traffic_generated'])) ? ($affiliateDetail['Affiliate']['total_conversion']/$affiliateDetail['Affiliate']['traffic_generated']*100) : 0;?>
			<td><?php echo $this->Number->toPercentage($conversionRate);?></td>
		</tr>
</table>
</div>
</div>
<script>
    $(document).ready(function(){
        $('.closeModel').click(function(){
          $(".cursor").tooltip('disable');
      });
    });
</script>