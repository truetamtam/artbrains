<?php

class ItemsController extends SecureController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
//	public function filters()
//	{
//		return array(
//			'accessControl', // perform access control for CRUD operations
//		);
//	}

    public function actions()
    {
        return array(
            'toggle' => array(
                'class'=>'bootstrap.actions.TbToggleAction',
                'modelName' => 'Items',
            ),
        );
    }

    public function filters()
    {
        return array(
            array(
                'COutputCache +index',
                'duration' => 24*3600*365,
                'dependency' => array(
                    'class' => 'CDbCacheDependency',
                    'sql' => 'SELECT MAX(updated) FROM ptf_items',
                )
            ),
        );
    }

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
//	public function accessRules()
//	{
//		return array(
//			array('allow',  // allow all users to perform 'index' and 'view' actions
//				'actions'=>array('index','view'),
//				'users'=>array('*'),
//			),
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete','update','create','upload','additionalimages','toggle'),
//				'users'=>Yii::app()->getModule('user')->getAdmins(),
//			),
//			array('deny',  // deny all users
//				'users'=>array('*'),
//			),
//		);
//	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
        $this->layout = '//layouts/column1';
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        $model = new Items();

        $this->performAjaxValidation($model);

        Yii::import( "xupload.models.XUploadForm" );
        $uimages = new XUploadForm();
        //Check if the form has been submitted
        if( isset( $_POST['Items'] ) ) {
            //Assign our safe attributes
            $model->attributes = $_POST['Items'];
            //Start a transaction in case something goes wrong
            $transaction = Yii::app( )->db->beginTransaction( );
            try {
                //Save the model to the database
                if($model->save()){
                    $transaction->commit();
                    $this->redirect(array('admin'));
                }
            } catch(Exception $e) {
                $transaction->rollback( );
                Yii::app( )->handleException($e);
            }
        }

		$this->render('create',array(
            'model'=>$model,
            'uimages' => $uimages,
        ));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
        Yii::import( "xupload.models.XUploadForm" );
        $uimages = new XUploadForm;
        $model=$this->loadModel($id);
        $imagesData = $this->getImagesData($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

        if( isset( $_POST['Items'] ) ) {
            //Assign our safe attributes
            $model->attributes = $_POST['Items'];
            //Start a transaction in case something goes wrong
            $transaction = Yii::app( )->db->beginTransaction( );
            try {
                //Save the model to the database
                if($model->save()){
                    $transaction->commit();
                    Yii::app()->user->setFlash('success', 'Сохранено.');
//                    $this->redirect(array('update', 'id'=>$model->id));
                }
            } catch(Exception $e) {
                $transaction->rollback( );
                Yii::app( )->handleException($e);
            }
        }

		$this->render('update',array(
			'model'=>$model,
            'uimages' => $uimages,
            'imagesDataProvider' => $imagesData,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        $this->layout = '//layouts/index';

        if(!empty(Yii::app()->params['indexDesc'])) {
            Yii::app()->clientScript->registerMetaTag(Yii::app()->params['indexDesc'], 'description', null,
                array(
                    'lang' => Yii::app()->language
                )
            );
        }

        if(!empty(Yii::app()->params['author'])) {
            Yii::app()->clientScript->registerMetaTag(Yii::app()->params['author'], 'author', null, array(
                'lang' => Yii::app()->language
            ));
        }

        Yii::app()->bootstrap->registerAssetCss('bootstrap-image-gallery.min.css');
        Yii::app()->bootstrap->registerAssetJs('fileupload/load-image.min.js');
        Yii::app()->bootstrap->registerAssetJs('bootstrap-image-gallery.min.js');



		$dataProvider=new CActiveDataProvider('Items',array(
            'criteria' => array(
                'with' => array('primeImage'),
                'condition' => 'status='.Items::STATUS_ACTIVE,
                'order' => 'weight ASC',
            ),
            'pagination' => false,
        ));

        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Items('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Items']))
			$model->attributes=$_GET['Items'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

    public function getImagesData($id)
    {
        $data = new CActiveDataProvider('Images', array(
            'criteria' => array(
                'condition' => 'itemId='.$id,
                'order' => 'sortOrder ASC',
            ),
            'pagination' => false,
        ));

        return empty($data) == true ? false : $data;
    }

    public function actionAdditionalImages()
    {
        $itemId = Yii::app()->getRequest()->getParam('id');

        $this->renderPartial("_relational", array(
            'id' => $itemId,
            'gridDataProvider' => $this->getImagesData($itemId),
            'gridColumns' => array('id', 'itemId', 'imageName'),
        ));
    }

    /**
     * @throws CDbException
     * @throws CHttpException
     */
    public function actionSetweight()
    {
        $r = Yii::app()->getRequest();
        if($r->getParam('editable')) {

            $id = $r->getParam('id');
            $model = Items::model()->findByPk($id);
            if($model !== NULL) {

                $model->weight = $r->getParam('value');
                if($model->save()) {
                    echo $model->weight;
                }
                else
                {
                    throw new CDbException('action updateeditable error');
                }
            }
            else
            {
                throw new CHttpException('bad request', 400);
            }
            Yii::app()->end();
        }
    }

    public function actionUpload( ) {
        Yii::import( "xupload.models.XUploadForm" );
        //Here we define the paths where the files will be stored temporarily
        $tmpDir = realpath( Yii::app( )->getBasePath( )."/../images/uploads/tmp" ).DIRECTORY_SEPARATOR;
        $tmpDirPublic = Yii::app( )->getBaseUrl( )."/images/uploads/tmp/";

        $thumbDir = realpath(Yii::app()->getBasePath()."/../images/uploads/thumbs").DIRECTORY_SEPARATOR;

        $thumbSmallDir = realpath( Yii::app( )->
            getBasePath( )."/../images/uploads/thumbs/".Yii::app()->params['thumbSmall']).DIRECTORY_SEPARATOR;
        $thumbMediumDir = realpath( Yii::app( )->
            getBasePath( )."/../images/uploads/thumbs/".Yii::app()->params['thumbMedium'] ).DIRECTORY_SEPARATOR;

        $thumbSmallDirPbl = Yii::app()
            ->getBasePath()."/../images/uploads/thumbs/".Yii::app()->params['thumbSmall'].'/';
        $thumbMediumDirPbl = Yii::app()
            ->getBasePath()."/../images/uploads/thumbs/".Yii::app()->params['thumbMedium'].'/';

        $thumbPreview = Yii::app()->getBaseUrl()."/images/uploads/thumbs/".Yii::app()->params['thumbMedium'].'/';

        //This is for IE which doens't handle 'Content-type: application/json' correctly
        header( 'Vary: Accept' );
        if( isset( $_SERVER['HTTP_ACCEPT'] )
            && (strpos( $_SERVER['HTTP_ACCEPT'], 'application/json' ) !== false) ) {
            header( 'Content-type: application/json' );
        } else {
            header( 'Content-type: text/plain' );
        }

        //Here we check if we are deleting and uploaded file
        if(isset( $_GET["_method"])) {
            if($_GET["_method"] == "delete") {
                if($_GET["file"][0] !== '.') {
                    $file = $tmpDir.$_GET["file"];
                    $thumbSmallImg = $thumbSmallDir.$_GET["file"];
                    $thumbMediumImg = $thumbMediumDir.$_GET["file"];
                    if( is_file( $file ) ) {
                        unlink($file);
                        unlink($thumbSmallImg);
                        unlink($thumbMediumImg);
                    }
                }
                echo json_encode(true);
            }
        } else {
            $model = new XUploadForm;
            $model->file = CUploadedFile::getInstance( $model, 'file' );
            //We check that the file was successfully uploaded
            if( $model->file !== null ) {
                //Grab some data
                $model->mime_type = $model->file->getType( );
                $model->size = $model->file->getSize( );
                $model->name = $model->file->getName( );
                //(optional) Generate a random name for our file
                $filename = md5( Yii::app( )->user->id.microtime( ).$model->name);
                $filename .= ".".$model->file->getExtensionName( );
                if( $model->validate( ) ) {
                    //Move our file to our temporary dir
                    $model->file->saveAs( $tmpDir.$filename );
                    chmod( $tmpDir.$filename, 0777 );
                    //here you can also generate the image versions you need
                    //using something like PHPThumb
                    $thumb = Yii::app()->phpThumb->create($tmpDir.$filename);
                    $thumb->resize((int)Yii::app()->params['thumbMedium']);
                    $thumb->save($thumbMediumDirPbl.$filename);
                    chmod( $thumbMediumDir.$filename, 0777 );
                    $thumb->resize((int)Yii::app()->params['thumbSmall']);
                    $thumb->save($thumbSmallDirPbl.$filename);
                    chmod( $thumbSmallDir.$filename, 0777 );
                    //Now we need to save this path to the user's session
                    if( Yii::app()->user->hasState('images')) {
                        $userImages = Yii::app()->user->getState('images');
                    } else {
                        $userImages = array();
                    }
                     $userImages[] = array(
                        "path" => $tmpDir.$filename,
                        //the same file or a thumb version that you generated
                        "thumb" => $thumbDir.$filename,
                        "filename" => $filename,
                        'size' => $model->size,
                        'mime' => $model->mime_type,
                        'name' => $model->name,
                    );
                    Yii::app()->user->setState('images', $userImages);

                    //Now we need to tell our widget that the upload was succesfull
                    //We do so, using the json structure defined in
                    // https://github.com/blueimp/jQuery-File-Upload/wiki/Setup
                    echo json_encode( array( array(
                            "name" => $model->name,
                            "type" => $model->mime_type,
                            "size" => $model->size,
                            "url" => $tmpDirPublic.$filename,
                            "thumbnail_url" => $thumbPreview.$filename,
                            "delete_url" => $this->createUrl( "upload", array(
                                "_method" => "delete",
                                "file" => $filename
                            ) ),
                            "delete_type" => "POST"
                        ) ) );
                } else {
                    //If the upload failed for some reason we log some data and let the widget know
                    echo json_encode( array(
                        array( "error" => $model->getErrors( 'file' ),
                    ) ) );
                    Yii::log( "XUploadAction: ".CVarDumper::dumpAsString( $model->getErrors( ) ),
                        CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction"
                    );
                }
            } else {
                throw new CHttpException( 500, "Could not upload file" );
            }
        }
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Items::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='items-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
