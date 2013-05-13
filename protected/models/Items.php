<?php

/**
 * This is the model class for table "ptf_items".
 *
 * The followings are the available columns in table 'ptf_items':
 * @property integer $id
 * @property integer $weight
 * @property string $name
 * @property string $tags
 * @property string $itemTitle
 * @property string $itemContent
 * @property string $fullContent
 * @property integer $triggerFull
 * @property string $metaDesc
 * @property string $metaKeywords
 * @property string $url
 * @property integer $status
 * @property string $created
 * @property string $updated
 */
class Items extends CActiveRecord
{
    const STATUS_ACTIVE = 1;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Items the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{items}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('itemTitle, name, itemContent, metaDesc, metaKeywords, url', 'required'),
			array('status, triggerFull', 'numerical', 'integerOnly'=>true, 'min'=>0, 'max'=>1),
            array('weight', 'numerical'),
			array('name', 'length', 'max'=>64),
			array('itemTitle, url', 'length', 'max'=>255),
            array('tags' ,'normalizeTags'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('itemTitle, status', 'safe', 'on'=>'search'),
            array('fullContent', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'images' => array(self::HAS_MANY, 'Images', 'itemId'),
            'primeImage' => array(self::HAS_ONE, 'Images', 'itemId', 'order' => 'sortOrder'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => '#',
            'weight' => 'Порядок',
            'itemTitle' => 'Заголовок элемента(адрес сайта)',
            'name' => 'Наименование',
            'itemContent' => 'Краткое описание',
            'fullContent' => 'Подробное описание',
            'triggerFull' => 'Вкл/Выкл Полное описание',
            'metaDesc' => 'Описание страницы',
            'metaKeywords' => 'Ключевые слова',
            'url' => 'ЧПУ',
            'status' => 'Откл./Вкл.',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria=new CDbCriteria;

		$criteria->compare('itemTitle',$this->itemTitle,true);
		$criteria->compare('status',$this->status);
        $criteria->order = 'weight ASC';
        $criteria->with = array('images');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function normalizeTags()
    {
        $this->tags = Tag::array2string(array_unique(Tag::string2array($this->tags)));
    }

    /**
     * Adding images on save.
     * @throws Exception
     */
    protected function addImages() {
        //If we have pending images
        if( Yii::app( )->user->hasState('images') ) {
            $userImages = Yii::app( )->user->getState('images');
            //Resolve the final path for our images
            $path = Yii::app( )->getBasePath( )."/../images/uploads/{$this->id}/";
            //Create the folder and give permissions if it doesnt exists
            if( !is_dir( $path ) ) {
                mkdir( $path );
                chmod( $path, 0777 );
            }
            // Finding max number for sort order
            $command = Yii::app()->db->createCommand('SELECT MAX(`sortOrder`) as `imagesCount` FROM {{images}}');
            $res = $command->queryAll();
            $imgCount = (int)$res[0]['imagesCount'];
            $imgSort = ($imgCount > 0) ? $imgCount + 1 : 1;

            //Now lets create the corresponding models and move the files
            foreach( $userImages as $image ) {
                if( is_file( $image["path"] ) ) {
                    if( rename( $image["path"], $path.$image["filename"] ) ) {
                        chmod( $path.$image["filename"], 0777 );
                        $img = new Images();
                        $img->fileSize = $image["size"];
                        $img->mime = $image["mime"];
                        $img->imageName = $image["name"];
                        $img->imageFileName = $image['filename'];
                        $img->imageAlt = $image["name"];
                        $img->sortOrder = $imgSort;
                        $img->itemId = $this->id;
                        if( !$img->save( ) ) {
                            //Its always good to log something
                            Yii::log( "Could not save Image:\n".CVarDumper::dumpAsString(
                                $img->getErrors( ) ), CLogger::LEVEL_ERROR );
                            //this exception will rollback the transaction
                            throw new Exception('Could not save Image');
                        }
                        $imgSort++;
                    }
                } else {
                    //You can also throw an execption here to rollback the transaction
                    Yii::log( $image["path"]." is not a file", CLogger::LEVEL_WARNING );
                }
            }
            //Clear the user's session
            Yii::app( )->user->setState( 'images', null );
        }
    }

    protected function removeImages()
    {
        $imagesDirId = realpath(Yii::app()->getBasePath()."/../images/uploads/{$this->id}").DIRECTORY_SEPARATOR;
        $thumbSmallDir = realpath(Yii::app()
            ->getBasePath()."/../images/uploads/thumbs/".Yii::app()->params['thumbSmall']).DIRECTORY_SEPARATOR;
        $thumbMediumDir = realpath(Yii::app()
            ->getBasePath()."/../images/uploads/thumbs/".Yii::app()->params['thumbMedium']).DIRECTORY_SEPARATOR;

        if(is_dir($imagesDirId)) {
            chmod($imagesDirId, 0777);
            chmod($thumbSmallDir, 0777);
            chmod($thumbMediumDir, 0777);
            $images = scandir($imagesDirId);
            foreach($images as $image) {
                if($image !== '.' && $image !== '..') {
                    if(is_file($imagesDirId.$image)
                    && is_file($thumbSmallDir.$image)
                    && is_file($thumbMediumDir.$image)) {
                        unlink($imagesDirId.$image);
                        unlink($thumbSmallDir.$image);
                        unlink($thumbMediumDir.$image);
                    }
                }
            }
            reset($images);
            rmdir($imagesDirId);
        }

    }

    protected function beforeSave()
    {
        if(parent::beforeSave()) {
            if(!$this->isNewRecord) {
                $this->updated = new CDbExpression('NOW()');
            } else {
                $this->updated = new CDbExpression('NOW()');
            }
            return true;
        } else {
            return false;
        }
    }

    protected function afterSave()
    {
        $this->addImages();
        parent::afterSave();
    }

    protected function afterDelete()
    {
        $this->removeImages();
        parent::afterDelete();
    }
}