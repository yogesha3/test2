<?php $this->Paginator->options(array('update' => '#ajaxTableContent', 'evalScripts' => true)); ?>
<?php echo $this->Paginator->options(array('url' => array('action'=>'partnersList',"perpage"=>$perpage,"search"=>$search,'sort'=> $this->Session->read('sort'),'direction'=> $this->Session->read('direction'))));
?>
<table class="col-md-12 table-bordered table-striped table-condensed no-more-tables cf data_table word_break invite_partners_table">
    <thead class="cf">
        <tr>
                <th width="20%"><?php echo $this->Paginator->sort('InvitePartner.invitee_name', 'Invitee Name'); ?></th>
                <th width="25%"><?php echo $this->Paginator->sort('InvitePartner.invitee_email', 'Invitee Email'); ?></th>
                <th width="15%"><?php echo $this->Paginator->sort('InvitePartner.created', 'Sent On'); ?></th>
                <th width="10%"><?php echo $this->Paginator->sort('InvitePartner.status', 'Status'); ?></th>
                <th width="30%"><?php echo $this->Paginator->sort('InvitePartner.referral_amount', 'Referral Amount'); ?></th>
                            </tr>
    </thead>
    <tbody>
                              <?php if (isset($partnersList) && !empty($partnersList)) {
                                foreach ($partnersList as $partnersList) { ?>
                                    <tr>
                                        <td class="partner_name">
                                                    <?php echo ucfirst($partnersList['InvitePartner']['invitee_name']) ;?></td>
                                        <td>
                                            <?php echo $partnersList['InvitePartner']['invitee_email'] ;?></td>
                                            <td><?php echo date('M d, Y ',strtotime($partnersList['InvitePartner']['created'])) ;?>
                                        </td>
                                        <td><?php echo !empty($partnersList['InvitePartner']['status']) ? ucfirst($partnersList['InvitePartner']['status']) : 'NA' ;?>
                                        </td>
                                        <td><?php echo '$'.(!empty($partnersList['InvitePartner']['referral_amount']) ? $partnersList['InvitePartner']['referral_amount'] : '0') ;?></td>
                                        
                                    </tr>
                            <?php }
                            } else {
                            ?>
                                <tr><td colspan="5" class="noData"><?php echo isset ($noDataMsg) ? $noDataMsg : "No record found"; ?></td></tr>
                            <?php } ?>
                        </tbody>
</table>
<div class="clearfix">&nbsp;</div>
<?php if ($this->Paginator->numbers()) { ?>
    <ul class="pagination pagination_table pagination-sm pull-right">
        <li><?php echo $this->Paginator->prev('&#171', array('tag' => false)); ?></li>
        <li><?php echo $this->Paginator->numbers(array('separator' => false)); ?> </li>
        <li><?php echo $this->Paginator->next('&#187', array('tag' => false)); ?></li>
    </ul>
<?php } ?>
<?php echo $this->Js->writeBuffer(); ?>
<script>
$( document ).ready(function() {
    $( "#checkall" ).change(function() {		
        if ($("#checkall").prop("checked")) {
            $(".checkthis").prop('checked', true);
        } else {
            $(".checkthis").prop('checked', false);
        }
    });
  
    $( ".checkthis" ).change(function() {
        if ($(this).prop("checked")) {
            $("#mass_action").val('');
        } else {
            $("#checkall").prop('checked', false);
        }
    });
  
    del_cnt = $('input.checkthis:checked').length;
    if (del_cnt>0) {
        $("#bulkapplysubmit").show();
        $("#bulkdeleteblank").hide();
    } else {
        $('#mass_action').val('');
        $("#bulkapplysubmit").hide();
        $("#bulkdeleteblank").show();
    }
    $( "#searching" ).keyup(function() {
        searchval = $('#search_field_val').val($(this).val());
    });
    backurl = $('#saveurl_field_val').val('<?php echo $this->Paginator->url()?>');
    $('.prev').html("&#171");
    $('.next').html("&#187");
});
</script>
