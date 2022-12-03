<?php 
namespace App\Controllers\Users;
use App\Core\Controller;
use App\Core\DB;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Login extends Controller {

	private $db;
	protected $user;

	public function __construct() {
		
	    try {
	    	// Enable us to use Headers
		    ob_start();
		    // Set sessions
		    if(!isset($_SESSION)) {
		        session_start();
		    }
	    	$this->db = new DB();
	    } catch (Exception $e) {
	    	echo $e;
	    }
		
	}
	public function login() {
		
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['btn-submit'])) {
				$username = trim($_POST['username']);
				$password = trim($_POST['password']);
				$data = [
					'username' => $username,
					'password' => $password
				];
				$user = $this->db->login($data, 'users');
				var_dump($user);
				$_SESSION['name'] = $user['username'];
				$_SESSION['pass'] = $user['password'];

					//$login_url = ROOTURL. '';
					//header('Location: ' . $login_url);
				
				
			}


		}
		else {
			$this->view('users/login');
			
		}
		
	}
}