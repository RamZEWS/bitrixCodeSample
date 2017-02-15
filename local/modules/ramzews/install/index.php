<?php
class globus extends CModule{

    public $MODULE_ID = 'globus';

    public function __construct(){
        $arModuleVersion = array();
        $this->MODULE_VERSION = '0.0.1';
        $this->MODULE_VERSION_DATE = '2015-06-28';

        $this->MODULE_NAME = 'Globus library';
        $this->MODULE_DESCRIPTION = 'Globus library';
        $this->PARTNER_NAME = 'Globus-ltd';
        $this->PARTNER_URI = 'http://globus-ltd.com/';
    }

    public function DoInstall(){
        global $APPLICATION;
        RegisterModule('globus');
        $APPLICATION->IncludeAdminFile('Установка модуля', __DIR__ . '/step.php');
    }

    public function DoUninstall(){
        global $APPLICATION;
        UnRegisterModule('globus');
        $APPLICATION->IncludeAdminFile('Удаление модуля', __DIR__ . '/step.php');
    }
}