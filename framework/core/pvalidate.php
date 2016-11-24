<?php
class Pvalidate
{
    public function numerical($value,$data=false)
    {
        $return_val = true;
        if(!empty($value))
            {
                if(preg_match('/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/',"$value"))
                {
                    if($data)
                    {
                        if(!preg_match('/^\s*[+-]?\d+\s*$/',"$value"))
                        {
                            $return_val = false;
                        }
                    }
                }else{
                    $return_val = false;
                }
            }
        return $return_val;
    }
    public function length($value,$data=array())
    {
        $return_val = true;
        $length=strlen($value);
	    if(isset($data['min']) && $length<$data['min'])
            {
                $return_val = false;
            }
            if(isset($data['max']) && $length>$data['max'])
            {
                $return_val = false;
            }
        return $return_val;
    }
    public function email($value)
    {
        $return_val = true;
        $pattern='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
        $fullPattern='/^[^@]*<[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?>$/';
        $valid = is_string($value) && (preg_match($pattern,$value));
        if(!$valid)
        {
            $return_val = false;
        }
        return $return_val;
    } 
}
?>