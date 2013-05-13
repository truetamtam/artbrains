<?php
$this->breadcrumbs=array(
	'Объекты'=>array('index'),
	'Управление',
);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider' => $model->search(),
    'enableSorting' => false,
    'template' => "{summary}{items}",
    'columns' => array_merge(
        array(
            array(
                'class'=>'bootstrap.widgets.TbRelationalColumn',
                'name' => 'изображение',
                'url' => $this->createUrl('items/additionalimages'),
                'value'=> '"показать"',
            ),
        ),
        array(
            'id',
            'name',
            array(
                'name' => 'weight',
                'class' => 'bootstrap.widgets.TbJEditableColumn',
                'saveURL' => '/items/setweight',
                'jEditableOptions' => array(
                    'type' => 'text',
                    'submitdata' => array('attribute' => 'weight'),
                    'cssclass' => 'form',
                    'width' => 40,
                ),
            ),
            array(
                'class'=>'bootstrap.widgets.TbToggleColumn',
                'toggleAction'=>'items/toggle',
                'name' => 'status',
            ),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'template' => '{update}{delete}'
            )
        )
    ),
));
