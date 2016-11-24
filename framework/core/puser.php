<?php
class Puser
{
    private static $__table_name;
    private static $__username_field;
    private static $__password_field;
    private static $__create_field;
    private static $__lastlogin_field;
    private static $__type_field;
    private static $__rank_field;
    private static $__pin_field;
    private static $__db;
    public static $__user;
    function __construct($table_name='user',$username_field='username',$password_field='password',$create_field='create',$lastlogin_field='lastlogin',$type_field='type',$rank_field='rank',$pin_field='pin')
    {
        session_start();
        self::$__table_name = $table_name;
        self::$__username_field = $username_field;
        self::$__password_field = $password_field;
        self::$__create_field = $create_field;
        self::$__lastlogin_field = $lastlogin_field;
        self::$__type_field = $type_field;
        self::$__rank_field = $rank_field;
        self::$__pin_field = $pin_field;
        self::$__db = new Pdb();
        $this->setLoginUrl(Fw::getConfig('login_url'));
        $this->checkLogin();
    }
    public function checkLogin()
    {
        if(empty($_SESSION['user'])){
            if(empty($_COOKIE['username']) || empty($_COOKIE['pin'])){
                return false;
            }else{
                $user = self::$__db->findByAttributes(self::$__table_name,array(self::$__username_field => $_COOKIE['username']));
                if($_COOKIE['pin'] != $user['pin']){
                    return false;
                }else{
                    $session = array('name'=>$user['username'],'type'=>$user['type'],'rank'=>$user['rank'],'create'=>$user['create'],'lastlogin'=>$user['lastlogin']);
                    $_SESSION['user'] = $session;
                    self::$__user = $session;
                    return true;
                }
            }
        }else{
            return true;
        }
    }
    public function login($username,$password,$remember=false)
    {
        $user = self::$__db->findByAttributes(self::$__table_name,array(self::$__username_field => $username));
        if(md5($password) == $user[self::$__password_field])
        {
            $session = array('name'=>$user['username'],'type'=>$user['type'],'rank'=>$user['rank'],'create'=>$user['create'],'lastlogin'=>$user['lastlogin']);
            $_SESSION['user'] = $session;
            self::$__user = $session;
            if($remember)
            {
                $pin = Pstr::randStr(10);
                $where = 'id='.$user['id'];
                if(self::$__db->update(array(self::$__pin_field => $pin),self::$__table_name,$where))
                {
                    setcookie("username", $user['username'], time()+Fw::getConfig('remember_login_time'));
                    setcookie("pin", $pin, time()+Fw::getConfig('remember_login_time'));
                }
            }
            return true;
        }else{
            return false;
        }
        return false;
    }
    public function logout()
    {
        self::$__user = array();
        unset($_SESSION['user']);
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-42000, '/');
            setcookie('username', '', time()-42000);
            setcookie('pin', '', time()-42000);
        }
        session_destroy();
    }
    public function setLoginUrl($url)
    {
        $_SESSION['login_url'] = $url;
    }
    public function getLoginUrl()
    {
        return $_SESSION['login_url'];
    }
    public function setReturnUrl($url)
    {
        $_SESSION['return_url'] = $url;
    }
    public function getReturnUrl()
    {
        return $_SESSION['return_url'];
    }
    public function getUser()
    {
        if(!empty($_SESSION['user']))
        {
            return $_SESSION['user'];
        }else{
            return false;
        }
    }
    public static function getStatu()
    {
        $user = self::getUser();
        if(!$user){
            return array('type'=>'visitor');
        }else{
            return array('type'=>$user['type'],'rank'=>$user['rank']);
        }
    }
}
?>