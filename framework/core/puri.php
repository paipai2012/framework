<?php
class Puri
{
    private static $__pathinfo;
    private static $__style;
    function __construct($style)
    {
        self::$__style = $style;
    }
    public static function get_app_uri()
    {
        switch (self::$__style)
        {
            case 'string':
                if(!isset($_GET['c'])){
                    return Array('c'=>Fw::getConfig('default_controller'),'a'=>'start');
                }else{
                    return self::__translate_from_string();
                }
                break;
            case 'path':
                if(isset($_SERVER['PATH_INFO']))
                {
                    self::$__pathinfo = $_SERVER['PATH_INFO'];
                }else{
                    return Array('c'=>Fw::getConfig('default_controller'),'a'=>'start');
                }
                return self::__translate_from_path(self::$__pathinfo);
                break;
            default:
                return self::__translate_from_string();
                break;
        }
    }
    private static function __translate_from_string()
    {
        if(!isset($_GET['c']))
        {
            return 'Controller parameter is no exist';
        }else{
            if(!ctype_alnum($_GET['c']))
            {
                return 'Controller parameter is not allowed';
            }
        }
        if(!isset($_GET['a']))
        {
            return Array('c'=>$_GET['c'],'a'=>'start');
        }else{
            if(!ctype_alnum($_GET['a']))
            {
                return 'Action parameter is not allowed';
            }else{
                return Array('c'=>$_GET['c'],'a'=>$_GET['a']);
            }
        }
    }
    private static function __translate_from_path($pathinfo)
    {
        $pathinfo = trim($pathinfo,'/');
        $path_array = explode('/',$pathinfo);
        if(count($path_array)<=0)
        {
            return 'Controller parameter is no exist';
        }
        if(!ctype_alnum($path_array[0]))
        {
            return 'Controller parameter is not allowed';
        }
        if(count($path_array)<=1)
        {
            $request = Array('c'=>$path_array[0],'a'=>'start');
        }else{
            if(!ctype_alnum($path_array[1]))
            {
                return 'Action parameter is not allowed';
            }else{
                $request = Array('c'=>$path_array[0],'a'=>$path_array[1]);
            }
        }
        
        
        $other_array = array_slice($path_array, 2);
        $other_array_count = count($other_array);
        if($other_array_count)
        {
            if(($other_array_count%2)==0)
            {
                $i = 0;
                while($i < $other_array_count)
                {
                    $slice_array = array_slice($other_array,$i,2);
                    $_GET[$slice_array[0]] = $slice_array[1];
                    $i += 2;
                }
            }else{
                return 'Action parameter is not allowed';
            }
        }
        return $request;
    }
}

?>