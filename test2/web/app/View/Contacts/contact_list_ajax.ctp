<?php $this->Paginator->options(array('update' => '#ajaxTableContent', 'evalScripts' => true)); ?>
<?php echo $this->Paginator->options(array('url' => array('action'=>'contact-list',"perpage"=>$perpage,"search"=>$search,'sort'=> $this->Session->read('sort'),'direction'=> $this->Session->read('direction'))));
?>
<?php $actionUrl = 'contacts/popupFunction'; ?>
<table class="col-md-12 table-bordered table-striped table-condensed no-more-tables cf data_table">
    <thead class="cf">
        <tr>
            <th width="1%"><input type="checkbox" id="checkall"></td>
            <th width="28%"><?php echo $this->Paginator->sort('Contact.first_name', 'Contact Name'); ?></th>
            <th width="20%"><?php echo $this->Paginator->sort('Contact.job_title', 'Job Title'); ?></th>
            <th width="30%"><?php echo $this->Paginator->sort('Contact.email', 'Email'); ?></th>
            <th width="30%"></th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($contactList) && !empty($contactList)) {
            foreach ($contactList as $contactData) {
                $contactId = $contactData['Contact']['id'];?>
                <tr>
                    <td>
                        <span class="check_inpurt">
                              <input name="contactIds[]" type="checkbox" class="checkthis" id="contact_<?php echo $contactData['Contact']['id']?>" value="<?php echo $contactData['Contact']['id']?>">&nbsp;&nbsp;
  
                          </span>
                    </td>
                    <td onclick="showContactDetail('<?php echo $contactData['Contact']['id']?>','contactDetail')" class="tdCursor">
                        <?php echo ucfirst($contactData['Contact']['first_name']) . " " . ucfirst($contactData['Contact']['last_name']); ?>
                    </td>
                    <td onclick="showContactDetail('<?php echo $contactData['Contact']['id']?>','contactDetail')" class="tdCursor"><?php echo !empty($contactData['Contact']['job_title']) ? ucfirst($contactData['Contact']['job_title']) : 'NA' ;?></td>
                    <td onclick="showContactDetail('<?php echo $contactData['Contact']['id']?>','contactDetail')" class="tdCursor"><?php
                                            if (!empty($contactData['Contact']['email'])) {
                                                if (strlen($contactData['Contact']['email']) > 30) {
                                                    $email = substr($contactData['Contact']['email'],0,30).'...';
                                                } else {
                                                    $email = $contactData['Contact']['email'];
                                                }
                                            }
                                            echo !empty($contactData['Contact']['email']) ? $email : 'NA' ;
                    ?></td>
                    <td>
                        <a title="Edit" href="javascript:void(0);" class="search_table_bg" onclick="showContactDetail('<?php echo $contactData['Contact']['id']?>','contactUpdate')"><span class="glyphicon glyphicon-pencil table_search_icon"></span></a>
                        <a class="search_table_bg" href="javascript:void(0)" data-toggle="modal" data-target="#myModal" onclick="popUp('<?php echo $actionUrl;?>', '<?php echo $contactId; ?>','<?php echo 'contacts'; ?>', '<?php echo 'deleteContact'; ?>', '<?php echo 'contacts/contactList'; ?>')" escape = false>
                            <span class="glyphicon glyphicon-trash table_search_icon" title="Delete"></span>
                        </a>
                        <?php
                        echo $this->Html->link(
                                               '<i class="fa fa-hand-o-right referContact"></i>',
                                               array('controller' => 'referrals', 'action' => 'sendReferrals', $contactData['Contact']['id']),
                                               array('escape' => FALSE, 'class' => 'search_table_bg', 'title' => 'Refer me'));
                        ?>
                        <div class="clearfix"></div>
                    </td>
                </tr>
        <?php }
        } else {
        ?>
            <tr><td colspan="5" class="noData"><?php echo isset ($noDataMsg) ? $noDataMsg : "No results found"; ?></td></tr>
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