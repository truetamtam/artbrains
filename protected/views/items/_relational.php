<?php
echo CHtml::tag('h4',array(),'изображения: "'.$id.'"');
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'type'=>'striped',
    'dataProvider' => $gridDataProvider,
    'enableSorting' => false,
    'enablePagination' => false,
    'pager' => array(
        'pageSize' => 5,
    ),
	'template' => "{items}",
    'columns' => array_merge(
        array(
            array(
                'class' => 'bootstrap.widgets.TbImageColumn',
                'imagePathExpression' => '"/images/uploads/thumbs/'.Yii::app()->params['thumbSmall'].'/".$data->imageFileName',
            )
        ),
        array(
            'imageAlt',
            array(
                'name' => 'fileSize',
                'value' => 'Yii::app()->format->formatSize($data->fileSize)',
            )
        )
    ),
));

