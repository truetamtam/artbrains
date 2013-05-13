<?php
    /**
     * @var $this ItemsController
     */
//    if($this->beginCache('Items-index-cache', array('dependency' => array(
//        'class' => 'system.caching.dependencies.CDbCacheDependency',
//        'sql' => 'SELECT MAX(updated) FROM ptf_items',)))) {
//    $this->pageTitle = 'Портфолио'
    ?>

<div id="gallery" data-toggle="modal-gallery" data-target="#modal-gallery" data-filter="*" >
<div class="container">
    <div class="sep">
       <span>Веб — сайты</span>
    </div>
</div>

<?php $this->widget('bootstrap.widgets.TbThumbnails', array(
    'id' => 'gal-container',
    'dataProvider' => $dataProvider,
    'template' => '{items}',
    'itemView' => '_thumb',
)); ?>
</div>

<script type="text/javascript">
    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $(function() {

        var $imgWrap = $('.site-desc-img-wrap');
        var $imgWrapSpan = $('.site-desc-img-wrap > span');
        var $imgLinks = $('.site-desc-img-wrap span > a');
        var pause = 50;

        $(window).resize(function() {
            delay(function() {

                var width = $(window).width();

                if( width > 1024) {
                    $imgWrapSpan.css('display', 'none');
                    $imgWrap.hover(function() {
                        $poped = $(this).children('span').fadeIn('fast');
                    }, function() {
                        $poped.stop(true, true).fadeOut('fast');
                    });
                    $imgLinks.tooltip({trigger: 'hover'});
                } else {
                    $imgWrap.off();
                    $imgLinks.tooltip('destroy');
                    $imgWrapSpan.css('display', 'block');
                }

            }, pause );

        });

        $(window).resize();
    });
</script>
<?php //$this->endCache(); ?>
<?php //} ?>