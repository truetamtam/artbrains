<?php
/**
 * @var $data Items
 */
?>
<div class="row-fluid" id="back-<?php echo empty($data->name) ? 'some' : $data->name; ?>">
    <div class="site-desc-wrap">
        <article class="container site-desc">
            <div class="row-fluid">
                <div class="span4 site-desc-brief">
                    <div class="row-fluid site-link">
                        <a href="http://<?php echo $data->itemTitle; ?>" target="_blank"><?php echo $data->itemTitle; ?></a>
                    </div>
                    <div class="row-fluid">
                        <?php echo $data->itemContent; ?>
                    </div>
                    <?php if(!empty($data->tags)): ?>
                    <div class="row-fluid site-desc-tags">
                        <ul>
                        <?php
                        foreach (explode(', ', $data->tags) as $tag) {
                            echo CHtml::openTag('li', array(
                                'class' => 'sprites'
                            ));
                            echo $tag;
                            echo CHtml::closeTag('li');
                        }
                        ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="span8 pull-right site-desc-img-wrap">
                    <a
                       class="site-desc-img-link" href="http://<?php echo $data->itemTitle; ?>" target="_blank">
                        <img
                         src="/images/uploads/<?php echo $data->id; ?>/<?php echo $data->primeImage->imageFileName; ?>"
                         rel="list-gallery"
                         alt="<?php echo $data->primeImage->imageAlt; ?>" />
                    </a>
                    <span>
                        <?php if($data->triggerFull): ?>
                        <a class="sprites my-btn my-btn-desc" title="описание" href="#"></a>
                        <?php endif; ?>
                        <a class="sprites my-btn my-btn-link" title="посетить"
                           href="http://<?php echo $data->itemTitle; ?>" target="_blank"></a>
                    </span>
                </div>
            </div>
            <div class="sep">
               <span class="sep-img">
                   <img src="/images/hr_greenRomb.gif" alt="separator" width="45" height="16">
               </span>
            </div>
        </article>
    </div>
</div>