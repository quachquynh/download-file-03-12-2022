<?php 
namespace App\Controllers;
use App\Core\Controller;

class Admin extends Controller {

	public function index() {
		echo 'http://localhost/download-file/ffmpeg/74/jp74/mp4/s1 - Convert mp4 to m3u8<br/>';
		echo 'http://localhost/download-file/fpt/upload/ - FPT Upload<br/>';
		echo 'http://localhost/download-file/download/{url} - Download Multiple Images<br/>';
		echo 'http://localhost/download-file/download-thumb/{url} - Download Thumb<br/>';
		echo 'http://localhost/download-file/google-drive/list - Google Drive List<br/>';
		echo 'http://localhost/download-file/google-drive/download/1CnfP8uoAJd62ul_FBommnbb_vfzxOedD/7 - Download Video Google Drive<br/>';

		echo 'http://localhost/download-file/downloadfile - Wordpress download | /public/download';
		//$this->view('majestic/index');
	}
}