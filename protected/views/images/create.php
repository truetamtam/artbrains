<?php
$this->breadcrumbs=array(
	'Images'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Images','url'=>array('index')),
	array('label'=>'Manage Images','url'=>array('admin')),
);
?>

<h1>Create Images</h1>
<?php
$this->widget('xupload.XUpload', array(
    'url' => Yii::app()->createUrl("images/upload", array("parent_id" => 1)),
    'model' => $model,
    'attribute' => 'file',
    'multiple' => true,
));
?>