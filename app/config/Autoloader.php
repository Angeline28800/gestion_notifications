<?php

/**
 * Class Autoloader
 */
class Autoloader{
    static private $instance=NULL;
    static private $dirs = [];
    /**
     * Enregistre notre autoloader
     */
    static function register($dir){
        if (is_null(self::$instance)) {
            self::$instance = new Autoloader();
            spl_autoload_register(array(__CLASS__, 'autoload'));
        }
        self::$dirs[] = $dir;
    }

    /**
     * Inclus le fichier correspondant à notre classe
     * @param $class string Le nom de la classe à charger
     */
    static function autoload($class){
        foreach (self::$dirs as $d) {
            $f = dirname(__FILE__).DIRECTORY_SEPARATOR.$d.DIRECTORY_SEPARATOR.$class.'.php';
            if (file_exists($f)) {
                require($f);
                return true;
            }
        }
        return false;
    }
}
