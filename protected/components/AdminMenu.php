<?php
/**
 * User: DC
 * Date: 01.12.12
 * Time: 20:25
 */
Yii::import('zii.widgets.CPortlet');

class AdminMenu extends CPortlet
{
    public function init()
    {
        parent::init();
    }

    protected function renderContent()
    {
        $this->render('adminMenu');
    }
}
