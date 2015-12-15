<div class="modal-dialog" role="document">
    <?php
        switch ($listPage) {
            case "referrals/received":
                $actionMessage = "Do you want to archive the referral(s)?";
                $checkAllId = "checkallarchive";
                break;
            case "referrals/sent":
                $actionMessage = "Do you want to archive the referral(s)?";
                $checkAllId = "checkallarchive";
                break;
            case "referrals/archive/sent":
                $actionMessage = "Do you want to delete the referral(s)? This cannot be undone";
                $checkAllId = "checkallarchive";
                break;
            case "referrals/archive/received":
                $actionMessage = "Do you want to delete the referral(s)? This cannot be undone";
                $checkAllId = "checkallarchive";
                break;
            case "messages/inbox/inbox":
                $actionMessage = "Do you want to archive the message(s)?";
                $checkAllId = "checkallarchive";
                break;
            case "messages/sentMessages/sentmessage":
                $actionMessage = "Do you want to archive the message(s)?";
                $checkAllId = "checkallarchive";
                break;
            case "messages/inbox/archive":
                $actionMessage = "Do you want to permanently delete the message(s)?";
                $checkAllId = "checkallarchive";
                break;
            case "messages/sentMessages/archive":
                $actionMessage = "Do you want to permanently delete the message(s)?";
                $checkAllId = "checkallarchive";
                break;
            case "contacts/contactList":
                $actionMessage = "Do you want to permanently delete the contact(s)?";
                $checkAllId = "checkall";
                break;
            case "UID":
                $actionMessage = "Do you want to join the group?";
                $checkAllId = "";
                $redirect = 'dashboard/dashboard';
                break;
            case "cancelMembership":
                $actionMessage = "Do you want to cancel the membership?";
				$checkAllId = "";
                $redirect = 'dashboard/dashboard';
                break;
            case "dashboard/changeGroup":
                $actionMessage = "Do you want to submit the group change request?";
                $checkAllId = "dashboard";
                break;
            default:
                $actionMessage = "Do you want to permanently delete?";
                $checkAllId = "checkallarchive";
        }
    ?>
    <div class="modal-content">
        <div class="modal-header" style="border:none">
        </div>
        <div class="modal-body popup_body">
            <h2>
              <?php echo $actionMessage; ?>
            <div class="modal_text"></div>
            
        </div>
        <div class="modal-footer popup_footer text-center">
            <button onclick="removeCheck('<?php echo $checkAllId; ?>');" type="button" class="btn btn-primary popup_cancel" data-dismiss="modal"><span class="pull-left">Cancel</span>  <i class="fa fa-close pull-right"></i></button>
            <?php if ($listPage == "UID" || $listPage == "cancelMembership") { ?>
                <button type="button" class="btn btn-default ok_btn" onClick="groupAction('<?php echo $id; ?>', '<?php echo $controller; ?>', '<?php echo $action; ?>', '<?php echo $UID; ?>','<?php echo $redirect;?>')"><span class="pull-left"  data-dismiss="modal">Ok</span>  <i class="fa fa-check pull-right"></i></button>
            <?php } else { ?>
                <button type="button" class="btn btn-default ok_btn" onClick="actionOnPopUp('<?php echo $id; ?>', '<?php echo $controller; ?>', '<?php echo $action; ?>', '<?php echo $listPage; ?>')"><span class="pull-left"  data-dismiss="modal">Ok</span>  <i class="fa fa-check pull-right"></i></button>
            <?php }?>
            
        </div>
    </div>
</div>