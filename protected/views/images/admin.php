<?php
$this->menu=array(
	array('label'=>'List Images','url'=>array('index')),
	array('label'=>'Create Images','url'=>array('create')),
);
?>

<h1>Manage Images</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'images-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'itemId',
		'imageName',
		'imageAlt',
		'sortOrder',
		'mime',
		/*
		'extension',
		'fileSize',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
