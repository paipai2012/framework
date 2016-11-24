<?php
class Perror
{
    function __construct()
    {
        
    }
    public function show($msg,$code=400)
    {
        echo('Error info: '.$msg);
        exit;
    }
    public function msg($code=404,$error_msg='Sorry, the page you requested cannot be found.')
    {
        ob_start();
	ob_implicit_flush(false);
	require(Fw::getConfig('app_directory').'/view/error/'.$code.'.php');
        $content = ob_get_clean();
        ob_start();
	ob_implicit_flush(false);
        require(Fw::getConfig('app_directory').'/view/main.php');
	echo(ob_get_clean());
    }
}
?>