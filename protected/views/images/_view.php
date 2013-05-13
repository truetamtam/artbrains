<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('itemId')); ?>:</b>
	<?php echo CHtml::encode($data->itemId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('imageName')); ?>:</b>
	<?php echo CHtml::encode($data->imageName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('imageAlt')); ?>:</b>
	<?php echo CHtml::encode($data->imageAlt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sortOrder')); ?>:</b>
	<?php echo CHtml::encode($data->sortOrder); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mime')); ?>:</b>
	<?php echo CHtml::encode($data->mime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('extension')); ?>:</b>
	<?php echo CHtml::encode($data->extension); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('fileSize')); ?>:</b>
	<?php echo CHtml::encode($data->fileSize); ?>
	<br />

	*/ ?>

</div>