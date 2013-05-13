<?php
/**
 * @var $model Items
 * @var $uimages XUploadForm
 * @var $this ItemsController
 */
?>

<h1>Создать объект</h1>

<?php $this->renderPartial('_form', array(
    'model' => $model,
    'uimages' => $uimages,
)); ?>