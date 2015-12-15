<div class="modal-dialog modal-lg GroupMemberPopup">
<div class="model_header">
<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
<table class="table table-hover">
	<thead>
		<tr>
			<th class="col-md-5">Group Member Name</th>
			<th class="col-md-4">Profession</th>
		</tr>
	</thead>
	<tbody id="shuffling_group">
	<?php
	if (!empty($groupMembers)) {
		foreach ($groupMembers as $key => $members) {
			?>
			<tr>
				<td>
            <?php
                $memberType = $members['BusinessOwner']['group_role'];
                switch ($memberType) {
                    case "leader":
                        $role = " (L)";
                        break;
                    case "co-leader":
                        $role = " (CL)";
                        break;
                    default:
                        $role = NULL;
                        break;
                }
                echo $members['BusinessOwner']['member_name'].$role;
            ?>
        </td>
				<td><?php echo $members['Profession']['profession_name']; ?></td>
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