<?php
class Pcontroller
{
    public $title;
    public function run()
    {
	$this->title = ucwords(implode(Fw::getUri(),' - '));
    }
    public function accessRules()
    {
	return array();
    }
    private function ruleCheckAction($rule_action,$action)
    {
	return is_null($rule_action)||in_array('*',$rule_action)||in_array($action,$rule_action);
    }
    private function ruleCheckType($rule_type,$user_type)
    {
    return in_array('*',$rule_type)||in_array($user_type,$rule_type);
    }
    private function ruleCheckRank($rule_rank,$user_rank)
    {
	return is_null($rule_rank)||in_array($user_rank,$rule_rank);
    }
    public function actionFactory($action)
    {
	$rules = $this->accessRules();
	$status = Puser::getStatu();
	if(!empty($rules))
	{
	    foreach($rules as $rule)
	    {
		if(isset($rule['actions']))
		{
		    $rule_action = $rule['actions'];
		}else{
		    $rule_action = null;
		}
		if(isset($rule['rank']))
		{
		    $rule_rank = $rule['rank'];
		}else{
		    $rule_rank = null;
		}
		$rule_type = $rule['type'];
		if(isset($status['rank']))
		{
		    $user_rank = $status['rank'];
		}else{
		    $user_rank = null;
		}
		if($rule[0] == 'allow' && $this->ruleCheckAction($rule_action,$action) && $this->ruleCheckType($rule_type,$status['type']) && $this->ruleCheckRank($rule_rank,$user_rank))
		{
		    $this->$action();
		    exit;
		}
		/*
		if(isset($rule['actions']))
		{
		    if(in_array($action,$rule['actions']))
		    {
			if(in_array('*',$rule['type']))
			{
			    $this->$action();
			    exit;
			}else{
			    if(in_array($status['type'],$rule['type'])
			    {
				if(isset($rule['rank']))
				{
				    if(in_array($status['rank'],$rule['rank']))
				    {
					if()
				    }
				}else{
				    $this->$action();
				    exit;
				}
			    }
			}
		    }
		}else{
		    if($rule[0] == 'allow')
		    {
			$this->$action();
			exit;
		    }else{
			$current_url =  "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
			Puser::setReturnUrl($current_url);
			header(Puser::getLoginUrl());
			exit;
		    }
		}
		*/
	    }
        $current_url =  "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
        Puser::setReturnUrl($current_url);
        header("Location: ".Fw::createUrl(Puser::getLoginUrl())); 
        exit;
	    //Perror::msg($code=404,$error_msg='Sorry, access deny.');
	}else{
	    $this->$action();
	}
    }
    public function render($filename,$data=null)
    {
        if(is_array($data))extract($data,EXTR_PREFIX_SAME,'data');
	    ob_start();
	    ob_implicit_flush(false);
	    require(Fw::getConfig('app_directory').'/view'.$filename.'.php');
        $content = ob_get_clean();
        ob_start();
	    ob_implicit_flush(false);
        require(Fw::getConfig('app_directory').'/view/main.php');
	    echo(ob_get_clean());
    }
}
?>