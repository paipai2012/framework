<?php
    class Item extends Pcontroller
    {
        public function start()
        {
            $data = Array('name'=>'tom');
            $this->render('/item/index',$data);
        }
    }
?>