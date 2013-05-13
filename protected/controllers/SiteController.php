<?php

class SiteController extends Controller
{

    public $layout = '//layouts/column1';
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
            'upload' => array(
                'class' => 'xupload.actions.XUploadAction',
                'path' => Yii::app() -> getBasePath() . "/../images/uploads",
                "publicPath" => Yii::app()->getBaseUrl()."/images/uploads"
            ),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
        Yii::import('xupload.models.XUploadForm');
        $model = new XUploadForm();
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index', array(
            'model'=>$model,
        ));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{

        if(!empty(Yii::app()->params['siteContactText'])) {
            Yii::app()->clientScript->registerMetaTag(Yii::app()->params['siteContactText'], 'description', null,
                array('lang' => Yii::app()->language));
        }

		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Спасибо! Мы очень скоро вам ответим!');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
    
    public function actionHanoy()
    {
        if(Yii::app()->request->isPostRequest) {

            $fileName = $_POST['moveLogId'].'.txt';
            $file = realpath(Yii::app()->basePath.'/../docs/tmp').DIRECTORY_SEPARATOR.$fileName;
            $d = fopen($file, 'w');
            chmod($file, 0777);
            fwrite($d, $_POST['moveLog']);
            fclose($d);

            echo $fileName;
        }
    }
}