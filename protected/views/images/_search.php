<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'itemId',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'imageName',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textAreaRow($model,'imageAlt',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'sortOrder',array('class'=>'span5','maxlength'=>2)); ?>

	<?php echo $form->textFieldRow($model,'mime',array('class'=>'span5','maxlength'=>64)); ?>

	<?php echo $form->textFieldRow($model,'extension',array('class'=>'span5','maxlength'=>12)); ?>

	<?php echo $form->textFieldRow($model,'fileSize',array('class'=>'span5','maxlength'=>11)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
		    'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
