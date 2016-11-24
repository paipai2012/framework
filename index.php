<?php
$myFramework=dirname(__FILE__).'/framework/framework.php';
$config=dirname(__FILE__).'/app/config.php';
require_once($myFramework);
Fw::run($config);