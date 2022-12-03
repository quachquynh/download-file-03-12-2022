<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;

class Stream extends Controller {

	public function get_data() {
		$a = scandir(ROOTPATH.'\\public\media\a');
		var_dump($a);
		$this->view('hls-stream', $a);

		?>

		
	<?php }


	public function stream() {?>
		<video width="640" height="360" controls>
		  <source src="https://cdn01.chongmong.xyz/" type="video/mp4">
		  <source src="movie.ogg" type="video/ogg">
		Your browser does not support the video tag.
		</video>
	<?php }

}
