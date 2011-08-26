<?php

namespace app\models;

class Lists extends \lithium\data\Model {
	
	public $validates = array(
	    'name' => 'please enter a name'
	);

	protected $hasMany = array('Tasks');

}

?>