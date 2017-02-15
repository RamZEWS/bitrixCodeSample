<?php
class ramzews extends CModule{

    public $MODULE_ID = 'ramzews';

    public function __construct(){
        $arModuleVersion = array();
        $this->MODULE_VERSION = '0.0.1';
        $this->MODULE_VERSION_DATE = '2015-06-28';

        $this->MODULE_NAME = 'RamZEWS library';
        $this->MODULE_DESCRIPTION = 'RamZEWS library';
        $this->PARTNER_NAME = 'RamZEWS-ltd';
        $this->PARTNER_URI = '';
    }

    public function DoInstall(){
        global $APPLICATION;
        RegisterModule('ramzews');
        $APPLICATION->IncludeAdminFile('Установка модуля', __DIR__ . '/step.php');
    }

    public function DoUninstall(){
        global $APPLICATION;
        UnRegisterModule('ramzews');
        $APPLICATION->IncludeAdminFile('Удаление модуля', __DIR__ . '/step.php');
    }
}