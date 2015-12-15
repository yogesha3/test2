<div class="modal-dialog modal-lg GroupMemberPopup">
<div class="model_header">
<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
<table class="table table-hover">
	<thead>
		<tr>
			<th class="col-md-5">Group Member Name</th>
			<th class="col-md-4">Profession</th>
			<th class="col-md-3 center">Percentage</th>
		</tr>
	</thead>
	<tbody id="shuffling_group">
	<?php
	if (! empty ( $usersList )) {
		foreach ( $usersList as $key => $user ) {
			?>
			<tr>
				<td><?php echo $user['BusinessOwner']['member_name']?></td>
				<td><?php echo $user['Profession']['profession_name']?></td>
				<td class="center"><?php echo ($user['BusinessOwner']['shuffling_percent']*100)?>%</td>
			</tr>
	<?php
		}
	} else {
		echo "<tr><td colspan='3' style='text-align:center'>No record found</td></tr>";
	}
	?>
	</tbody>
</table>
</div>
</div>