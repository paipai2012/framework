<?php
    class Index extends Pcontroller
    {
        public function accessRules()
        {
            return array(
                array('allow',
                    'actions'=>array('start','view'),
                    'type'=>array('*'),//*: 任何用户
                    //'rank'=>array(1),
                ),
                array('allow',
                    'actions'=>array('create','update'),
                    'type'=>array('admin'),
                ),
                array('allow',
                    'actions'=>array('admin','delete'),
                    'type'=>array('admin'),
                ),
                array('deny',
                    'type'=>array('*'),
                ),
            );
        }
        public function start()
		{
            $category = Category::findById(1);
            if(isset($_POST['Category'])) {
                $category->eat($_POST['Category']);
                $category->save();
            }
            $this->render('/index',array('category'=>$category));
        }
    }
?>