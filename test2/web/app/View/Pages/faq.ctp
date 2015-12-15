<?php

/**
 * this is FAQ front end page
 * @author Laxmi Saini
 */
?>
<div class="inner_pages_heading">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="intro-text">
                    <span class="inner_page_name">FAQ</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<section id="inner_pages_top_gap">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content_grey">
                    <div class="grey_box_bottom">
                        <h2 class="forums_h">Search the Knowledge Base</h2>
                        <div class="clearfix"> &nbsp; </div>
                        <div class="search_faq col-md-6">
                            <input type="text" style="width:100%" autocomplete="off" placeholder="Search" class="title" id="query" name="query">
                            <div class="clearfix"></div>
                            <div class="search_keyword" style="display: none;"></div>
                            <i class="fa fa-search search_icon"></i>
                        </div>
                        <div class="clearfix"></div>
                        <?php
                        $this->Js->get('#query');
                        $this->Js->event('keyup',$this->Js->request(array(
                            'controller' => 'pages',
                            'action' => 'faqSearch'),
                          array('async'=> true,
                            //'update'=>'.autocomplete',
                            'dataExpression' => true,
                            'data' => '$(\'#query\').serializeArray()',
                            'method' => 'post',
                            'success' => 'updateData(data);')
                          ));
                        
                          ?>
                    </div>
                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="faq_content">
                    <div class="faq_white">
                        <div class="row">
                            <div class="col-md-12">
                                 <?php
                                if (!empty($faqdata)) {
                                   foreach ($faqdata as $data) {?>
                                <div class="faq_head"><?php echo $data['Faqcategorie']['category_name']?></div>
                                  <?php if(isset($data['Faq']) && count($data['Faq'])){?>
                                <ul class="faq_section">
                                      <?php foreach($data['Faq'] as $faq){?>
                                    <li>
                                         <?php echo  $this->Html->link($faq['question'], array('controller' => 'pages', 'action' => 'faq-view',$faq['slug']), array('role' => 'menuitem', 'escape' => false));?>
                                    </li>
                                      <?php }?>
                                </ul>
                                  <?php }
                                  }
                                } else { ?>
                                <div class="faq_head">No Record Found</div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>

                </div>


            </div>

        </div>
    </div>
</section>
<div class="clearfix"></div>
<?php echo $this->Js->writeBuffer(array('onDomReady' => false)); ?>
<script>
    function updateData(data) {
        var jsonData = JSON.parse(data);
        var dataArr = [];
        if (jsonData.response != '' && jsonData.response != null) {
            //alert('not null');
            if (jsonData.response != '0') {
                for (var i = 0; i < jsonData.response.length; i++) {
                    var counter = jsonData.response[i];
                    dataArr.push('<a href="faq-view/' + counter.faqName + '" class="search_field">' + counter.name + '</a>');
                }
            } else {
                dataArr.push('<div class="search_field">No record Found</div>');
            }
            $('.search_keyword').css('display', 'block');
            $('.search_keyword').html(dataArr);
        } else {
            $('.search_keyword').css('display', 'none');
            $('.search_keyword').html('');
        }
    }
    $(document).click(function () {
        $('.search_keyword').css('display', 'none');
    });
    /*$("#query").click(function (event) {
        $('.search_keyword').css('display', 'block');
        event.stopPropagation();
    });*/
</script>