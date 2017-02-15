<?php
/**
 * RamzewsModule class
 * Class includes submodules to project
 */
class RamzewsModule {
    /**
     * Path to module
     * @var string
     */
    public $path = "/local/modules/ramzews";
    public $id = 'ramzews';

    /**
     * Submodules
     * @var array
     */
    public $modules = array(
        'main',
        'file',
        'common',
    );

    /**
     * List of classes for include
     * @var array
     */
    public static $classes = array();

    /**
     * @return string
     */
    public static function getDocumentRoot(){
        return \Bitrix\Main\Loader::getDocumentRoot();
    }

    /**
     * Load all classes and submodules
     */
    public function autoload(){
        $docRoot = self::getDocumentRoot();

        foreach($this->modules as $folder) {
            if(file_exists($docRoot.$this->path."/modules/".$folder."/init.php")) {
                $folderClasses = require($docRoot.$this->path."/modules/".$folder."/init.php");
                foreach($folderClasses as $cPath) {
                    $class = end(explode('/', $cPath));
                    self::$classes[$class] = $this->path."/modules/".$folder."/".$cPath.".php";
                }
            }
        }

        CModule::AddAutoloadClasses($this->id, self::$classes);
    }

}

