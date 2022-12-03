<?php 
namespace App\Controllers;
use App\Core\Controller;
use Madcoda\Youtube;

class Api extends Controller {
	public function api_fb() {
		echo 'dfg';
	}

	public function api_yt() {
		$youtube = new Youtube(array('key' => 'AIzaSyCbuQ4pbc3smAXdkO9tDsb7g1s4wYE9FIU'));
		//$video = $youtube->getVideoInfo('rie-hPVJ7Sw');
		$video = $youtube->getPlaylistById('UCbuMSMTh5D7HFr_kpavrNTg');

		//var_dump($video);
		$this->view('majestic/index');
    	//$view->assign('variablename', 'variable content');
	}
}