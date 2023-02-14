<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;
use App\Core\DB;

class Wordpress extends Controller {

	public function insert_post() {
		if($_SERVER['REQUEST_METHOD'] == "POST") {
	    $title = 'admin';
	    $slug = '@@Hoquynh1234??';

	    $url_api= 'https://songmaweb.com/wp-json/wp/v2/posts';
	    $data = [
	        "title" => $_POST['title'],
	        "slug" => $_POST['slug'], 
	        "status" => $_POST['status'], 
	        "content" => $_POST['content'],
	        "categories" => $_POST['category']

	    ];
	    $fields = json_encode($data);
	    $ch=curl_init();
	    // user credencial
	    curl_setopt($ch, CURLOPT_USERPWD, "$title:$slug");
	    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_URL, $url_api);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	    curl_setopt($ch,CURLOPT_POST, 1);

	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
	    curl_setopt($ch, CURLOPT_VERBOSE, true);

	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	    $response = curl_exec($ch);

	    curl_close($ch);
	    var_dump($response);
		}
		else {
			$this->view('/api/insert_post');
		}
		
	}
}