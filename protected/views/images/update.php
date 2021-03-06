<?php
    $this->widget('bootstrap.widgets.TbExtendedGridView', array(
        'id' => 'images-related',
        'sortableRows' => true,
        'enableSorting' => false,
        'sortableAttribute' => 'sortOrder',
        'sortableAction' => 'images/sortupdate',
        'sortableAjaxSave' => true,
        'afterSortableUpdate' => 'js:function(id, position){ console.log("id: "+id+", position:"+position);}',
        'type'=>'striped bordered',
        'dataProvider' => $imagesDataProvider,
        'template' => "{items}",
        'columns' => array(
            array(
                'name' => 'id',
                'header' => '#',
                'headerHtmlOptions' => array('style' => 'width:30px'),
            ),
            array(
                'header' => 'изображение',
                'class' => 'bootstrap.widgets.TbImageColumn',
                'imagePathExpression' => '"/images/uploads/thumbs/x40/".$data->imageFileName',
            ),
            array(
                'name' => 'imageAlt',
                'header' => 'alt(seo)',
                'class' => 'bootstrap.widgets.TbJEditableColumn',
                'jEditableOptions' => array(
                    'type' => 'text',
                    'submitdata' => array('attribute' => 'imageAlt'),
                    'cssclass' => 'form',
                    'width' => 400,
                ),
            ),
            array(
                'template' => '{delete}',
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'deleteButtonUrl' => 'Yii::app()->createUrl("images/delete/{$data->id}")',
                'deleteConfirmation' => false,
            ),
        )
    ));
