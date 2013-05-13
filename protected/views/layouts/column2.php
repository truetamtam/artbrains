<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
    <div class="row">
        <div class="span4 pull-right">
            <!--sideNav-=======================================================-->
            <?php if(!Yii::app()->user->isGuest) {
                $this->widget('AdminMenu', array(
                    'contentCssClass' => null,
                    'decorationCssClass' => null,
                ));
            } ?>
            <!--sideNavEnd-====================================================-->
        </div>
        <div class="span8">
            <?php if(!empty($this->breadcrumbs)):?>
                <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                )); ?>
            <?php endif;?>
            <?php $this->widget('bootstrap.widgets.TbAlert', array(
                'id' => 'statusMsg',
                'block'=>true, // display a larger alert block?
                'fade'=>true, // use transitions?
                'closeText'=>'×', // close link text - if set to false, no close link is displayed
                'alerts' => array(
                    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'),
                ),
            )); ?>
            <?php echo $content; ?>
        </div><!-- content -->
    </div>
</div>
<?php $this->endContent(); ?>