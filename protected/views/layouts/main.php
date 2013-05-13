<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="<?php echo Yii::app()->language ?>" />
    <meta name="company" content="<?php echo Yii::app()->name; ?>" />
	<title><?php echo Yii::app()->name.' - '.CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.adds.css" />
</head>

<body>
    <div id="wrap">
        <!--headNav-=======================================================-->
        <?php $this->widget('bootstrap.widgets.TbNavbar', array(
            'type' => 'inverse',
            'brand' => false,
            'collapse' => true,
            'htmlOptions' => array(
                'id' => 'nav-top',
                'class' => 'centered',
            ),
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'lastItemCssClass' => !Yii::app()->user->isGuest ? 'wrench' : null,
                    'items'=>array(
                        array('label'=>'Портфолио', 'url'=>array('/items/index')),
                        array('label'=>'Процесс', 'url'=>array('/site/page/view/process')),
                        array('label'=>'Контакт', 'url'=>array('/site/contact')),
                        array('label'=>'Блог', 'url'=>array('/blog/post/index')),
//                        array('label' => 'Вход',
//                            'url' => array('/user/login'),
//                            'visible' => Yii::app()->user->isGuest,
//                        ),
                        array(
                            'label'=>'Выход ('.Yii::app()->user->name.')',
                            'url'=>array('/user/logout'),
                            'visible'=>!Yii::app()->user->isGuest,
                        ),
                        array(
                            'icon' => 'wrench white',
                            'url'=>array('/items/admin'),
                            'visible'=>!Yii::app()->user->isGuest,
                        ),
                    ),
                )
            )
        )); ?>
        <!--  headNavEnd-====================================================-->

        <div id="main">
            <div class="container my-cnt">
                <div class="row">
                    <div class="my-ph pull-right"><span>+7(495)777-77-77</span></div>
                    <a class="my-ml pull-right" href="mailto:<?php echo Yii::app()->params['my-ml'];?>">
                        <i></i>
                        <?php echo Yii::app()->params['my-ml'];?>
                    </a>
                </div>
            </div>
            <div class="row-fluid">
                <a class="sprites logo" href="<?php echo Yii::app()->homeUrl;?>"></a>
            </div>
            <!--content-=======================================================-->
            <?php echo $content; ?>
            <!--contentEnd-====================================================-->
            <div id="push"></div>
        </div>
    </div>

    <!--footer-=======================================================-->
    <footer>
        <a class="sprites logo-footer" href="<?php echo Yii::app()->homeUrl;?>"></a>
    </footer>
    <!--footerEnd-====================================================-->
</body>
</html>