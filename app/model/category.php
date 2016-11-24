<?php
class Category extends Pactiverecord
{
    
    public function rules()
    {
         return array(
            array('name', 'required'),
             // array('views', 'numerical',true),
            // array('name', 'email'),
            array('name', 'length', array('min' => 1, 'max' => 255)),
             array('show', 'length', array('min' => 1, 'max' => 1)),
             // array('author', 'length', 'max'=>500),
            // array('version', 'length', 'max'=>100),
            // array('demo, cn_description, en_description, add_time', 'safe'),
            // array('id, cn_title, en_title, demo, cn_description, en_description, author, yays, nays, views, category, icon, file, add_name, add_time, download_count, version, required, category_id, tag', 'safe', 'on'=>'search'),
            );
    }

    public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Category',
		);
	}

}
?>