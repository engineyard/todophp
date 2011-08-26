<?php
 
use \lithium\util\Validator;
 
Validator::add('unique', function (&$value, $format = null, array $options = array()) {
	$conditions = array(
		'_id' => array('$ne' => $options['values']['_id']),
		$options['field'] => $value,
	);
	$count = $options['model']::count(compact('conditions'));
 
	return $count == 0;
});

?>