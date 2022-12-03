<?php 
namespace App\Controllers\Users;
use App\Core\Controller;
use App\Core\DB;

class Register extends Controller {

	private $db;

	public function __construct() {
		$this->db = new DB();
	}
	public function create_user() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['btn-submit'])) {
				$username = $_POST['username'];
				$email = $_POST['email'];
				$password = $_POST['password'];
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				$data = [
					'username' => $username,
					'email' => $email,
					'password' => $hashed_password
				];
				$user = $this->db->user_insert($data, 'users');
				if($user) {
					echo 'Thanh cong';
				}
				else {
					$login_url = ROOTURL. '/user/register';
					header('Location: ' . $login_url);
				}
			}
		}
		else {
			$this->view('users/login');
		}
		
	}
}