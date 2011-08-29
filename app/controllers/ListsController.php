<?php

namespace app\controllers;

use app\models\Lists;

class ListsController extends \lithium\action\Controller {
	
	
	public function index() {
		
		$lists = Lists::all();
	    return compact('lists');
	        
	}
	
	public function create() {
	        
	}
	
	
	public function update() {
	        
	}
	
	public function destroy() {
	        
	}
	
}

?>