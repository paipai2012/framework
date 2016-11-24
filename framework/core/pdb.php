<?php
class Pdb
{
    private static $__db;
    function __construct($db_type='mysql',$dsn=null)
    {
        switch ($db_type)
        {
            case 'mysql':
                require_once(dirname(__FILE__).'/db/mysql.php');
                $dbConfig = array(
                    'hostname' => Fw::getConfig('db_server'),
                    'username' => Fw::getConfig('db_user'),
                    'password' => Fw::getConfig('db_pw'),
                    'database' => Fw::getConfig('db_name'),
                    'dbms'     => 'mysql',
                    'dsn'      => 'mysql:host='.Fw::getConfig('db_server').';dbname='.Fw::getConfig('db_name')
                );
                self::$__db = new MysqlPdo($dbConfig);
                break;
            default:
                
        }
    }
    public function findById($table_name,$id,$fields)
    {
        return self::$__db->findById($table_name,$id,$fields);
    }
    public function findByAttributes($table_name,$attributes,$fields='*')
    {
        $where = '';
        $str_arr = array();
        foreach($attributes as $key=>$value)
        {
            $str_arr[] = " ".$key." = '".$value."' ";
        }
        $where .= implode(' AND ',$str_arr);
        return self::$__db->find($table_name,$where,$fields);
    }
    public function find($table_name,$where,$fields)
    {
        return self::$__db->find($table_name,$where,$fields);
    }
    public function getFields($tableName)
    {
        return self::$__db->getFields($tableName);
    }
    public function update($sets,$table,$where)
    {
        return self::$__db->update($sets,$table,$where);
    }
    public function add($data,$table)
    {
        return self::$__db->add($data,$table);
    }
    public function delete($where,$table)
    {
        return self::$__db->remove($where,$table);
    }
}
?>