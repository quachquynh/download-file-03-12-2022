<?php 
namespace App\Core;
use PDO;

class DB {

	protected $host_name = HOSTNAME;
	protected $database = HOSTDB;
	protected $username = HOSTUSER; 
	protected $password = HOSTPASS;
	protected $pbo;
	protected $table;

	public function __construct() {
		try {
			$this->pbo = new PDO('mysql:host='.$this->host_name.';dbname='.$this->database, $this->username, $this->password);
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}

	public function login($data, $table) {
		$username = $data['username'];
		$password = $data['password'];
		$stmt = $this->pbo->prepare("SELECT * FROM `$table` WHERE username=?");
		$stmt->execute(array($username));
		$user = $stmt->fetch();

		if (password_verify($password, $user['password'])) {
			$token = bin2hex(random_bytes(16));
			$id = $user['id'];
			$data_update = [
				'username' => $user['username'],
				'password' => $user['password'],
				'token' => $token
			];
			$this->user_update($id,$data_update, 'users');
			return $user;
		}
		else {
			$login_url = ROOTURL. '/user/login';
			header('Location: ' . $login_url);
		}
	}

	public function user_insert($data, $table) {
		$username = $data['username'];
		$email = $data['email'];
		$password = $data['password'];
		$stmt = $this->pbo->prepare("INSERT INTO $table (username, email, password) VALUES (?,?,?)");
		$stmt->execute(array($username,$email, $password));
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		return $user;
	}

	public function user_update($id, $data,$table) {
		$stmt = $this->pbo->prepare("UPDATE users SET username = :username, password = :pwd, token = :token WHERE id=:id");
    	$stmt->execute([':username' => $data['username'],':pwd' => $data['password'], ':token' => $data['token'], ':id'=> $id]);
		return $stmt;
	}

	public function table($table) {
		$this->table = $table;
	}

	public function insert($data) {
		$prep = array();
		foreach($data as $k => $v ) {
		    $prep[':'.$k] = $v;
		}
		$stmt = $this->pbo->prepare("INSERT INTO `$this->table` ( " . implode(', ',array_keys($data)) . ") VALUES (" . implode(', ',array_keys($prep)) . ")");
		$res = $stmt->execute($prep);
		return $res;

		//$sql = "INSERT INTO products (title, slug, body, price) VALUES (?,?,?,?)";
		//$stmt= $this->pbo->prepare($sql);
		//$stmt->execute($data);
		//return $stmt;
	}

}
