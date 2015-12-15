<div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php
        switch($listPage){
            case "referrals/received":
                $actionMessage = "Do you want to archive the referral(s)?";
                $checkAllId = "checkallarchive";
                break;
            case "referrals/sent":
                $actionMessage = "Do you want to archive the referral(s)?";
                $checkAllId = "checkallarchive";
                break;
            case "referrals/archive/sent":
                $actionMessage = "Do you want to permanently delete the referral(s)?";
                $checkAllId = "checkallarchive";
                break;
            case "referrals/archive/received/unarchive":
            case "referrals/archive/sent/unarchive":
                $actionMessage = "Do you want to move the referral(s) to archive?";
                $checkAllId = "checkallarchive";
                break;
            case "referrals/archive/received":
                $actionMessage = "Do you want to permanently delete the referral(s)?";
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
                break;
            case "teams/teamList":
                $actionMessage = "Do you want to kick the selected member(s) from group?";
                $checkAllId = "checkallteam";
                break;

            default:
                $actionMessage = "Do you want to permanently delete?";
                $checkAllId = "checkall";
        }
        ?>
        <div class="modal-header" style="border:none">
        </div>
        <div class="modal-body popup_body">
            <h2>
              <?php echo $actionMessage; ?>
            <div class="modal_text"></div>
        </div>
        <div class="modal-footer popup_footer text-center">
            <button onclick="removeCheck('<?php echo $checkAllId; ?>');" type="button" class="btn btn-primary popup_cancel" data-dismiss="modal"><span class="pull-left">Cancel</span>  <i class="fa fa-close pull-right"></i></button>
            <button type="button" class="btn btn-default ok_btn" onClick="actionOnMassDelete('<?php echo $formId; ?>', '<?php echo $controller; ?>', '<?php echo $action; ?>', '<?php echo $listPage; ?>')"><span class="pull-left"  data-dismiss="modal">Ok</span>  <i class="fa fa-check pull-right"></i></button>
        </div>
    </div>
</div>