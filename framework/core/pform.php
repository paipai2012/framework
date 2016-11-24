<?php
class Pform
{
    private $_model;
    function __construct($model)
    {
        $this->_model = $model;
    }
    public function errorSummary()
    {
        if(isset($this->_model->_validate_info))
        {
            $attributeLabels = $this->_model->attributeLabels();
            foreach($this->_model->_validate_info as $key=>$info)
            {
                if(isset($attributeLabels[$key]))
                {
                    echo($attributeLabels[$key].' '.$info[0].'<br/>');
                }else{
                    echo(ucwords($key).' '.$info[0].'<br/>');
                }
            }
        }
        
    }
    public function labelEx($name) {
        $attributeLabels = $this->_model->attributeLabels();
        $rules = $this->_model->rules();
        $required_str = '';
        foreach($rules as $rule) {
            $name_array = explode(',',$rule[0]);
            if(in_array($name,$name_array))
            {
                if($rule[1] == 'required') {
                    $required_str = ' <span class="required">*</span>';
                    break;
                }
            }
        }
        if(isset($attributeLabels[$name]))
        {
            echo('<label for="ContactForm_show">'.$attributeLabels[$name].$required_str.'</label>');
        }else {
            echo('<label for="ContactForm_show">'.ucwords($name).$required_str.'</label>');
        }
    }
    public function textField($name,$attr=array('size'=>20,'maxlength'=>255)) {
        $attr_str = ' ';
        foreach($attr as $key=>$val) {
            $attr_str .= ' '.$key.'="'.$val.'"';
        }
        echo('<input name="'.$this->_model->tableName().'['.$name.']" id="'.$this->_model->tableName().'_'.$name.'" type="text" value="'.$this->_model->$name.'"'.$attr_str.' />');
    }
    public function msg($name) {
        $validate_info = $this->_model->_validate_info;
        //$rules_info = $this->_model->_rules_info;
        if(isset($validate_info[$name])){
            echo($validate_info[$name][0]);
        }
        /*
        else if(isset($rules_info[$name])){
            echo($rules_info[$name][0]);
        }
        */
    }
}
?>