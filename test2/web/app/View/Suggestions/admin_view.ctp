<div class="modal-header">
    <?php
    echo $this->Form->button('&times;', array('class' => 'close closeModel', 'data-dismiss' => 'modal', 'aria-hidden' => true));
    echo '<strong>Suggestion Details</strong>';
    ?>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            
            <p style="min-width: 700px;max-width: 700px;"><?php echo htmlspecialchars($suggestion['Suggestion']['message']);?></p>
        </div>
    </div>
</div>
<div class="modal-footer">
</div>
<script>
    $(document).ready(function(){
        $('.closeModel').click(function(){
            $("a.view_suggestion").tooltip('disable');
        });
    });
</script>