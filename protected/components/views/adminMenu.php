<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'id' => 'admin-menu',
    'htmlOptions' => array(
        'class' => 'well',
    ),
    'encodeLabel' => false,
    'stacked' => true,
    'type'=>'list',
    'items' => array(
        array('label'=>'управление', 'itemOptions'=>array('class'=>'nav-header')),
        '',
        array('label'=>'Items', 'url' => array('/items/admin'), 'itemOptions' => array('class' => 'nav-header'), 'icon' => 'list'),
        array('label' => 'создать', 'url'=> array('/items/create') ,'icon' => 'plus'),
        '',
        array(
            'label'=>'Все записи блога',
            'url' => array('/blog/post/index'),
            'icon' => 'eye-open',
        ),
        array(
            'label'=>'Блог',
            'url' => array('/blog/post/admin'),
            'itemOptions' => array('class' => 'nav-header'),
            'icon' => 'book',
        ),
        array('label'=>'новый пост', 'url' => array('/blog/post/create'), 'icon' => 'plus'),
        array(
            'label'=> Comment::model()->PendingCommentsCount > 0 && Yii::app()->controller->id != 'comment'?
                'комментарии <span class="badge badge-warning">'.Comment::model()->PendingCommentsCount.'</span>' :
                'комментарии',
            'url' => array('/blog/comment/admin'),
            'icon' => 'comment',
        ),
    )
));

