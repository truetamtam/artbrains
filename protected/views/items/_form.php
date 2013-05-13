<?php
/**
 * @var $model Items
 * @var $uimages XUploadForm
 * @var $this ItemsController
 * @var $form TbActiveForm
 */
?>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'items-form',
	'enableAjaxValidation'=>true,
    'focus' => $model->isNewRecord ? array($model, 'itemTitle') : NULL,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<?php echo $form->textFieldRow($model,'itemTitle',array('class'=>'span5','maxlength'=>255)); ?>
	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>64)); ?>
    <?php echo $form->redactorRow($model, 'itemContent', array(
                'class'=>'span4',
                'rows'=>5,
                'id'=>'itemContent')
            ); ?>
    <span id="chars-count">0</span>
    <?php echo $form->redactorRow($model, 'fullContent', array('class'=>'span4', 'rows'=>5)); ?>
    <?php echo $form->toggleButtonRow($model, 'triggerFull', array(
            'options' => array(
                'enabledLabel' => 'Вкл',
                'disabledLabel' => 'Выкл',
                'enabledStyle' => 'success',
            ),
    )); ?>
    <?php echo $form->select2Row($model, 'tags', array(
            'asDropDownList' => false,
            'data' => $model->tags,
            'options' => array(
                'tags' => Tag::model()->returnTagNames(),
                'tokenSeparators' => array(',', ' '),
                'placeholder' => 'теги',
                'width' => '50%',
                'minimumResultForSearch' => 3,
            ),
    )); ?>
	<?php echo $form->textAreaRow($model,'metaDesc',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
	<?php echo $form->textAreaRow($model,'metaKeywords',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
	<?php echo $form->textFieldRow($model,'url',array('class'=>'span5')); ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
            'type'=>'primary',
            'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
        )); ?>
    </div>
<?php $this->endWidget(); ?>

    <?php echo CHtml::openTag('h3');?>
        Загрузить изображения.
    <?php echo CHtml::closeTag('h3'); ?>
    <?php $this->widget('ext.xupload.XUpload', array(
        'url' => Yii::app()->createUrl("/items/upload"),
        'model' => $uimages,
        'attribute' => 'file',
        'multiple' => true,
        'htmlOptions' => array(
            'class' => 'well',
        )
    ));?>

<script type="text/javascript">

    $(function() {
        var $counter = $('#chars-count');
        var $itemContent = $('#itemContent');

        $counter.text($itemContent.val().length + ' (430)');

        $counter.click(function() {
            $(this).text($itemContent.val().length + ' (430)');
        });
    });

</script>