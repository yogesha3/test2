<div class="modal-header">
    <?php
    echo $this->Form->button('&times;', array('class' => 'close closeModel', 'data-dismiss' => 'modal', 'aria-hidden' => true));
    echo $videoName;
    ?>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <video width="308" controls>
              <source src="<?php echo Configure::read('SITE_URL');?>trainingvideo/<?php echo $videoName;?>" type="video/mp4">
            </video>
        </div>
    </div>
</div>
<div class="modal-footer">
</div>
<script>
    $(document).ready(function(){
        $('.closeModel').click(function(){
          $(".video").tooltip('disable');
      });
    });
</script>