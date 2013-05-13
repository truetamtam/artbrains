<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle='Контакт';
$this->breadcrumbs=array(
	'Contact',
);
?>

<div class="sep">
   <span>Контакт</span>
</div>

<div id="page-wrap">
<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="alert in alert-block alert-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<div class="form row-fluid">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'contact-form',
    'type' => 'horizontal',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

    <?php echo $form->textFieldRow($model,'name', array('prepend' => '<i class="icon-user"></i>')); ?>
    <?php echo $form->textFieldRow($model,'email', array('prepend' => '@')); ?>
    <?php echo $form->textFieldRow($model,'subject', array('prepend' => '<i class="icon-question-sign"></i>')); ?>
    <?php echo $form->textAreaRow($model, 'body', array('class'=>'span6', 'rows'=>7)); ?>

    <?php echo $form->captchaRow($model, 'verifyCode'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Отправить')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>

</div>