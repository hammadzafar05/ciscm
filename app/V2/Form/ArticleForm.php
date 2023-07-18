<?php

namespace App\V2\Form;

use App\V2\Model\ArticlesTable;
use App\Lib\BaseForm;
use Laminas\Form\Form;

class ArticleForm extends BaseForm {
    private $parentSelectOptions;

    public function __construct($name = null,$serviceLocator,$exId=null)
    {
    	// we want to ignore the name passed
    	parent::__construct('user');
    	$this->setAttribute('method', 'post');
        $this->parentSelectOptions = array();
    	$this->serviceLocator = $serviceLocator;

    	$this->add(array(
    		'name'=>'alias',
    	    'attributes' => array(
    	    'type'=>'text',
    	    		'class'=>'form-control ',
                    'placeholder'=>'e.g. about-us'
    	        ),
    	    'options'=>array('label'=>__lang('url-slug').' ('.__lang('optional').')'),
    	));

    	$this->add(array(
    			'name'=>'article_name',
    	    'attributes' => array(
    			'type'=>'text',
    	    		'class'=>'form-control ',
    	    		'required'=>'required',
    	        ),
    			'options'=>array('label'=>__lang('Heading')),
    	));


    	$this->add(array(
    			'name'=>'article_content',
    			'attributes' => array(
    					'type'=>'textarea',
    					'class'=>'form-control ',
    					'required'=>'required',
    			),
    			'options'=>array('label'=>__lang('Content')),
    	));

        $this->createSelect('top_nav','Show on top navigation?',[1=>__lang('Yes'),0=>__lang('No')],false,false);
        $this->createSelect('bottom_nav','Show on bottom navigation?',[1=>__lang('Yes'),0=>__lang('No')],false,false);
        $this->createText('sort_order','sort-order-(optional)',false,'form-control number');
        $this->getCategoryChildren(0, 0,$exId);
        $this->createSelect('parent','Parent (Optional)',$this->parentSelectOptions);

        $this->createSelect('visibility','Visibility',[
            'w'=>__lang('Website'),
            'm'=>__lang('Mobile App'),
            'b'=>__lang('Website & Mobile App')
        ],true);



    }

    function getCategoryChildren($parent,$level,$exId=null)
    {
        $articlesTable = new ArticlesTable($this->serviceLocator);
        $rowset = $articlesTable->getArticlesForParent($parent);
        $repeater = '  |__  ';

        foreach($rowset as $row)
        {

            if (isset($exId) && $exId==$row->article_id) {
                continue;
            }

            $this->parentSelectOptions[$row->article_id] = str_repeat($repeater,$level).limitLength($row->article_name,100);
            $this->getCategoryChildren($row->article_id, $level+1,$row->article_id);
        }


    }

}

?>
