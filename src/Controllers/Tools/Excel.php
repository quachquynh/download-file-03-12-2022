<?php
namespace App\Controllers\Tools;
use Shuchkin\SimpleXLSX;
class Excel {
	public function read() {
		$dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $dbname = 'blogs';
        $mysql = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
         
         if($mysql ) {
            echo "Ket noi thanh cong";
            //exit();
         }
		$query = "CREATE TABLE IF NOT EXISTS `member` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`stt` varchar(15) NOT NULL,
			`sobaodanh` varchar(50) NOT NULL,
			`mahocsinh` varchar(100) NOT NULL,
			`hoten` varchar(100) NOT NULL,
			`ngaysinh` varchar(100) NOT NULL,
			`toan` varchar(100) NOT NULL,
			`tiengviet` varchar(100) NOT NULL,
			`nguvan` varchar(100) NOT NULL,
			`ghichu` varchar(100) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1";

		if (mysqli_query($mysql, $query)) {
		  echo "A new database called mycompany is successfully created!";
		} else {
		  echo "Error:" . mysqli_error($mysql);
		}

		mysqli_close($mysql);
	}
}