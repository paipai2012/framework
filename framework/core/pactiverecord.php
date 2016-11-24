<?php
class Pactiverecord
{
    private static $__db;
    private $_attributes;
    private $_md;
    private $_pk;
    private $_pk_name;
    public $_validate_info;
    public $_rules_info;
    public static $tableName;
    public static $className;
    function __construct()
    {
        self::$tableName = strtolower($this->tableName());
        self::$__db = new Pdb();
        $this->_md = self::$__db->getFields(self::$tableName);
    }
    public function tableName()
    {
	    return get_class($this);
    }
    public static function findById($pk_id,$fields='*')
    {
        $model = new self::$className();
	    $attributes = self::$__db->findById(self::$tableName,$pk_id,$fields);
        if(empty($attributes))
        {
            return false;
        }else{
            $model->_attributes = $attributes;
        }
	    $model->_pk = $pk_id;
        $model->_pk_name = 'id';
        return $model;
    }
    public function __get($name)
    {
        if(isset($this->_attributes[$name])){
            return $this->_attributes[$name];
        }else if(isset($this->$name)){
            return $this->$name;
        }else{
            return '';
        }
    }
    public function __set($name,$value)
    {
        if($this->setAttribute($name,$value)===false)
	{
	    Fw::$error->show($name.' is no exist.');
	}
    }
    public function eat($data) {
        $this->_attributes=$data;
    }
    public function setAttribute($name,$value)
    {
	if(property_exists($this,$name))
	    $this->$name=$value;
	else if(isset($this->_md[$name]))
	    $this->_attributes[$name]=$value;
	else
	    return false;
	return true;
    }
    public function save()
    {
        if($this->validate())
        {
            if(isset($this->_pk))
            {
                $where = $this->_pk_name.'='.$this->_pk;
                self::$__db->update($this->_attributes,self::$tableName,$where);
            }else{
                self::$__db->add($this->_attributes,self::$tableName);
            }
        }else{
            return false;
        }
    }
    public function delete()
    {
	if(isset($this->_pk))
	{
            $where = $this->_pk_name.'='.$this->_pk;
            self::$__db->delete($where,self::$tableName);
	}else{
	    Fw::$error->show('New model cannot delelt.');
	}
    }
    public function rules()
    {
	return array();
    }
    public function attributeLabels()
	{
		return array();
	}
    private function setRulesInfo()
    {
        $rules = $this->rules();
        $return_val = true;
        if(!empty($rules))
        {
            foreach($rules as $rule)
            {
                $action = trim($rule[1]);
                $field = explode(',',$rule[0]);
                if(isset($rule[2]))
                {
                    $data = $rule[2];
                }else{
                    $data = '';
                }
                switch($action) {
                    case 'required':
                        $this->_rules_info[trim($field)][] = 'Cannot be empty';
                        break;
                    case 'numerical':
                        $this->_rules_info[trim($field)][] = 'Must be an integer';
                        break;
                    case 'length':
                        $this->_rules_info[trim($field)][] = 'Must be match';
                        break;
                }
            }
        }
        return $return_val;
    }
    private function validate()
    {
        $rules = $this->rules();
        $return_val = true;
        if(!empty($rules))
        {
            foreach($rules as $rule)
            {
                $action = trim($rule[1]);
                $field = explode(',',$rule[0]);
                if(isset($rule[2]))
                {
                    $data = $rule[2];
                }else{
                    $data = '';
                }
                if(!$this->$action($field,$data))
                {
                    $return_val = false;
                }
            }
        }
        return $return_val;
    }
    private function required ($field,$data)
    {
        $return_val = true;
        foreach($field as $field_str)
        {
            if(isset($this->_attributes[trim($field_str)]))
            {
                $value = $this->_attributes[trim($field_str)];
            }else{
                Perror::show('rule field `'.$field_str.'` is no exist.');
            }
            if(empty($value) || !isset($value))
            {
                $this->_validate_info[trim($field_str)][] = 'cannot be empty.';
                $return_val = false;
            }
        }
        return $return_val;
    }
    private function numerical($field,$data=false)
    {
        $return_val = true;
        foreach($field as $field_str)
        {
            if(isset($this->_attributes[trim($field_str)]))
            {
                $value = $this->_attributes[trim($field_str)];
            }else{
                Perror::show('rule field: '.$field_str.' is no exist.');
            }
            
            if(!empty($value))
            {
                if(Pvalidate::numerical($value))
                {
                    if($data)
                    {
                        if(!Pvalidate::numerical($value,true))
                        {
                            $this->_validate_info[$field_str][] = 'must be an integer.';
                            $return_val = false;
                        }
                    }
                }else{
                    $this->_validate_info[$field_str][] = 'must be a number.';
                    $return_val = false;
                }
            }
        }
        return $return_val;
    }
    private function length($field,$data=array())
    {
        $return_val = true;
        foreach($field as $field_str)
        {
            if(isset($this->_attributes[trim($field_str)]))
            {
                $value = $this->_attributes[trim($field_str)];
            }else{
                Perror::show('rule field: '.$field_str.' is no exist.');
            }
	    if(!Pvalidate::length($value,$data) && !empty($value))
	    {
            if($data['min'] == $data['max']) {
                $this->_validate_info[$field_str][] = 'must be '.$data['max'].' characters.';
            }else{
                $this->_validate_info[$field_str][] = 'must be '.$data['min'].' - '.$data['max'].' characters.';
            }
            $return_val = false;
	    }
        }
        return $return_val;
    }
    private function email($field)
    {
        $return_val = true;
        foreach($field as $field_str)
        {
            if(isset($this->_attributes[trim($field_str)]))
            {
                $value = $this->_attributes[trim($field_str)];
            }else{
                Perror::show('rule field: '.$field_str.' is no exist.');
            }
            if(!Pvalidate::email($value))
            {
                $this->_validate_info[$field_str][] = 'is not a valid email address.';
                $return_val = false;
            }
        }
        return $return_val;
    }
}
?>