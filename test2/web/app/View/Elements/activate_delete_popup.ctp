<div class="modal-header">
        <?php
        /*$firstButtonLabel='Cancel';
        $headerMsg = 'Delete Confirmation';
        $secondButtonDisplay='';
        switch($action){
            case "approve":
                $headerMsg = 'Approve Confirmation';
                $actionMessage = "Do you want to approve the ".$info."?";
                break;
            
            case "activate":
                $headerMsg = 'Activate Confirmation';
                $actionMessage = "Do you want to activate the ".$info."?";
                break;
            
            case "delete":
                $actionMessage = "Do you want to delete the ".$info."?";
                break;
            
            case "deleteCategory":                
                $actionMessage = " The category may consists of FAQs. Do you want to delete the category?";
                break;
            
            case "cannotDeleteProfession":
                $actionMessage= "Cannot complete the operation as profession is already in use.";
                $firstButtonLabel='Ok';
                $secondButtonDisplay="display:none";
                break;
            
            case "cannotDeleteGroup":
                $actionMessage= "No sufficient groups available for the members";
                $firstButtonLabel='Ok';
                $secondButtonDisplay="display:none";
                break;                
            
            case "moveGroupMembers":
                $actionMessage= "Group consist of members. Do you still want to delete the group?";
                break;
            
            case "status":
                $headerMsg = $data.' Confirmation';
                $actionMessage = "Do you want to ".$data." the ".$info."?";
                break;
            case "cannotActiveStatus":
                $headerMsg ='Activate Confirmation';
                $actionMessage = "Cannot activate this coupon as only one public coupon can be active at a time.";
                $firstButtonLabel='Ok';
                $secondButtonDisplay="display:none";
                break;
            case "subscriptionDelete":
            	$actionMessage = "Do you want to delete the ".$info."?";
            	break;
            default:
                $headerMsg=" confirmation";
                $actionMessage = "Do you want to perform this action?";
                
                
        }*/
        if(isset($popupData) && is_array($popupData)) {
            extract($popupData);
        }
        echo $this->Form->button('&times;', array('class' => 'close closeModel', 'data-dismiss' => 'modal', 'aria-hidden' => true));
        echo $this->Html->tag('h4', $headerMsg, array('class' => 'Activate Confirmation'));
        ?>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <?php
                echo $this->Html->tag('p', $actionMessage);
                ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?php
            echo $this->Form->button($firstButtonLabel, array('data-dismiss' => 'modal', 'class' => 'btn btn-light-grey closeModel'));
            echo '&nbsp;';
            if($action!='moveGroupMembers'){
                echo $this->Form->postLink('Confirm', $action.'/'.$id, array('class' => 'btn  btn-bricky tooltips popup_btn', 'data-placement' => 'top', 'escape' => false,'style'=>$secondButtonDisplay));
            }else {
                echo $this->Html->link($this->Form->button('Proceed', array('type' => 'button', 'class' => 'btn  btn-bricky tooltips popup_btn')), array('controller' => 'groups', 'action' => $action, 'admin' => true,$id), array('escape' => false)); 
            }
        
        
        ?>

    </div>
<script>
    $(document).ready(function(){
        $('.closeModel').click(function(){
          $(".delete").tooltip('disable');
          $(".activeInactive").tooltip('disable');
      });
    });
</script>
    