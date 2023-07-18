<?php

namespace App\V2\Form;

use Laminas\InputFilter\InputFilter;

class HomeworkFilter extends InputFilter {
	function __construct() {


		$this->add(array(
			'name'=>'title',
			'required'=>true,
			'validators'=>array(
			array(
				'name'=>'NotEmpty'
			)
		)
		));

		$this->add(array(
			'name'=>'content',
			'required'=>true,
				'validators'=>array(
						array(
								'name'=>'NotEmpty'
						)
				)
		));


		$this->add(array(
				'name'=>'course_id',
				'required'=>true,
				'validators'=>array(
						array(
								'name'=>'NotEmpty'
						)
				)
		));


		$this->add(array(
				'name'=>'description',
				'required'=>false,
		));



	}
}

?>
