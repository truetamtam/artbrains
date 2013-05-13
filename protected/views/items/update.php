    <?php
    $this->breadcrumbs=array(
        'Объекты'=>array('admin'),
        'Редактирование '.$model->itemTitle,
    );
    ?>

    <?php $this->widget('bootstrap.widgets.TbTabs', array(
        'type' => 'tabs',
        'tabs' => array(
            array(
                'label' => 'Объект #'.$model->id,
                'active' => true,
                'content' => $this->renderPartial('_form', array(
                    'model' => $model,
                    'uimages' => $uimages,
                ), true),
            ),
            array(
                'label' => 'Изображения',
                'content' => $this->renderPartial('u_images_form', array(
                    'imagesDataProvider' => $imagesDataProvider,
                ), true),
            ),
        ),
    ));?>

