<?php

/**
 * this is youtube video preview
 * @author Laxmi Saini
 */
?>
<div class="modal-header">
    <?php echo $this->Form->button('&times;', array('class' => 'close closeModel', 'data-dismiss' => 'modal', 'aria-hidden' => true)); ?>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <?php 
            $url='';
            if (!empty($webcast)) {
               $url = $webcast['Webcast']['link'];
            }
            echo $this->Youtube->iframe($url);
            ?>
        </div>
    </div>
</div>
<div class="modal-footer">
</div>
<script>
    $(document).ready(function () {
        $('.closeModel').click(function () {
            $(".video").tooltip('disable');
        });
    });
</script>