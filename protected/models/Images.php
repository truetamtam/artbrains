<?php

/**
 * This is the model class for table "ptf_images".
 *
 * The followings are the available columns in table 'ptf_images':
 * @property string $id
 * @property string $itemId
 * @property string $imageName
 * @property string $imageFileName
 * @property string $imageAlt
 * @property string $sortOrder
 * @property string $mime
 * @property string $extension
 * @property string $fileSize
 */
class Images extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Images the static model class
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
		return '{{images}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('itemId, imageName', 'required'),
			array('itemId, fileSize', 'length', 'max'=>11),
			array('imageName', 'length', 'max'=>255),
			array('sortOrder', 'length', 'max'=>2),
			array('mime', 'length', 'max'=>64),
			array('extension', 'length', 'max'=>12),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, itemId, imageName, imageFileName, imageAlt, sortOrder, mime, extension, fileSize', 'safe', 'on'=>'search'),
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
            'item' => array(self::BELONGS_TO, 'Items', 'itemId'),
            'sortOrderMax' => array(self::STAT, 'Images', 'sortOrder'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'itemId' => 'ItemId',
			'imageName' => 'Имя изображения',
            'imageFileName' => 'Имя файла',
			'imageAlt' => 'alt',
			'sortOrder' => 'Порядок сортировки',
			'mime' => 'mime',
			'extension' => 'расширение',
			'fileSize' => 'Размер файла',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('itemId',$this->itemId,true);
		$criteria->compare('imageName',$this->imageName,true);
		$criteria->compare('imageAlt',$this->imageAlt,true);
		$criteria->compare('sortOrder',$this->sortOrder,true);
		$criteria->compare('mime',$this->mime,true);
		$criteria->compare('extension',$this->extension,true);
		$criteria->compare('fileSize',$this->fileSize,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    protected function removeImage()
    {
        $fileName = $this->imageFileName;

        $imagesDirId = realpath(Yii::app()->getBasePath()."/../images/uploads/{$this->id}").DIRECTORY_SEPARATOR;
        $thumbSmallDir = realpath(Yii::app()
            ->getBasePath()."/../images/uploads/thumbs/".Yii::app()->params['thumbSmall']).DIRECTORY_SEPARATOR;
        $thumbMediumDir = realpath(Yii::app()
            ->getBasePath()."/../images/uploads/thumbs/".Yii::app()->params['thumbMedium']).DIRECTORY_SEPARATOR;

        if(is_dir($imagesDirId)) {
            chmod($imagesDirId, 0777);
            chmod($thumbSmallDir, 0777);
            chmod($thumbMediumDir, 0777);

            if(is_file($imagesDirId.$fileName)
            && is_file($thumbSmallDir.$fileName)
            && is_file($thumbMediumDir.$fileName)) {
                unlink($imagesDirId.$fileName);
                unlink($thumbSmallDir.$fileName);
                unlink($thumbMediumDir.$fileName);
            }
        }
    }

    protected function afterDelete()
    {
        $this->removeImage();
        parent::afterDelete();
    }
}