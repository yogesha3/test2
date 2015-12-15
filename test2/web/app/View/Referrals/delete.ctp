<div class="modal-header">
    <?php
   // echo $referralId;
    echo $this->Form->button('&times;', array('class' => 'close closeModel', 'data-dismiss' => 'modal', 'aria-hidden' => true));
    //echo $videoName;
    ?>
    <h4 class="Activate Confirmation">Delete Confirmation</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            123 helo how ae yo a;lsdkjf ;lkjasd;fjasd fl;ajsdl;fjasdjf a;sldkfja 
            <input type="hidden" name="referralId" id="referralId" value="<?php echo $referralId;?>">
            <input type="hidden" name="action" id="action" value="delete">
        </div>
    </div>
</div>
<div class="modal-footer">
<?php 
echo $this->Form->button('cancel', array('data-dismiss' => 'modal', 'class' => 'btn btn-light-grey closeModel'));
echo $this->Form->button('Yes', array('data-dismiss' => 'modal', 'class' => 'btn btn-light-grey deleteReferral','onclick' => 'popUp();'));

/*$this->Js->get('.deleteReferral');
           $this->Js->event('click',
           $this->Js->request(array(
                  'controller'=>'referrals',
                  'action'=>'delete'),
                     array('async'=>true,
                        'dataExpression'=>true,
                        'data' => '$(\'#referralId,#action\').serializeArray()',
                        'method'=>'post')
                    )
            );*/
?>
</div> 
<script>
  function popUp(){
    var id = $('#referralId').val();
    $.ajax({      
        type: 'post',
        url: 'sent',
        data:{id:id,action:'delete'},
        success: function(data,textStatus,xhr){
            //$("#popup").html(data);
        },
        error: function(xhr,textStatus,error){
        }
    });
    return false;
}
</script> 