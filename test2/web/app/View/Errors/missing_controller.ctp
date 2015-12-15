<!-- start: PAGE -->
<div class="container">
    <div class="row">
        <!-- start: 404 -->
        <div class="col-sm-12 page-error">
            <div class="error-number teal">
                404
            </div>
            <div class="error-details col-sm-6 col-sm-offset-3">
                <h3>Oops! You are stuck at 404</h3>
                <p>
                    Unfortunately the page you were looking for could not be found.
                    <br>
                    It may be temporarily unavailable, moved or no longer exist.
                    <br>
                    Check the URL you entered for any mistakes and try again.
                    <br>
                     <?php
                    echo $this->Html->link('Back', array('controller'=>'plans','action'=>'index'),array('class'=>'btn btn-teal btn-return','escape'=>false));
                    ?>
                    <br>
                    Still no luck?
                    <br>
                    Search for whatever is missing, or take a look around the rest of our site.
                </p>
<!--                <form action="#">
                    <div class="input-group">
                        <input type="text" placeholder="keyword..." size="16" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-teal">
                                Search
                            </button> </span>
                    </div>
                </form>-->
            </div>
        </div>
        <!-- end: 404 -->
    </div>
</div>
<!-- end: PAGE -->


