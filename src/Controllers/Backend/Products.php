<?php 
namespace App\Controllers\Backend;
use App\Core\Controller;
use App\Models\Product;
use App\Core\DB;

class Products extends Controller {

	public function index() {
		$this->view('majestic/index');
	}

	public function add_product() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 				
				$title = $_POST['title'];
				$slug = $_POST['slug'];
				$body = $_POST['body'];
				$price = $_POST['price'];
				$file_name = $_FILES['thumbnail']['name'];
				$file_tmp =$_FILES['thumbnail']['tmp_name'];

				$destination = 'public/media/'.$file_name;

				$data = [
					'title' => $title,
					'slug' => $slug,
					'body' => $body,
					'price' => $price,
					'thumbnail' => $destination
				];

				if(move_uploaded_file($file_tmp,$destination)) {
					$save = new DB();
					$save->table('products');
					$result = $save->insert($data);
					if($result) {
						echo 'Success';
					}
					else {
						echo 'Error';
					}
				}

		}
		elseif($_SERVER['REQUEST_METHOD'] === 'GET') {
			$this->view('majestic/header');
			$this->view('backend/insert');
			$this->view('majestic/footer');
		}
		
	}

	
}