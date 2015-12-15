<div class="modal-header">
    <?php
   // echo $referralId;
    echo $this->Form->button('&times;', array('class' => 'close closeModel', 'data-dismiss' => 'modal', 'aria-hidden' => true));
    //echo $videoName;
    ?>
    Delete Confirmation
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            Are you sure want to delete ?
            <input type="hidden" name="referralId" id="referralId" value="<?php echo $referralId;?>">
            <input type="hidden" name="action" id="action" value="delete">
        </div>
    </div>
</div>
<div class="modal-footer">
<?php
    echo $this->Form->button('Cancel', array('data-dismiss' => 'modal', 'class' => 'btn btn-light-grey closeModel'));
    echo '&nbsp;';
    echo $this->Form->postLink('Ok', 'delete/'.$referralId, array('class' => 'btn  btn-bricky tooltips popup_btn', 'data-placement' => 'top', 'escape' => false)); 


?>
</div>  