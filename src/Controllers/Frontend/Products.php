<?php
namespace App\Controllers\Frontend;
use App\Core\Controller;

class Products extends Controller {

	public function index() {
		$this->view('frontend/furn/index');
	}

	public function single() {
		$this->view('frontend/furn/index');
	}
}