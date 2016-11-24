<?php

class Fw {

    private static $__config_def;
    private static $__app_uri;
    private static $__core = Array('pcontroller', 'perror', 'puri');
    private static $__db;
    public static $error;

    // public static $title;
    public static function getVersion() {
        return '0.0.1';
    }

    public static function run($path) {
        //self :: includeHelper();
        require_once($path);
        self :: $__config_def = $def;
        $app_uri_obj = new Puri(self :: getConfig('uri_style'));
        $app_uri = $app_uri_obj->get_app_uri();
        $app_error = new Perror();
        self :: $error = $app_error;
        self :: includeModel();
        $user = new Puser();
        // self::$__db = new Pdb();
        if (is_array($app_uri)) {
            self :: $__app_uri = $app_uri;
        } else {
            $app_error->show($app_uri);
        }
        $controller_class_file = self :: getConfig('app_directory') . '/controller/' . strtolower(self :: $__app_uri['c']) . ".php";
        if (file_exists($controller_class_file)) {
            require_once($controller_class_file);
            $app_controller = new self :: $__app_uri['c'];
            $app_controller->run();
            if (method_exists($app_controller, self :: $__app_uri['a'])) {
                if (self :: $__app_uri['a'] != self :: $__app_uri['c']) {
                    $app_controller->actionFactory(self :: $__app_uri['a']);
                    // eval('$app_controller->'.self::$__app_uri['a'].'();');
                }
            } else {
                $app_error->show('Method ' . self :: $__app_uri['a'] . ' is no exist');
            }
        } else {
            $app_error->show('Controller file ' . $controller_class_file . ' is no exist');
        }
        /**
         * try {
         * $app_controller = new self::$__app_uri['c'];
         * }catch (Exception $e){
         * echo($e->__toString());
         * }
         */
    }

    public static function getConfig($name) {
        return self :: $__config_def[$name];
    }

    public static function getUri() {
        return self :: $__app_uri;
    }

    public static function createUrl($path='',$parameter=null) {
        if(empty($parameter)){
            $url = $_SERVER['SCRIPT_NAME'].'/'.$path;
        }else {
            if(self :: getConfig('uri_style') != 'path'){
                $path = trim($path,'/');
                $path_array = explode('/',$path);
                if(isset($path_array[0])){
                    $c_path = 'c='.$path_array[0];
                }else{
                    $c_path = 'c='.Fw::getConfig('default_controller');
                }
                if(isset($path_array[1])){
                    $a_path = 'a='.$path_array[1];
                }else{
                    $a_path = 'a=start';
                }
                $path = $c_path.'&'.$a_path;
            }else{
                
            }
            $parameter_str = '';
            if(self :: getConfig('uri_style') == 'path') {
                $path = '/'.$path;
            }else {
                $path = '?'.$path;
            }
            foreach($parameter as $key=>$val) {
                if(self :: getConfig('uri_style') == 'path') {
                    $parameter_str .= '/'.$key.'/'.$val;
                }else{
                    $parameter_str .= '&'.$key.'='.$val;
                }
                
            }
            $url = $_SERVER['SCRIPT_NAME'].$path.$parameter_str;
        }
        
        return $url;;
    }

    private static function includeModel() {
        $model_dir = self :: getConfig('app_directory') . '/model/';
        if ($handle = opendir($model_dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $extend = pathinfo($file);
                    $extendName = strtolower($extend["extension"]);
                    if ($extendName == 'php') {
                        require_once($model_dir . $file);
                        $class_name = ucwords(str_replace('.php', '', $file));
                        $tmpClass = new $class_name();
                        eval($class_name . '::$tableName = strtolower($tmpClass->tableName());');
                        eval($class_name . '::$className = $tmpClass->tableName();');
                    }
                }
            }
            closedir($handle);
        }
    }

    private static function includeHelper() {
        $helper_path = dirname(__FILE__) . '/helper/';
        require_once($helper_path . 'str.php');
        require_once($helper_path . 'validate.php');
    }

    public static function autoload($classname) {
        $class_file = dirname(__FILE__) . '/core/' . strtolower($classname) . ".php";
        if (file_exists($class_file)) {
            require_once($class_file);
        }
    }

}

spl_autoload_register(array('Fw', 'autoload'));
