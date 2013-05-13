<?php
class SecureController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl'
        );
    }

    /**
     * @return array
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            // All actions to authenticated user's
            array('allow',
                'users' => Yii::app()->getModule('user')->getAdmins(),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
}